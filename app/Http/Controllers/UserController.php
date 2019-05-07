<?php

namespace App\Http\Controllers;

use App\Model\Book;
use App\Model\CashStream;
use App\Model\Coupon;
use App\Model\Deliver;
use App\Model\Essay;
use App\Model\FinanceClass;
use App\Model\FinanceUser;
use App\Model\FoodMenu;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\Neighborhood;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductAttr;
use App\Model\RandomGet;
use App\Model\SubFoodOrders;
use App\Model\SyncModel;
use App\Model\User;
use App\Model\UserAddress;
use App\Model\UserHabit;
use App\Model\UserHabit2;
use App\Model\VipOrder;
use App\Model\YlConfig;
use App\Util\DealString;
use App\Util\FoodTime;
use App\Util\Kit;
use App\Util\SmsTemplate;
use Carbon\Carbon;
use Faker\Provider\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Ytulip\Ycurl\Kits;

class UserController extends Controller
{

    private $user;
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->user = User::find(Auth::id());
    }

    //首页
    public function getIndex()
    {
        $product = Product::find(1);
        $list = DB::table('essays')->orderBy('sort','desc')->get();
        return view('index')->with('product',$product)->with('list',$list);
    }

    /**
     * 个人中心
     */
    public function getCenter()
    {
        return view('center')->with('user',$this->user);
    }

    public function getReportBill()
    {
        return view('report_bill')->with('product',Product::find(Request::input('product_id')));
    }

    /**
     * 编辑用户信息
     */
    public function anyEditUser()
    {
        $type = Request::input('type');
        $user = User::find(Auth::id());

        /**
         * type 1修改姓名，2修改身份证号，3修改手机号
         */
        if( $type == 3)
        {
            if (Cache::get('register_sms_code' . Request::input('phone')) != (Request::input('phone') . '_' . Request::input('register_sms_code'))) {
                return $this->jsonReturn(0, '验证码错误');
            }



            $phone = Request::input('phone');
            /*手机号已被使用*/
            if( User::where('phone',$phone)->count() )
            {
                return $this->jsonReturn(0,$phone . '已被使用');
            }

            $user->phone = $phone;

        }else if( $type == 2)
        {
            $user->id_card = Request::input('id_card');
        }else if( $type == 1 )
        {
            $user->real_name = Request::input('real_name');
        }else if( $type == 4 )
        {
            $user->age = Request::input('age');
        }

        $user->save();
        return $this->jsonReturn(1);
    }


    public function postReportVip()
    {


        $order = new VipOrder();
        $order->buy_type = Request::input('type');
        $order->price = 1;
        $order->user_id = Auth::id();
        $order->save();

        //调起微信支付
        require_once base_path() . "/plugin/swechatpay/lib/WxPay.Api.php";
        require_once base_path() . "/plugin/swechatpay/example/WxPay.JsApiPay.php";


        $tools = null;
        $openid = Request::input('user_openid');
        $tools = new \JsApiPay();

        //创建支付订单
//        $cashStream = new CashStream();
//        $cashStream->refer_id = $order->id;
//        $cashStream->cash_type = CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT;
//        $cashStream->user_id = Auth::id();
//        $cashStream->price = $order->need_pay;
//        $cashStream->save();

        //付款金额，必填
        if( env('PAY_TEST')) {
            $total_amount = 1;
        } else {
            $total_amount = $order->price * 100;
        }




        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("花甲会员");
        $input->SetAttach("花甲会员");
        $input->SetOut_trade_no('v' . $order->id);//这个订单号是特殊的
        $input->SetTotal_fee($total_amount); //钱是以分计的
        $input->SetTime_start(date("YmdHis"));
        $input->SetGoods_tag("花甲汇演");
        $input->SetNotify_url(env('WECHAT_NOTIFY_URL'));
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $payOrder = \WxPayApi::unifiedOrder($input);
        $tools = new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($payOrder);
        return $this->jsonReturn($order->id,$jsApiParameters);
    }

    /**
     * 高级会购买
     */
    public function postReportBill()
    {
        $product = Product::find(Request::input('product_id'));


        if($product->type != 1)
        {
            //购买食物
        }

        $user = User::getCurrentUser();


        //取价格和取面积
        $productAttr = ProductAttr::find(Request::input('attr_id'));


        $order = new Order();
        if( $product->isCleanProduct() )
        {
            //获得打扫时长
            $clean_service_time = Request::input('clean_service_time');


            $order->product_id = $product->id;
            $order->product_name = $product->product_name;
            $order->need_pay = $product->price * Kit::cleanServiceTimeType($clean_service_time);
            $order->origin_pay = $product->price * Kit::cleanServiceTimeType($clean_service_time);
            $order->quantity = Kit::cleanServiceTimeType($clean_service_time);
            $order->user_id = $user->id;
            $order->remark = Request::input('remark');
//            $order->service_time = Request::input('clean_service_time');
//            $order->service_time = '';
            /*新增remark*/
            UserHabit2::addHabit($order->user_id,$order->remark);


            //处理预约时间
            $serviceStartTime = Request::input('service_start_time');
            $foodTime = new FoodTime();
            $timeList = $foodTime->startTimeList();

            $order->service_time = $timeList[$serviceStartTime[0]];
            $order->service_start_time = $order->service_time;

            $hourList = FoodTime::hoursList();
            $minList = FoodTime::minList();
            $order->begin_time = $hourList[$serviceStartTime[1]] . ':' . $minList[$serviceStartTime[2]];


        } else
        {
            //可以提取公共
            $order->product_id = $product->id;
            $order->product_name = $product->product_name;
            $order->quantity = Request::input('quantity');
//            $order->product_attr_id = $productAttr->id;
//            $order->need_pay = $product->price;
            $order->origin_pay = $product->price * $order->quantity * Order::getDaysByType(Request::input('tabIndex')) * Order::saleOff(Request::input('tabIndex'));
            $order->user_id = $user->id;
            $order->remark = Request::input('remark');


            /*新增remark*/
            UserHabit::addHabit($order->user_id,$order->remark);


            $order->buy_type = 100;
            $order->days = Order::getDaysByType(Request::input('tabIndex'));

            $order->lunch_service_time = Request::input('lunch_service');
            $order->dinner_service_time = Request::input('dinner_service');
            $order->service_start_time = Request::input('service_start_time');



        }

        //保存地址哟
        $order->address = Request::input('pct_code_name') . Request::input('address');
        $order->address_name = Request::input('name');
        $order->address_phone = Request::input('phone');


        //模拟支付
        if(env('MOCK_PAY'))
        {
            $order->pay_status = 1;
            $order->pay_time = date('Y-m-d H:i:s');
            $order->order_status = Order::ORDER_STATUS_WAIT_DELIVER;
        }


        $order->save();


        //如果是订餐订单的话插入小订单
        if( !$product->isCleanProduct() )
        {

            $carbon = Carbon::parse(Request::input('service_start_time'));

            for($i = 0; $i < $order->days ; $i++ )
            {
                $subFoodOrders  = new SubFoodOrders();
                $subFoodOrders->order_id = $order->id;
                $subFoodOrders->date = $carbon->format('Y-m-d');
                $subFoodOrders->status = 0;
                $subFoodOrders->type = 1;
                $subFoodOrders->product_id  = $product->id;
                $subFoodOrders->save();


                if( !in_array($product->id,[6])) {
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



        //查看代金券是否抵扣完全，如果代金券抵扣完全，那么直接跳转到支付成功页面
        /*使用代金券数目*/
        $couponIdsStr = Request::input('couponIds');
        if( $couponIdsStr )
        {
            $couponIds = explode(',',$couponIdsStr);
        } else {
            $couponIds = [];
        }
        $order->coupons = $couponIdsStr;
        $order->save();


        if( !$product->isCleanProduct() ) {


            if (count($couponIds) == $order->quantity * $order->days) {
                /**把优惠券置为已使用**/
                foreach ($couponIds as $couponId) {
                    $coupon = Coupon::find($couponId);
                    $coupon->order_id = $order->id;
                    $coupon->status = 2;
                    $coupon->save();
                }


                //标识全部已由优惠券抵扣，无需再支付
                return $this->jsonReturn(1, 333);
            }
        } else {
            if( count($couponIds) )
            {
                /**把优惠券置为已使用**/
                foreach ($couponIds as $couponId) {
                    $coupon = Coupon::find($couponId);
                    $coupon->order_id = $order->id;
                    $coupon->status = 2;
                    $coupon->save();
                }

                return $this->jsonReturn(1, 333);
            }

        }


        //减去优惠券张数

        if( $product->isCleanProduct() )
        {

        } else {
            $order->need_pay = $product->price * ($order->quantity * Order::getDaysByType(Request::input('tabIndex')) - count($couponIds)) * Order::saleOff(Request::input('tabIndex'));
            $order->save();
        }


        //调起微信支付
        require_once base_path() . "/plugin/swechatpay/lib/WxPay.Api.php";
        require_once base_path() . "/plugin/swechatpay/example/WxPay.JsApiPay.php";


        $tools = null;
        $openid = Request::input('user_openid');
        $tools = new \JsApiPay();

        //创建支付订单
//        $cashStream = new CashStream();
//        $cashStream->refer_id = $order->id;
//        $cashStream->cash_type = CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT;
//        $cashStream->user_id = Auth::id();
//        $cashStream->price = $order->need_pay;
//        $cashStream->save();

        //付款金额，必填
        if( env('PAY_TEST')) {
            $total_amount = 1;
        } else {
            $total_amount = $order->price * 100;
        }




        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("花甲服务");
        $input->SetAttach("花甲服务");
        $input->SetOut_trade_no($order->id);//这个订单号是特殊的
        $input->SetTotal_fee($total_amount); //钱是以分计的
        $input->SetTime_start(date("YmdHis"));
        $input->SetGoods_tag("花甲服务");
        $input->SetNotify_url(env('WECHAT_NOTIFY_URL'));
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $payOrder = \WxPayApi::unifiedOrder($input);
        $tools = new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($payOrder);
        return $this->jsonReturn(1,$jsApiParameters);
//        return $this->jsonReturn(1,'下单成功');
    }

    /*支付订单页面*/
    public function getPayBill()
    {
        $orderId = Request::input('order_id');
        $order = Order::where(['user_id'=>Auth::id(),'id'=>$orderId])->first();
        if( !$order ) {
            dd('购买不存在，或者无权访问');
        }

        if( $order->status ) {
            dd('请勿重复购买');
        }
        $user = User::getCurrentUser();
        return view('pay_bill')->with('order',$order)->with('user',$user)->with('product',Product::getDefaultProduct());
    }

    public function getFinance()
    {
        $user = User::getCurrentUser();
        return view('finance')->with('user',$user);
    }

    public function getOrders()
    {
        $res = DB::table('orders')->leftJoin('products','product_id','=','products.id')->leftJoin('product_attrs','product_attr_id','=','product_attrs.id')->where('user_id',Auth::id())->where('order_status','>',0)->selectRaw('*,orders.created_at as order_created_at,orders.id as order_id')->orderBy('orders.id','desc')->get();
        $res = $res?$res:[];
        return view('orders')->with('orders',$res);
    }

    public function getOrderDetail()
    {
        $order = Order::where('user_id',Auth::id())->where('id',Request::input('order_id'))->first();
        if (!$order)
        {
            dd('无效订单');
        }
        return view('order_detail')->with('order',$order)->with('direct',User::find($order->immediate_user_id))->with('productAttr',ProductAttr::find($order->product_attr_id));
    }

    public function getOrderDetailData()
    {
        $order = Order::where('user_id',Auth::id())->where('id',Request::input('order_id'))->first();
        if (!$order)
        {
            return $this->jsonReturn(0);
        }
        $product = Product::find($order->product_id);

        /**
         * 两周内的食物
         */
        $foodTime = new FoodTime();
        $dates = $foodTime->startTimeList();
        $dates = date('Y-m-d') . ',' . implode(',',$dates);
        $dates = explode(',',$dates);
        $res = [];

        foreach ( $dates as $date)
        {
            $foods = FoodMenu::where('product_id',$product->id)->where('date',$date)->get();

            $lunch = (Object)[];
            $dinner = (Object)[];

            foreach ( $foods as $food)
            {
                if( $food->type == 1)
                {
                    $lunch = $food;
                } else
                {
                    $dinner = $food;
                }
            }

            $res[$date]['lunch'] = $lunch;
            $res[$date]['dinner'] = $dinner;
        }

        $pastDays = SubFoodOrders::where('order_id',$order->id)->where('date','<',date('Y-m-d'))->get();
        $days = SubFoodOrders::where('order_id',$order->id)->where('date','>=',date('Y-m-d'))->get();


        return $this->jsonReturn(1,['order'=>$order,'user'=>User::find($order->user_id),'product'=>$product,"res"=>$res,"pastDays"=>$pastDays,"days"=>$days]);
    }

    public function getAddresses()
    {
        return view('addresses')->with('addressList',UserAddress::mineAddressList(Auth::id()));
    }

    public function anyAddressData()
    {
        return $this->jsonReturn(1,UserAddress::mineAddressList(Auth::id()));
    }

    public function anyUserAddressData()
    {
        $address = UserAddress::find(Request::input('address_id'));
        return $this->jsonReturn(1,$address);
    }

    public function anyAddressInfo()
    {
        //组装社区数据
        $neighborhood = Neighborhood::neighborhoodConfig();
        return $this->jsonReturn(1,['neighborhood'=>$neighborhood]);
    }

    public function getSetting()
    {
        return view('setting');
    }

    /*支付订单*/
    public function anyOrderPay()
    {

        /*应该带一个戳，避免重复支付*/

        $orderId = Request::input('order_id');
        $order = Order::find($orderId);
        if(!$order) {
            dd('订单存在');
        }

        if ($order->user_id != Auth::id()) {
            dd('无权支付');
        }

        $user = User::find(Auth::id());

        if(!$order->needPay()) {
            return $this->jsonReturn(0,'请勿重复支付');
        }

        $payType = Request::input('pay_type');

        //余额支付
        if( $payType == Order::PAY_lMS ) {
            if ( $order->need_pay > $user->charge ) {
                return $this->jsonReturn(0,'余额不足');
            }

            //开始扣钱了哟，更新余额
            $user->charge = $user->charge - $order->need_pay;
            $user->save();


            //余额支付
            $cashStream = new CashStream();
            $cashStream->refer_id = $order->id;
            $cashStream->pay_status = 1;
            $cashStream->cash_type = CashStream::CASH_TYPE_LMS_PAY_BUY_PRODUCT;
            $cashStream->user_id = Auth::id();
            $cashStream->price = $order->need_pay;
            $cashStream->compare_price = $user->charge;
            $cashStream->save();

            //更改订单信息
            $order->pay_status = 1;
            $order->order_status = ($order->deliver_type == Deliver::SELF_GET)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
            $order->pay_type = CashStream::CASH_PAY_TYPE_LMS;
            $order->pay_time = date('Y-m-d H:i:s');
            $order->save();

            //生成邀请码，如果是复购的话没有邀请码

            $order = Order::find($order->id);
            $order->paySuccess();

//            if( $order->buy_type == 1 ) {
//                InvitedCodes::makeRecord($order->id);
//            }
//
//            //生成开发辅导初始记录
//            $order->benefitInit();

            $cashStream = new CashStream();
            $cashStream->refer_id = $order->id;
            $cashStream->pay_status = 1;
            $cashStream->cash_type = CashStream::CASH_TYPE_LMS_BUY_PRODUCT;
            $cashStream->user_id = Auth::id();
            $cashStream->price = $order->need_pay;
            $cashStream->save();
            Message::addReport($order->id);

            return $this->jsonReturn(1);
        }

        if( $payType == Order::PAY_ALIPAY )
        {
            require_once base_path() .'/plugin/alipay/wappay/service/AlipayTradeService.php';
            require_once base_path().'/plugin/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';

            require  base_path() .'/plugin/alipay/config.php';

//            var_dump($config);
//            var_dump(config('alipay'));
//            exit;
            $config = config('alipay');

//            var_dump($config);
//            exit;


            //创建支付订单
            $cashStream = new CashStream();
            $cashStream->refer_id = $order->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT;
            $cashStream->user_id = Auth::id();
            $cashStream->price = $order->need_pay;
            $cashStream->save();

            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $cashStream->id;

            //订单名称，必填
            $subject = '辣木膳素食全餐';

            //付款金额，必填
            if( env('PAY_TEST')) {
                $total_amount = 0.01;
            } else {
                $total_amount = $cashStream->price;
            }

            //商品描述，可空
            $body = '';

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new \AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            return ;

        }

        if( $payType == Order::PAY_WECHAT )
        {
            require_once base_path() . "/plugin/wechatpay/lib/WxPay.Api.php";
            require_once base_path() . "/plugin/wechatpay/example/WxPay.JsApiPay.php";


            $tools = null;
            $openid = "";
            if( Kit::isWechat() )
            {
                //
                $openid = \WechatCallback::getOpenidInThisUrlDealWithError(function () {
                    App::abort('404');
                });
                $tools = new \JsApiPay();
            }

            //创建支付订单
            $cashStream = new CashStream();
            $cashStream->refer_id = $order->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT;
            $cashStream->user_id = Auth::id();
            $cashStream->price = $order->need_pay;
            $cashStream->save();

            //付款金额，必填
            if( env('PAY_TEST')) {
                $total_amount = 1;
            } else {
                $total_amount = $cashStream->price * 100;
            }




            //②、统一下单
            $input = new \WxPayUnifiedOrder();
            $input->SetBody("辣木膳素食全餐");
            $input->SetAttach("辣木膳素食全餐");
            $input->SetOut_trade_no($cashStream->id);//这个订单号是特殊的
            $input->SetTotal_fee($total_amount); //钱是以分计的
            $input->SetTime_start(date("YmdHis"));
            $input->SetGoods_tag("辣木膳素食全餐");
            $input->SetNotify_url(env('WECHAT_NOTIFY_URL'));
//            $input->SetOpenid($openId);






            if( Kit::isWechat() )
            {
                $input->SetOpenid($openid);
                $order = \WxPayApi::unifiedOrder($input);
                $jsApiParameters = $tools->GetJsApiParameters($order);
                return view('pay')->with('jsApiParameters',$jsApiParameters);
//                return $this->jsonReturn(1,'/finance/pay?data=' . urlencode(json_encode($jsApiParameters)) );
            } else {
                $input->SetTrade_type("MWEB");
                $wxorder = \WxPayApi::unifiedOrder($input);
                return $this->jsonReturn(1,['mweb_url'=>$wxorder['mweb_url'] . '&redirect_url=' . urlencode(env('WECHAT_RETURN_URL') . '?order_id=' . $order->id )]);
            }

        }
    }

    public function getAddModAddress()
    {
        $this->validate(Request::all(),[
            'address_id'=>'exists:user_address,address_id',
        ]);
        $addressId = Request::input('address_id');
        $address = null;
        if($addressId) {
            //修改
            $address = UserAddress::where('address_id',$addressId)->first();
            if($address->user_id != Auth::id() || !$address->status) {
                dd('无效地址');
            }
        }
        return view('add_mod_address')->with('address',$address);
    }

    public function postAddModAddress()
    {
        $this->validate(Request::all(),[
            'address_id'=>'exists:user_address,address_id',
            'real_name'=>'required',
            'phone'=>'required',
            'neighborhood'=>'required',
            'address'=>'required'
        ]);

        $addressId = Request::input('address_id');
        if($addressId) {
            //修改
            $address = UserAddress::where('address_id',$addressId)->first();
            if($address->user_id != Auth::id() || !$address->status) {
                return $this->jsonReturn(0,'无效地址');
            }

            $address->address_name = Request::input('real_name');
            $address->mobile = Request::input('phone');
            $address->pct_code = Request::input('neighborhood');
            $address->pct_code_name = Neighborhood::getColumnValueById('neighborhood_name',$address->pct_code);;
            $address->address = Request::input('address');
            $address->is_default = Request::input('is_default');
            $address->save();
        } else {
            //新增
            $address = new UserAddress();
            $address->user_id = Auth::id();
            $address->address_name = Request::input('real_name');
            $address->mobile = Request::input('phone');
            $address->pct_code = Request::input('neighborhood');
            $address->pct_code_name = Neighborhood::getColumnValueById('neighborhood_name',$address->pct_code);
            $address->address = Request::input('address');
            $address->is_default = Request::input('is_default');
            $address->save();
        }

        if($address->is_default)
        {
            UserAddress::where('user_id',Auth::id())->where('is_default',1)->whereNotIn('address_id',[$address->address_id])->update(['is_default'=>0]);
        }

        //是否设置为默认


        return $this->jsonReturn(1,$address->address_id);
    }

    public function postDeleteAddress(){
        $this->validate(Request::all(),[
            'address_id'=>'required|exists:user_address,address_id',
        ]);

        $addressId = Request::input('address_id');
        if($addressId) {
            //修改
            $address = UserAddress::where('address_id',$addressId)->first();
            if($address->user_id != Auth::id() || !$address->status) {
                return $this->jsonReturn(0,'无效地址');
            }
            $address->delete();
        }
        return $this->jsonReturn(1);
    }

    public function postSetDefaultAddress(){
        $this->validate(Request::all(),[
            'address_id'=>'required|exists:user_address,address_id',
        ]);

        $addressId = Request::input('address_id');
        if($addressId) {
            //修改
            $address = UserAddress::where('address_id',$addressId)->first();
            if($address->user_id != Auth::id() || !$address->status) {
                return $this->jsonReturn(0,'无效地址');
            }
//            $address->save();
            DB::update("update user_address set is_default = case when address_id = {$addressId} then 1 else 0 end where user_id =  " . Auth::id());
        }
        return $this->jsonReturn(1);
    }



    /**
     * 账单
     */
    public function getCheck()
    {
        return view('check');
    }

    /**
     * 搜索账单
     */
    public function getSearchCheck()
    {
        $query = DB::table('cash_stream')->where('user_id',$this->user->id)->where('pay_status',1)->whereIn('cash_type',CashStream::userIncomeOutcomeArr());
        if(Request::input('month'))
        {
            $query->whereRaw('DATE_FORMAT(created_at,"%Y-%m") = "' . Request::input('month') . '"');
        }
        $query->orderBy('id','desc')->selectRaw('*,DATE_FORMAT(created_at,"%Y-%m") as created_month');
        $count = 30;
        if(Request::input('all')) {
            $count = $query->count();
            $count = $count?$count:30;
        }
        $res = $query->paginate();
        foreach ($res as $key=>$val) {
            $res[$key]->price_with_char = (($val->direction == 1)?"+":"-") . "￥" . $val->price;
            $res[$key]->cash_type_text = CashStream::cashTypeText($val->cash_type);
            $res[$key]->created_at_text = Kit::dateFormat2($val->created_at);
        }
        return $res;
    }







    public function getWithdrawSuccess()
    {
        return view('withdraw_success');
    }

    public function getFillUserInfo()
    {
        return view('fill_user_info');
    }

    public function postFillUserInfo()
    {
        $user = User::getCurrentUser();
        $user->real_name = Request::input('real_name');
        $user->id_card = Request::input('id_card');
        $user->save();
        return $this->jsonReturn(1);
    }

    public function getGoodDetail()
    {
        return view('good_detail')->with('product',Product::getDefaultProduct());
    }

    public function getGoodDetailXcx()
    {
        $product = Product::find(Request::input('product_id'));
        if($product->isCleanProduct())
        {
            return view('good_detail_clean')->with('product',$product);
        } else
        {
            $foodTime = new FoodTime();
            $thisWeek =FoodMenu::where('product_id',$product->id)->whereIn('date', $foodTime->thisWeekList())->orderBy('date','asc')->orderBy('type','asc')->first();
            $nextWeek = FoodMenu::where('product_id',$product->id)->whereIn('date', $foodTime->lastWeekList())->orderBy('date','asc')->orderBy('type','asc')->first();
            return view('good_detail_food')->with('product',$product)->with('thisWeek',$thisWeek)->with('nextWeek',$nextWeek)->with('thisWeekList',$foodTime->thisWeekList())->with('nextWeekList',$foodTime->lastWeekList());
        }
    }

    public  function getEssay()
    {
        $essay = Essay::find(Request::input('id'));
        if( !$essay )
        {
            dd('文章不存在');
        }
        return view('essay')->with('essay',$essay);
    }

    public function getInvitedList()
    {
        $invitedList = $this->user->myInvitedCodes();
        return view('invited_list')->with('invited_list',$invitedList);
    }

    public function getInfo()
    {
        return view('info')->with('user',$this->user);
    }

    public function anyUserOrder()
    {
        $list = Order::where('user_id',Auth::id())->leftJoin('products','products.id','=','orders.product_id')->selectRaw('orders.*,products.type')->orderBy('orders.id','desc')->where('service_start_time','>=',date('Y-m-d'))->where('order_status','>',0)->get();
        if( !$list)
        {
            $list = [];
        }

        foreach ( $list as $key=>$item)
        {
            $list[$key]->service_start_time_format = Kit::dateFormat4($item->service_start_time);
            if( $item->type != 1)
            {
                $count = SubFoodOrders::where('order_id',$item->id)->where('date','>=',date('Y-m-d'))->where('type',1)->count();
                $list[$key]->days_count =  $count;
            }
        }

        return $this->jsonReturn(1,$list);
    }


    public function anyUserOrder2()
    {
        $list = Order::where('user_id',Auth::id())->leftJoin('products','products.id','=','orders.product_id')->selectRaw('orders.*,products.type')->orderBy('orders.id','desc')->where('status','>',0)->get();
        if( !$list)
        {
            $list = [];
        }

        foreach ( $list as $key=>$item)
        {
            $list[$key]->service_start_time_format = Kit::dateFormat4($item->service_start_time);
            if( $item->type != 1)
            {
                $count = SubFoodOrders::where('order_id',$item->id)->where('date','>=',date('Y-m-d'))->count();
                $list[$key]->days_count =  $count;
            } else
            {
                if( $item->service_start_time >= date('Y-m-d'))
                {
                    $list[$key]->days_count =  1;
                } else
                {
                    $list[$key]->days_count =  0;
                }
            }
        }

        return $this->jsonReturn(1,$list);
    }

    public function anyUserCenter()
    {

        $user = User::find(Auth::id());
        //获得用户代金券数量
        $count = Coupon::where('user_id',Auth::id())->where('status',1)->where('expire_at','>=',date('Y-m-d'))->orderBy('expire_at','asc')->count();

        return $this->jsonReturn(1,['user'=>$user,'is_vip'=>$user->isVip(),'couponCount'=>$count]);
    }

    /**
     * 设置头像
     */
    public function getHeaderImg()
    {
        return view('header_img');
    }

    public function postHeaderImg()
    {
        $files = \Illuminate\Support\Facades\Request::file('images');
        $count = count($files);

        if( $count != 1)
        {
            return json_encode(["status"=>0,"desc"=>"文件个数异常"],JSON_UNESCAPED_UNICODE);
        }

        $imagesInfo = [];
        foreach( $files as $key=>$file )
        {
            $imageExtension = $file->getClientOriginalExtension(); //上传文件的后缀
            if(!in_array($imageExtension,['jpg','png','gif','jpeg'])){
                return json_encode(['status'=>0,'desc'=>'文件格式异常'],JSON_UNESCAPED_UNICODE);
            }
            $imagesInfo[] = $imageSaveName =  bin2hex(base64_encode( time() . $key)) . '.' . $imageExtension; //文件保存的名字
        }

        $res = [];
        $result = false;
        foreach($files as $key=>$file)
        {
            if ( $file->move('imgsys',$imagesInfo[$key] ) ){
                $result = true;
                $res[] = '/imgsys/' . $imagesInfo[$key];
            } else {
                $result = false;
                break;
            }
        }

        if($result) {
            $this->user->header_img = $res[0];
            $this->user->save();
            //写入数据库
            return json_encode(['status'=>1,'data'=>$res]);
        }else {
            return json_encode(['status'=>0,'desc'=>"上传异常"],JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 修改绑定手机号
     */
    public function getModifyPhone()
    {
        return view('modify_phone');
    }

    public function postModifyPhone()
    {
        if ( Session::get('modify_phone_sms_code') !=  (Request::input('phone') . '_' . Request::input('sms_code')) )
        {
            return $this->jsonReturn(0,'验证码错误');
        }

        $user = User::where('phone',Request::input('phone'))->first();

        if( $user )
        {
            return $this->jsonReturn(0,'手机号已被使用');
        }

        $user = User::getCurrentUser();
        $user->phone = Request::input('phone');
        $user->save();

        return $this->jsonReturn(1);

    }

    public function anyModifyPhoneSms()
    {
        $this->validate(Request::all(),[
            'phone'=>'required',
        ]);


        $code = DealString::random(6,'number');


        $smsTemplate = new SmsTemplate(SmsTemplate::MODIFY_PHONE_SMS);


        $smsTemplate->sendSms(Request::input('phone'),['code'=>$code]);
        Session::put('modify_phone_sms_code',(Request::input('phone') . '_' . $code));
        return $this->jsonReturn(1);
    }


    /**
     * 我的服务
     */
    public function anyMyServices()
    {
        return view('my_service')->with('financeClass',FinanceClass::getDefaultTakePart());
    }


    public function anyBookFinance()
    {
        return view('book_finance');
    }

    public function anyServiceSegment()
    {
        $type = Request::input('type');

        if ( $type == 'clean') {
            $list = Order::where('user_id', Auth::id())->where('order_status', '>', 0)->where('buy_type',1)->orderBy('id','desc')->get();
            return view('segments.clean_segment')->with('list', $list);
        } else if ($type == 'food')
        {
            $list = Order::where('user_id', Auth::id())->where('order_status', '>', 0)->where('buy_type',100)->orderBy('id','desc')->get();
            return view('segments.food_segment')->with('list', $list);
        } else if ( $type == 'finance' )
        {
//            $list = Order::where('user_id', Auth::id())->where('order_status', '>', 0)->get();
            $list = FinanceUser::where('user_id',Auth::id())->leftJoin('finance_class','finance_user.finance_id','=','finance_class.id')->orderBy('finance_user.id','desc')->get();
            return view('segments.finance_segment')->with('list', $list);
        } else if ( $type == 'health')
        {
            $list = Order::where('user_id', Auth::id())->where('order_status', '>', 0)->orderBy('id','desc')->get();
            return view('segments.clean_segment')->with('list', $list);
        }
    }


    /**
     *购买VIP
     */
    public function anyBuyVip()
    {
        $user = User::getCurrentUser();
        $month = (Request::input('type') == 1)?6:12;

        if( $expireDay = $user->vipExpireDay() )
        {
            $expireMonth = Carbon::parse(Carbon::parse($expireDay)->format('Y-m'));
            //增加月份
            $expireMonth->addMonths($month);
            //把日期算上去？
            $expireMonth->addDays( ($user->pay_day < $expireMonth->daysInMonth)?($user->pay_day - 1):($expireMonth->daysInMonth - 1));

        } else
        {
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

        }
        $user->expire_time= $expireMonth->format('Y-m-d');

        $user->save();
        return $this->jsonReturn(1);
    }


    /**
     * 会员详情页面信息
     */
    public function anyVipPageInfo()
    {
        $user = User::getCurrentUser();
        $expireDay = $user->vipExpireDay();
        $data['user'] = $user->toArray();
        $data['isVip'] = $expireDay?true:false;
        $data['expireDay'] = $expireDay;
        $data['serviceInfo'] = [
            "vip_food"=>YlConfig::value('vip_food'),
            "vip_clean"=>YlConfig::value('vip_clean'),
            "vip_finance"=>YlConfig::value('vip_finance'),
            "vip_health"=>YlConfig::value('vip_health')
            ];


       //如果是会员的话,则要显示相应的信息
        if ( $data['isVip'] )
        {
            //拿最近的会员支付订单
            $vipOrder = VipOrder::where('user_id',$user->id)->where('pay_status',1)->orderBy('id','desc')->first();


            $data['vip_type_text'] =  $vipOrder->vipName();



            //点餐服务
            $foodTotal = Coupon::where('refer_id',$vipOrder->id)->whereIn('coupon_type',[4,5,6])->count();
            $foodActive = Coupon::where('refer_id',$vipOrder->id)->whereIn('coupon_type',[4,5,6])->where('status',1)->count();
            $data['foodTotal'] = $foodTotal;
            $data['foodActive'] = $foodActive;

            //家庭清洁
            $cleanTotal = Coupon::where('refer_id',$vipOrder->id)->whereIn('coupon_type',[1,2,3])->count();
            $cleanActive = Coupon::where('refer_id',$vipOrder->id)->whereIn('coupon_type',[1,2,3])->where('status',1)->count();
            $data['cleanTotal'] = $cleanTotal;
            $data['cleanActive'] = $cleanActive;


            $count = Book::where('user_id',$user->id)->where('refer_id',$vipOrder->id)->count();

            $data['healthTotal'] = $user->health_count;
            $data['healthActive'] = $user->health_count - $count;


//            $data['foodId'] = in_array($vipOrder->buy_type,[1,2])?4:5;
            //理财咨询

            //健康体检
        }

        return $this->jsonReturn(1,$data);
    }


    /**
     * 报名参加讲座
     */
    public function anyTakePartInFinance()
    {
        $finance = FinanceClass::find(Request::input('finance_id'));
        $user = User::getCurrentUser();

        //判断是否重复预约
        if( FinanceUser::where('user_id',$user->id)->where('finance_id',$finance->id)->count() )
        {
            return $this->jsonReturn(0,'请勿重复报名');
        }


        $financeUser = new FinanceUser();
        $financeUser->finance_id = $finance->id;
        $financeUser->user_id = $user->id;
        $financeUser->save();

        return $this->jsonReturn(1);
    }

    public function anyCleanOrFoodOrderDetail()
    {
        $order = Order::find(Request::input('id'));
        $product = Product::find($order->product_id);
        if ( $product->isCleanProduct() )
        {
            return view('clean_detail')->with('product',$product)->with('order',$order);
        } else
        {
            $foodTime = new FoodTime();
            $cWeek = FoodMenu::where('product_id',$product->id)->whereIn('date',$foodTime->menuTimeList(1))->get();
            $lWeek = FoodMenu::where('product_id',$product->id)->whereIn('date',$foodTime->menuTimeList(2))->get();;
            return view('food_detail')->with('product',$product)->with('order',$order)->with('cWeek',$cWeek)->with('lWeek',$lWeek);
        }
    }

    public function anyBindmore()
    {
        return view('bindmore');
    }


    /**
     * 红包
     */
    public function anyBonus()
    {
        $list = DB::table('bonuses')->where('user_id',Auth::id())->orderBy('id','desc')->get();
        if(!$list)
        {
            $list = [];
        }

        return $this->jsonReturn(1,$list);
    }


    /**
     * 延后逻辑处理
     */
    public function anyYanHou()
    {
        $order = Order::find(Request::input('order_id'));
        $foodTime = new FoodTime();
        $nextDate = $foodTime->nextDay();

        $subFoodOrders = SubFoodOrders::where('order_id',$order->id)->where('date',$nextDate)->first();

        if( !($subFoodOrders instanceof  SubFoodOrders))
        {
            return $this->jsonReturn(0);
        }

        $subFoodOrders->status = 100; //已延后
        $subFoodOrders->save();

        //
        $count = $order->quantity;

        for($i = 0;$i < $count;$i++)
        {
            $coupon = new Coupon();
            $coupon->coupon_type = $order->product_id;
            $coupon->expire_at = Carbon::now()->addDays(180)->format('Y-m-d');
            $coupon->type_text = $order->product_name;
            $coupon->user_id = $order->user_id;
            $coupon->status = 1;
            $coupon->save();
        }

        return $this->jsonReturn(1);

        //补偿优惠券
    }


    /**
     * 获得优惠券列表
     */
    public function anyCouponList()
    {
        $list = Coupon::where('user_id',Auth::id())->where('status',1)->where('expire_at','>=',date('Y-m-d'))->orderBy('expire_at','asc')->get();
        return $this->jsonReturn(1,$list);
    }

    /**
     * 更新列表
     */
    public function anyUpdateHabit()
    {
        $ids = Request::input('ids');

        DB::delete("delete from user_habit where id in (".$ids .")");


        $myHabit = UserHabit::where('user_id',Request::input('user_id'))->get();
        return $this->jsonReturn(1,$myHabit->toArray());

    }

    public function anyUpdateHabit2()
    {
        $ids = Request::input('ids');

        DB::delete("delete from user_habit2 where id in (".$ids .")");


        $myHabit = UserHabit2::where('user_id',Request::input('user_id'))->get();
        return $this->jsonReturn(1,$myHabit->toArray());

    }

    /**
     * 兑换邀请码
     */
    public function anyChangeInvited()
    {
        $invitedCode = Request::input('invited_code');

        $randomGet = RandomGet::where('code',$invitedCode)->first();
        if( !$randomGet instanceof  RandomGet )
        {
            return $this->jsonReturn(0,'兑换码不存在');
        }

        if ( $randomGet->status != 1 )
        {
            return $this->jsonReturn(0,'该兑换码已被使用');
        }


        $product = Product::find($randomGet->product_id);

        for($i = 0; $i < $randomGet->quantity; $i++)
        {
            $coupon = new Coupon();
            $coupon->user_id = Auth::id();
            $coupon->coupon_type = $randomGet->product_id;
            $coupon->status = 1;
            $coupon->type_text = $product->product_name;
            $coupon->price = $product->price;
            $coupon->expire_at = Carbon::now()->addDays(14)->format('Y-m-d');
            $coupon->refer_code = $randomGet->code;
            $coupon->save();
        }

        $randomGet->status = 2;
        $randomGet->user_id = Auth::id();
        $randomGet->save();

        return $this->jsonReturn(1);
    }


    public function anyNameage()
    {
        $user = User::find(Auth::id());
        $user->age = Request::input('age');
        $user->real_name = Request::input('name');
        $user->save();
        return $this->jsonReturn(1);
    }


//    public function anyUserHabit()
//    {
//        UserHabit::where('user_id',Auth::id())
//    }
}