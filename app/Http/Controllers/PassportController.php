<?php

namespace App\Http\Controllers;

use App\Log\Facades\Logger;
use App\Model\Banner;
use App\Model\CashStream;
use App\Model\Essay;
use App\Model\InvitedCodes;
use App\Model\Order;
use App\Model\Period;
use App\Model\Product;
use App\Model\UserAddress;
use App\Model\YlConfig;
use App\Util\AdminAuth;
use App\Util\Curl;
use App\Util\DownloadExcel;
use App\Util\FoodTime;
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

    public function anyInitActivityUser()
    {
        set_time_limit(3600);

        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        $PHPExcel = $reader->load(storage_path('sl.xlsx'));
//
//
        $objWorksheet = $PHPExcel->getSheet(2);
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数


//        dd($highestRow);
//        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
//
////        var_dump($highestRow);
//
//        $userArr = [];
//        exit;
//
        for($i = 2; $i <= $highestRow;$i++)
        {
            $arr = [];
//            $arr['id'] = 8000 + $i - 1;
//            $arr['real_name'] = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
//            $arr['phone'] = strval($objWorksheet->getCellByColumnAndRow(1, $i)->getValue());
//            $arr['id_card'] = strval($objWorksheet->getCellByColumnAndRow(2, $i)->getValue());
//            $arr['immediate_id'] = strval($objWorksheet->getCellByColumnAndRow(4, $i)->getValue());
//
//            $arr['password'] = Hash::make('123456');
//            $arr['parent_phone'] = strval($objWorksheet->getCellByColumnAndRow(3, $i)->getValue());;
//            $arr['indirect_phone'] = strval($objWorksheet->getCellByColumnAndRow(5, $i)->getValue());;
//
//            $userArr[] = $arr;
            $phone = strval($objWorksheet->getCellByColumnAndRow(1, $i)->getValue());
            $count = strval($objWorksheet->getCellByColumnAndRow(9, $i)->getValue());
            $user = \App\Model\User::where('id','>',8000)->where('phone',$phone)->first();


            if( $user instanceof  \App\Model\User )
            {
                echo $user->id . '-' . $count . '<br/>';
                $user->get_good = $count;
                $user->save();
            } else
            {
                echo 'User not exist! <br/>';
            }

        }

        exit;

        foreach ( $userArr as $key=>$item)
        {
            $user = \App\Model\User::find($item['id']);

            if( !($user instanceof  \App\Model\User))
            {
                continue;
            }

            $parentId = \App\Model\User::where('id', '>',8000)->where('phone',$item['parent_phone'])->pluck('id');
            $indirectId = \App\Model\User::where('id', '>',8000)->where('phone',$item['indirect_phone'])->pluck('id');

//            echo $user->id . '-' . $parentId . '-' . $indirectId . '<br/>';

            $user->parent_id = $parentId;
            $user->indirect_id = $indirectId;
            $user->save();





//            $newUser = new \App\Model\User();
//            $newUser->id = $item['id'];
//            $newUser->real_name = $item['real_name'];
//            $newUser->phone = $item['phone'];
//            $newUser->id_card = $item['id_card'];
//            $newUser->save();
        }




//        dd($userArr);
//
//        $res = json_encode($userArr);
//        file_put_contents(storage_path('activity.json'),$res);
//        exit;
//
//        var_dump($userArr);

//        $arr = json_decode(file_get_contents(storage_path('activity.json')),true);
//
//        for ($i =0; $i < count($arr); $i++)
//        {
//            $this->makeActivityUser($arr[$i]);
//        }
    }


    private function makeActivityUser($arr)
    {
        var_dump($arr);
        $user = \App\Model\User::where('phone',$arr['phone'])->first();

        if($user instanceof \App\Model\User)
        {
            Logger::info('用户已存在' . $arr['phone'],'cz');

            //判断是否有活动订单
            $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();

            if( $order instanceof  Order )
            {
                Logger::info('用户已存在活动订单' . $arr['phone'],'cz');
                return;
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
        $order = new Order();
        $order->user_id = $user->id;
        $order->buy_type = Order::BUY_TYPE_ACTIVITY;
        $order->need_pay = Product::find(2)->price;
        $order->immediate_user_id = $arr['immediate_id'];

        $order->pay_status = 1;
        $order->order_status = Order::ORDER_STATUS_WAIT_DELIVER;
//        $order->pay_type = CashStream::CASH_PAY_TYPE_WECHAT;
        $order->pay_time = date('Y-m-d H:i:s');
        $order->save();

        Logger::info('下单成功' . $order->id,'cz');


    }


    public function anyInitUser()
    {

        set_time_limit(3600);

        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        $PHPExcel = $reader->load(storage_path('3.xlsx'));

        $objWorksheet = $PHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数

        dd($highestRow);
//        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数

//        var_dump($highestRow);

        $userArr = [];

        for($i = 3; $i <= $highestRow;$i++)
        {
            $arr = [];
            $arr['id'] = intval($objWorksheet->getCellByColumnAndRow(0, $i)->getValue());
            $arr['real_name'] = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
            $arr['phone'] = strval($objWorksheet->getCellByColumnAndRow(3, $i)->getValue());
            $arr['parent_id'] = strval($objWorksheet->getCellByColumnAndRow(5, $i)->getValue());
            $arr['indirect_id'] = strval($objWorksheet->getCellByColumnAndRow(7, $i)->getValue());

            if($arr['real_name'] == '')
            {
                continue;
            }


            if($arr['parent_id'] == '无')
            {
                $arr['parent_id'] = 0;
            }

            if($arr['indirect_id'] == '无')
            {
                $arr['indirect_id'] = 0;
            }

            $arr['password'] = Hash::make('123456');

            $userArr[] = $arr;

        }

//        dd($userArr);

        $res = User::insert($userArr);
        var_dump($res);
        dd(3);

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

    public function anyGoodDetail()
    {
        $product = Product::find(Request::input('product_id'));

        $index = Request::input('index');

        if($index == 0)
        {
            $content = $product->context;
        } else if( $index == 1)
        {
            $content = $product->context_deliver;
        } else {
            $content = $product->context_server;
        }

        return view('show_product_detail')->with('content',$content);
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



    public function getXcxBind()
    {
        return view('xcx_bind');
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


    public function anyVipGuide()
    {
        return view('vip_guide');
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
        $user = \App\Model\User::getUserByOpenid(Request::input('openid'));
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

        return $this->jsonReturn(1,['arr'=>$arr,'timeArr'=>$timeArray,'lunchArr'=>json_decode(YlConfig::value('lunch_service_time')),'dinnerArr'=>json_decode(YlConfig::value('dinner_service_time')),'start_deliver_day'=>$foodTime->startTimeList(),'periodPrice'=>$periodPrice,'userAddress'=>$userAddress,'product'=>$product->toArray()]);
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
        return view('habbit_remark');
    }

    public function anyHabbitRemark2()
    {
        return view('habbit_remark2');
    }


    public function anyPaySuccess()
    {
        return view('pay_success');
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




}