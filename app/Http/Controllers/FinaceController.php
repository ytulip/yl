<?php

namespace App\Http\Controllers;

use App\Log\Facades\Logger;
use App\Model\CashStream;
use App\Model\Coupon;
use App\Model\Deliver;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductAttr;
use App\Model\SubFoodOrders;
use App\Model\User;
use App\Model\VipOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class FinaceController extends Controller
{

    /**
     * 微信支付回调
     */
    public function anyWechatNotify()
    {
        Logger::info(Request::getContent(),'wxs');
        $request = Request::getContent();
        $wxObj = simplexml_load_string($request, 'SimpleXMLElement', LIBXML_NOCDATA);
        if( $wxObj->result_code != "SUCCESS"){
            return;
        }
        $tradeNo = $wxObj->out_trade_no;



        if( strpos($tradeNo,'v') === 0 )
        {

            $tradeNo = str_replace('v','',$tradeNo);
            $vipOrder = VipOrder::find($tradeNo);
            if( $vipOrder->pay_status )
            {
                return 'SUCCESS';
            }
            $vipOrder->pay_status = 1;
            $vipOrder->save();



            $vipOrder->doCoupon();

            //购买会员的
            $user = User::find($vipOrder->user_id);
            $month = (in_array($vipOrder->buy_type,[1,3]))?3:6;


            $expireMonth = Carbon::parse(Carbon::now()->format('Y-m'));
            $expireMonth->addMonths($month);
            if( $expireMonth->daysInMonth < date('d') )
            {
                //到期日子没有今天的日子，那到期日就是他了
                $expireMonth->addDays($expireMonth->daysInMonth - 1);
            } else {
                //到期日要减一天
                $expireMonth->addDays(date('d'))->subDay()->subDay();
            }
            $user->pay_day = (date('d') == 1)?31:(date('d') - 1);

            $user->expire_time= $expireMonth->format('Y-m-d');
            $user->health_count = 1;


            /**
             * 发放优惠券
             */

            $user->save();
            return "SUCCESS";
        }




        //TODO 处理订单
        $order = Order::find($tradeNo);
        $order->pay_status = 1;
        $order->pay_time = date('Y-m-d H:i:s');
        $order->order_status = Order::ORDER_STATUS_WAIT_DELIVER;
        $order->save();



        $couponIdsStr = $order->coupons;
        if( $couponIdsStr )
        {
            $couponIds = explode(',',$couponIdsStr);
        } else {
            $couponIds = [];
        }


        /**
         * 优惠券置为已使用
         */
        foreach ($couponIds as $couponId) {
            $coupon = Coupon::find($couponId);
            $coupon->order_id = $order->id;
            $coupon->status = 2;
            $coupon->save();
        }

        $product = Product::find($order->product_id);

        //如果是订餐订单的话插入小订单
        if( !$product->isCleanProduct() )
        {

            $carbon = Carbon::parse($order->service_start_time);

            for($i = 0; $i < $order->days ; $i++ )
            {

                if(in_array($product->food_type,[1,2])) {
                    $subFoodOrders = new SubFoodOrders();
                    $subFoodOrders->order_id = $order->id;
                    $subFoodOrders->date = $carbon->format('Y-m-d');
                    $subFoodOrders->status = 0;
                    $subFoodOrders->type = 1;
                    $subFoodOrders->product_id = $product->id;
                    $subFoodOrders->save();
                }


                if( in_array($product->food_type,[1,3])) {
                    $subFoodOrders = new SubFoodOrders();
                    $subFoodOrders->order_id = $order->id;
                    $subFoodOrders->date = $carbon->format('Y-m-d');
                    $subFoodOrders->status = 0;
                    $subFoodOrders->type = 2;
                    $subFoodOrders->product_id  = $product->id;
                    $subFoodOrders->save();
                }

                $carbon->addDay(1);
            }
        }


        return "SUCCESS";
    }

}