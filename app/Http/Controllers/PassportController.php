<?php

namespace App\Http\Controllers;

use App\Log\Facades\Logger;
use App\Model\Banner;
use App\Model\CashStream;
use App\Model\Coupon;
use App\Model\Essay;
use App\Model\FoodMenu;
use App\Model\InvitedCodes;
use App\Model\Order;
use App\Model\Period;
use App\Model\Product;
use App\Model\UserAddress;
use App\Model\UserHabit;
use App\Model\UserHabit2;
use App\Model\YlConfig;
use App\Util\AdminAuth;
use App\Util\Curl;
use App\Util\DownloadExcel;
use App\Util\FoodTime;
use App\Util\Kit;
use App\Util\SmsTemplate;
use App\User;
use App\Util\DealString;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class PassportController extends Controller
{

    public function getRegister()
    {
        return view('register');
    }

    /**
     * 注册用的验证码
     */
    public function postRegisterSms()
    {
        $this->validate(Request::all(),[
            'phone'=>'required',
        ]);


        $code = DealString::random(4,'number');

        if( Request::input('password') )
        {
            $smsTemplate = new SmsTemplate(SmsTemplate::PASSWORD_SMS);
        } else {
            $smsTemplate = new SmsTemplate(SmsTemplate::REGISTER_SMS);
        }

        $smsTemplate->sendSms(Request::input('phone'),['code'=>$code]);

        if(Request::input('storage_type') == 'cache') {
            $expiresAt = Carbon::now()->addMinutes(30);
            Cache::put('register_sms_code' . Request::input('phone'), (Request::input('phone') . '_' . $code),$expiresAt);
        } else {
            Session::put('register_sms_code', (Request::input('phone') . '_' . $code));
        }
        return $this->jsonReturn(1);
    }


    public function postRegister()
    {
        $this->validate(Request::all(), [
            'phone' => 'required',
//            'password'=>'required',
//            'openid'=>'required',
            'register_sms_code' => 'required'
        ]);


        if (Cache::get('register_sms_code' . Request::input('phone')) != (Request::input('phone') . '_' . Request::input('register_sms_code'))) {
            return $this->jsonReturn(0, '验证码错误');
        }

        $user = \App\Model\User::where('phone', Request::input('phone'))->first();
        if( $user instanceof  \App\Model\User)
        {
            return $this->jsonReturn(1,['userId'=>$user->id]);
        }else {
            $user = new \App\Model\User();
            $user->phone = Request::input('phone');
            $user->save();

            return $this->jsonReturn(1,['userId'=>$user->id]);
        }
    }

    public function getLogin()
    {
        return view('login');
    }

    public function postLogin()
    {
        if(Auth::attempt(['phone' => Request::input('phone'), 'password' => Request::input('password')])){
           return $this->jsonReturn(1);
        }
        else{

            //尝试用用户ID登录
            if(Auth::attempt(['id' => Request::input('phone'), 'password' => Request::input('password')]))
            {
                return $this->jsonReturn(1);
            }

            return $this->jsonReturn(0,"用户名密码错误");
        }
    }

    public function anyLoginOut()
    {
        Session::flush();
        return Redirect::to('/passport/login');
    }

    public function getRetrievePassword()
    {
        return view('retrieve_password');
    }

    public function postRetrievePassword()
    {
        $this->validate(Request::all(),[
            'phone'=>'required',
            'password'=>'required',
            'register_sms_code'=>'required'
        ]);


        if ( Session::get('register_sms_code') !=  (Request::input('phone') . '_' . Request::input('register_sms_code')) )
        {
            return $this->jsonReturn(0,'验证码错误');
        }

        $user = \App\Model\User::where('phone',Request::input('phone'))->first();
        if( !$user )
        {
            return $this->jsonReturn(0,'用户不存在');
        }

        $user->password = Hash::make(Request::input('password'));
        $user->save();

        return $this->jsonReturn(1);
    }

    public function getFillUserInfo()
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
        return view('fill_user_info')->with('address',$address);
    }

    public function getAdminLoginOut()
    {
        Session::flush();
        return Redirect('/passport/admin-login');
    }

    public function getAdminLogin()
    {
        return view('admin.login');
    }


    public function postAdminLogin()
    {
        $res = AdminAuth::attempt(Request::all());
        return $res;
    }

    public function getTest()
    {
//        echo Carbon::parse('next Friday')->toDateTimeString();
//        echo date('Y-m-d H:i:s',strtotime('+30 days',strtotime(date('Y-m-d'))));
        $foodTime = new FoodTime();
        var_dump($foodTime->startTimeList());
    }






    public function anyTestSms()
    {
        //

//        var_dump(app_path('plugin/aliyun-dysms-php-sdk/api_demo/SmsDemo.php'));
//        exit;

        require_once  base_path('plugin/aliyun-dysms-php-sdk/api_demo/SmsDemo.php');

        $response = \SmsDemo::sendSms();
        echo "发送短信(sendSms)接口返回的结果:\n";
        print_r($response);
    }

    public function anyShowEssay()
    {
        return view('show_essay')->with('essay',Banner::find(Request::input('id')));
    }

    public function anyPdf()
    {
        return view('pdf');
    }

    public function anyWechat()
    {
        $openid = \SmallWechatCallback::getAccessToken();
    }

    public function anyOnLogin()
    {
        $requestUrl = 'https://api.weixin.qq.com/sns/jscode2session?appid='.\SmallWechatCallback::getAppId().'&secret='.\SmallWechatCallback::getAppSecret().'&js_code=' . Request::input('code'). '&grant_type=authorization_code';
        Logger::info('code请求:' . $requestUrl,'xcx');
        $response = file_get_contents($requestUrl);
        Logger::info($response,'xcx');

        //TODO:去创建openid用户哟
        $response = json_decode($response);
        if( !isset($response->opneid) )
        {
            $this->jsonReturn(0);
        }

        $user = \App\Model\User::where('openid',$response->openid)->first();
        if( !($user instanceof  \App\Model\User) )
        {
            $user = new \App\Model\User();
            $user->openid = $response->openid;
            $user->save();
        }

        return $this->jsonReturn(1);
    }

    public function getOpenid()
    {
        $requestUrl = 'https://api.weixin.qq.com/sns/jscode2session?appid='.\SmallWechatCallback::getAppId().'&secret='.\SmallWechatCallback::getAppSecret().'&js_code=' . Request::input('code'). '&grant_type=authorization_code';
        Logger::info('code请求:' . $requestUrl,'xcx');
        $response = file_get_contents($requestUrl);
        Logger::info($response,'xcx');

        $response = json_decode($response);
        if( !isset($response->openid) )
        {
            return $this->jsonReturn(0);
        }

        $data = [];
        $data['openid'] = $response->openid;
        $data['user'] = \App\Model\User::where('openid',$response->openid)->first();

        if( $data['user'] )
        {
            $data['order'] = Order::where('user_id',$data['user']->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();
        }

        return $this->jsonReturn(1,$data);
    }

    public function anyQRPrimary()
    {
//        https://api.weixin.qq.com/wxa/getwxacode?access_token=ACCESS_TOKEN
        $token = \SmallWechatCallback::getAccessToken();
        $requestURI = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=' . $token . '';
        $data = ['path'=>'pages/health/route','auto_color'=>'false'];
        $response = Curl::curlPostRaw($requestURI,json_encode($data),false,'origin');
        var_dump($response);
        file_put_contents(public_path('xcx/primary.jpg'),$response);
    }


    public function anyQRCode()
    {
        $token = \SmallWechatCallback::getAccessToken();
        $requestURI = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $token . '';
        $data = ['scene'=>'53','page'=>'pages/index/index'];
        $response = Curl::curlPostRaw($requestURI,json_encode($data),false,'origin');
//        var_dump($response);
        file_put_contents(public_path('xcx/1.jpg'),$response);
    }



    /**
     * 身份证号码转换性别
     */
    public  function getIdToSex($id_card)
    {
        if(!isset($id_card[16]))
        {
            return '';
        }
        return $id_card[16]%2 == 1?'男':'女';
    }


    public function anyProductList()
    {
        $products = Product::where('type',Request::input('type'))->selectRaw('id, concat("'.env('IMAGE_HOST').'",cover_image) as cover_image,product_name,sub_desc')->where('status',1)->get();
        return $this->jsonReturn(1,$products->toJson());
    }


    public function anyConfigDetail()
    {
        $val = json_decode(YlConfig::value(Request::input('config_name')));
        return view('show_product_detail')->with('content',isset($val->content)?$val->content:'');
    }


    public function anyProductInfo()
    {

        //获得用户默认地址哟
//        $user =  User (Request::input('openid'));
        $user = \App\Model\User::find(Request::input('openid'));
        $userAddress = UserAddress::mineAddressList($user->id);

        $product = Product::find(Request::input('id'));
        $attrs = $product->getAttrs();


        $arr = [];
        foreach ( $attrs as $key=>$item )
        {
            $kv = ['size'=>$item->size,'price'=>$item->price,'attr_id'=>$item->id];
//            var_dump($kv);
            $ids = array_pluck($arr,'neighborhood_id');
            $ind = array_search($item->neighborhood_id,$ids);
            if( $ind === false )
            {
                //没有新增
                $arr[] = ["neighborhood_id"=>$item->neighborhood_id,"kv"=>[]];
                $ind = count($arr) - 1;
            }
            $arr[$ind]['kv'][] = $kv;
        }

        $timeArray = json_decode(YlConfig::value('clean_service_time'));
        $foodTime = new FoodTime();


        //
        $periodPrice = [];
        if( !$product->isCleanProduct() ) {
            foreach ($attrs as $key => $item) {
                $periodPrice[] = ["attr_id"=>$item->id,"period_id"=>$item->period_id,"period_name"=>Period::periodName($item->period_id),"price"=>$item->price];
            }
        }


        //用户的优惠券信息
        $coupon = Coupon::where('user_id',$user->id)->where('status',1)->where('expire_at','>=',date('Y-m-d'))->where('coupon_type',$product->id)->orderBy('expire_at','asc')->get();
        if(isset($coupon[0]))
        {
            $couponId =$coupon[0]->id;
        }else{
            $couponId = '';
        }


        $foodTime = new FoodTime();
        $timeList = $foodTime->startTimeList();

        $cleanTime = [];
        foreach ( $timeList as $item )
        {
            $cleanTime[] = Kit::dateFormat5($item);
        }

        return $this->jsonReturn(1,['arr'=>$arr,'timeArr'=>$timeArray,'lunchArr'=>json_decode(YlConfig::value('lunch_service_time')),'dinnerArr'=>json_decode(YlConfig::value('dinner_service_time')),'start_deliver_day'=>$foodTime->startTimeList(),'periodPrice'=>$periodPrice,'userAddress'=>$userAddress,'product'=>$product->toArray(),'couponId'=>$couponId,'coupons'=>$coupon->toArray(),'cleanTime'=>$cleanTime,'hourTime'=>FoodTime::hoursList(),'minTime'=>FoodTime::minList()]);
    }


    public  function getBannerDetail()
    {
        $banner = Banner::find(Request::input('id'));
        if( !$banner )
        {
            dd('文章不存在');
        }
        return view('essay')->with('essay',$banner);
    }

    public function anyHabbitRemark()
    {

        $product = Product::find(Request::input('product_id'));

        if ( $product->isCleanProduct() )
        {
            $myHabit = UserHabit2::where('user_id',Request::input('user_id'))->get();
            return view('habbit_remark2')->with('habit',$myHabit->toArray());
        } else {
            $myHabit = UserHabit::where('user_id',Request::input('user_id'))->get();
            return view('habbit_remark')->with('habit',$myHabit->toArray());
        }

    }

    public function anyHabbitRemark2()
    {
        return view('habbit_remark2');
    }


    public function anyPaySuccess()
    {
        $user = \App\Model\User::find(Request::input('openid'));
        $order = Order::where('user_id',$user->id)->orderBy('id','desc')->first();
        return view('pay_success')->with('order',$order);
    }


    public function anyRandom()
    {
        //869436034388526
        set_time_limit(600);
        $total = Request::input('total',100);
        $arr = [];
        for ($i = 0; $i < $total; $i++ )
        {
//            $newRandom = 8694360  + rand(10000000,99999999);
            $whileFlag = true;
            while($whileFlag)
            {
                $newRandom = 8694360  . rand(10000000,99999999);
                if(in_array($newRandom,$arr))
                {
                    continue;
                } else {
                    $arr[] = $newRandom;
                    $whileFlag =false;
                }
            }
        }


        $dataList = [];
        foreach ($arr as $key=>$item)
        {
            $dataList[] = [$item];
        }



        $data = array(
            'title' => array('ids'),
            'data' => $dataList,
            'name' => 'tixian',
        );
        DownloadExcel::publicDownloadExcel($data);
    }

    public function anyDecodeInfo()
    {
        require_once base_path() .'/plugin/swechatpay/mp/wxBizDataCrypt.php';

        $appid = env('SMALL_APPID');
        $sessionKey = Request::input('sessionKey');
        $encryptedData=Request::input('encryptedData');
        $iv = Request::input('iv');;

        $pc = new \WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode == 0) {
            return $this->jsonReturn(1,$data);
        } else {
            return $this->jsonReturn(0,$errCode);
        }
    }

    public function getTest1()
    {
        return view('withdraw_success');
    }

    /**
     *
     */
    public function anyMenu()
    {
       $product = Product::find(Request::input('product_id'));
       $dates = Request::input('dates');
       $dates = explode(',',$dates);


        $res = [];
        foreach ( $dates as $date)
        {
            $foods = FoodMenu::where('product_id',$product->id)->where('date',$date)->get();

            $lunch = (Object)['cover_img'=>''];
            $dinner = (Object)['cover_img'=>''];

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

       return view('food_menu')->with('dates',$dates)->with('res',$res);
    }


    /**
     * 订餐订单历史记录
     */
    public function getHistory()
    {
        $dates = Request::input('dates');
        $dates = explode(',',$dates);

        $product = Product::find(Request::input('product_id'));

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

        return view('history')->with('dates',$dates)->with('res',$res);
    }


    /**
     * 根据日期获得菜单
     */
    public function getMenuByDates()
    {
        $dates = Request::input('dates');
        $dates = explode(',',$dates);

        $product = Product::find(Request::input('product_id'));

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

        return $this->jsonReturn(1,$res);
    }

    /**
     *
     */
    public function anyCommonQues()
    {
        return view('common_ques')->with('product',Product::find(Request::input('product_id')));
    }
}