<?php

namespace App\Model;

use App\Log\Facades\Logger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    const LEVEL_ACTIVITY = 0;
    const LEVEL_VIP = 1;
    const LEVEL_MASTER = 2;

    public static function levelText($level)
    {
        $text = '';
        switch ($level)
        {
            case User::LEVEL_ACTIVITY:
                $text = '活动会员';
                break;
            case User::LEVEL_VIP:
                $text = "天使会员";
                break;
            case User::LEVEL_MASTER:
                $text = "高级会员";
                break;
        }
        return $text;
    }

    public function monthIncome()
    {
        return DB::table('cash_stream')->where('user_id',$this->id)->whereIn('cash_type',CashStream::incomeCashTypeArr())->whereRaw('DATE_FORMAT(created_at,"%Y-%m") = "' . date('Y-m') . '"')->where('pay_status','>','0')->sum('price');
    }

    public function validInvitedCodeCount()
    {
        return count($this->myInvitedCodes(['code_status'=>0]));
    }

    private function monthCountAndSum($filterArr)
    {
        $price = DB::table('cash_stream')->where('user_id',$this->id)->where($filterArr)->whereRaw('DATE_FORMAT(created_at,"%Y-%m") = "' . date('Y-m') . '"')->where('pay_status','>','0')->sum('price');
        $count = DB::table('cash_stream')->where('user_id',$this->id)->where($filterArr)->whereRaw('DATE_FORMAT(created_at,"%Y-%m") = "' . date('Y-m') . '"')->where('pay_status','>','0')->count('id');
        return ['count'=>$count,'price'=>$price];
    }

    public function monthIncomeDetail()
    {
        $res = [];
        $res['total'] = ['price'=>$this->monthIncome()];
        $res['direct_vip'] =$this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_DIRECT,'sub_cash_type'=>User::LEVEL_VIP]);;
        $res['direct_master'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_UP,'sub_cash_type'=>User::LEVEL_MASTER]);;
        $res['indirect_vip'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_INDIRECT,'sub_cash_type'=>User::LEVEL_VIP]);;
        $res['indirect_master'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_INDIRECT,'sub_cash_type'=>User::LEVEL_MASTER]);;
        $res['up'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_UP]);
        $res['super'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_SUPER]);
        return $res;
    }

    public function upAndSuperCount()
    {
        return DB::table('cash_stream')->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER])->where('user_id',$this->id)->where('pay_status','>',0)->count();
    }

    public function upAndSuperList()
    {
        $list = DB::table('cash_stream')->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER])->where('user_id',$this->id)->leftJoin('users','cash_stream.refer_user_id','=','users.id')->where('pay_status','>',0)->selectRaw('cash_stream.*,users.phone,users.vip_level,users.real_name')->orderBy('cash_stream.id','desc')->get();
        return $list?$list:[];
    }

    public function directAndIndirectList()
    {
        $list = DB::table('cash_stream')->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT])->where('user_id',$this->id)->leftJoin('users','cash_stream.refer_user_id','=','users.id')->where('pay_status','>',0)->selectRaw('cash_stream.*,users.phone,users.vip_level,users.real_name')->orderBy('cash_stream.id','desc')->get();
        return $list?$list:[];
    }

    public function directAndIndirectCount()
    {
        return DB::table('cash_stream')->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT])->where('user_id',$this->id)->where('pay_status','>',0)->count();
    }

    public function relationMap()
    {
        $invitedCode = InvitedCodes::where('user_id',$this->id)->first();
        if( !$invitedCode ) {
            $indirect = null;
            $direct = null;
        } else {
            $order = Order::getOrderByInvitedCode($invitedCode->invited_code);
            $indirect = User::find($order->user_id);
            $direct = User::find($order->immediate_user_id);
        }
        $up = User::find($this->parent_id);
        $super = User::find($this->indirect_id);

        return (object)['direct'=>$direct,'indirect'=>$indirect,'up'=>$up,'super'=>$super];
    }

    public static function getCurrentUser()
    {
        return User::find(Auth::id());
    }

    /**
     * 作为简介开发者的邀请码们
     */
    public function myInvitedCodes($filter=[])
    {
        $res = Order::where('orders.user_id',$this->id)->where('order_status','>',0)->leftJoin('invited_codes','orders.id','=','refer_order_id')->where(function($query) use($filter){
            if($filter){
                $query->where($filter);
            }
        })->orderBy('invited_codes.id','desc')->get();
        return $res?$res:[];
    }

    public function countOrder()
    {
        return DB::table('orders')->where('user_id',Auth::id())->where('order_status','>',0)->count();
    }

    public function staticalCashStream()
    {
        $withdrawCount = CashStream::where('cash_type',CashStream::CASH_TYPE_WITHDRAW_AGREE)->where('user_id',$this->id)->count();
        $withdraw = CashStream::where('cash_type',CashStream::CASH_TYPE_WITHDRAW_AGREE)->where('user_id',$this->id)->sum('price');

        $directCount = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_DIRECT)->where('user_id',$this->id)->count();
        $direct = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_DIRECT)->where('user_id',$this->id)->sum('price');

        $indirectCount = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_INDIRECT)->where('user_id',$this->id)->count();
        $indirect = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_INDIRECT)->where('user_id',$this->id)->sum('price');

        $upCount = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_UP)->where('user_id',$this->id)->count();
        $up = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_UP)->where('user_id',$this->id)->sum('price');

        $superCount = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_SUPER)->where('user_id',$this->id)->count();
        $super = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_SUPER)->where('user_id',$this->id)->sum('price');

        return ['withdraw'=>$withdraw,'withdrawCount'=>$withdrawCount,'direct'=>$direct,'directCount'=>$directCount,'indirect'=>$indirect,'indirectCount'=>$indirectCount,'up'=>$up,'upCount'=>$upCount,'super'=>$super,'superCount'=>$superCount];

    }

    public function getDefaultAddress()
    {

    }


    /**
     * 下级会员列表
     */
    public function subList()
    {
        $res = User::where('parent_id',$this->id)->orderBy('id','desc')->get();
        if(!$res)
        {
            $res = [];
        }

        return $res;
    }

    /**
     * 下级会员列表
     */
    public function subListCount()
    {
        return User::where('parent_id',$this->id)->orderBy('id','desc')->count();
    }


    /**
     * 下级会员列表
     */
    public function subDeepList()
    {
        $res = User::where('indirect_id',$this->id)->orderBy('id','desc')->get();
        if(!$res)
        {
            $res = [];
        }

        return $res;
    }

    /**
     * 下级会员列表
     */
    public function subDeepListCount()
    {
        return User::where('indirect_id',$this->id)->orderBy('id','desc')->count();
    }


    public function isVip()
    {
        if( date('Y-m-d',$this->expire_time) > date('Y-m-d') )
        {
            return true;
        } else {
            return false;
        }
    }

    public function vipExpireDay()
    {
        if( date('Y-m-d',strtotime($this->expire_time)) > date('Y-m-d') )
        {
            return date('Y-m-d',strtotime($this->expire_time));
        } else {
            return false;
        }
    }



    public static function makeActivityUser($arr)
    {
        $user = \App\Model\User::where('phone',$arr['phone'])->first();

        if($user instanceof \App\Model\User)
        {
            Logger::info('用户已存在' . $arr['phone'],'cz');

            if( $user->activity_pay )
            {
                Logger::info('用户已存在已支付活动订单' . $arr['phone'],'cz');
                return ['status'=>false,'desc'=>'用户已存在已支付活动订单'];
            }

            $user->activity_pay = 1;
            $user->save();
        } else
        {
            Logger::info('用户新建' . $arr['phone'],'cz');
            $user = new \App\Model\User();
            $user->phone = $arr['phone'];
            $user->vip_level = \App\Model\User::LEVEL_ACTIVITY;
            $user->origin_vip_level = \App\Model\User::LEVEL_ACTIVITY;

            $user->real_name = $arr['real_name'];
            $user->id_card = $arr['id_card'];
            $user->activity_pay = 1;
            $user->save();
        }


        //下单
        //没有订单则创建订单哟

        //判断是否有活动订单
        $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();

        if( !$order instanceof  Order)
        {
            $order = new Order();
        }
        $order->user_id = $user->id;
        $order->buy_type = Order::BUY_TYPE_ACTIVITY;
        $order->need_pay = Product::find(2)->price;
        $order->immediate_user_id = $arr['immediate_id'];

        $order->pay_status = 1;
        $order->order_status = Order::ORDER_STATUS_DELIVERED;
        $order->quantity = 1;
//        $order->pay_type = CashStream::CASH_PAY_TYPE_WECHAT;
        $order->pay_time = date('Y-m-d H:i:s');
        $order->activity_by_admin = 1;
        $order->save();

        $user->activity_get_good = 1;
        $user->save();

        return ['status'=>true,'desc'=>'创建活动订单成功'];

    }

    /**
     * 获得活动支付的订单
     */
    public function getActivityPayedOrder()
    {
        return Order::where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('user_id',$this->id)->where('pay_status',1)->first();
    }

    /**
     * 是否需要去退款
     */
    public function needToTurn()
    {
        $order = $this->getActivityPayedOrder();
        if( !($order instanceof  Order) )
        {
            return false;
        }

        $count = CashStream::where('cash_type',CashStream::CASH_TYPE_ACTIVITY_WITHDRAW)->whereNotIn('withdraw_deal_status',[2])->count();

        if( !$count )
        {
            return false;
        }

        return true;
    }

    public static function tryGetRealName($userId,$default = '')
    {
        $user = User::find($userId);
        return isset($user->real_name)?$user->real_name:$default;
    }
}