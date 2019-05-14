<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Log\Facades\Logger;
use App\Model\Admin;
use App\Model\Banner;
use App\Model\Book;
use App\Model\CashStream;
use App\Model\Essay;
use App\Model\FinanceClass;
use App\Model\FinanceUser;
use App\Model\FoodMenu;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\MonthGetGood;
use App\Model\Neighborhood;
use App\Model\Order;
use App\Model\Period;
use App\Model\Product;
use App\Model\ProductAttr;
use App\Model\RandomGet;
use App\Model\RandomPool;
use App\Model\ServeUser;
use App\Model\SignRecord;
use App\Model\SubFoodOrders;
use App\Model\SyncModel;
use App\Model\User;
use App\Model\UserAddress;
use App\Util\CommKit;
use App\Util\DownloadExcel;
use App\Util\Kit;
use App\Util\OrderStatical;
use App\Util\SmsTemplate;
use App\Util\TotalStatical;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class IndexController extends Controller
{
    public function getIndex()
    {
        return view('public_index');
    }

    public function getPreview()
    {
        return view('admin.preview');
    }

    public function getBone()
    {
        return view('admin.master');
    }

    /**
     * 概況
     */
    public function getTotal()
    {
        return view('admin.total');
    }

    public function getTotalFinance()
    {
        return view('admin.total_finance');
    }






    public function postSetOrderStatus()
    {
        $orderId = Request::input('order_id');
        $targetStatus = Request::input('status');
        $order = Order::find($orderId);
        if (!$order) {
            return $this->jsonReturn(0, '订单不存在');
        }

        if ($targetStatus == Order::ORDER_STATUS_SELF_GOT && $order->order_status == Order::ORDER_STATUS_WAIT_SELF_GET) {
            $order->order_status = $targetStatus;


        } else if ($targetStatus == Order::ORDER_STATUS_DELIVERED && $order->order_status == Order::ORDER_STATUS_WAIT_DELIVER) {
            $order->order_status = $targetStatus;
            $order->deliver_company_name = Request::input('deliver_company_name');
            $order->deliver_number = Request::input('deliver_number');

            //物流信息
            //发送物流通知短信
            $smsTemplate = new SmsTemplate(SmsTemplate::DELIVER_SMS);
            $smsTemplate->sendSms(User::find($order->user_id)->phone, ['company' => $order->deliver_company_name, 'billno' => $order->deliver_number]);


        } else {
            return $this->jsonReturn(0, '状态异常');
        }

        $order->save();
        return $this->jsonReturn(1);
    }


    public function getAddReport()
    {
        return view('admin.add_report');
    }

    public function getEssays()
    {
        $list = DB::table('essays')->orderBy('sort', 'desc')->get();
        return view('admin.essays')->with('list', $list);
    }

    public function getEditEssay()
    {
        $essay = Banner::find(Request::input('id'));
        if (Request::input('id') && !$essay) {
            dd('不存在');
        }
        return view('admin.edit_essay')->with('essay', $essay);
    }

    public function postEditEssay()
    {
        $essay = Banner::find(Request::input('id'));
        if (!$essay) {
            $essay = new Banner();
            $essay->sort = DB::table('essays')->max('sort') + 1;
        }

        $essay->cover_image = Request::input('cover_image');
        $essay->title = Request::input('title');
        $essay->content = Request::input('content');
        $essay->sub_title = Request::input('sub_title');
        $essay->status = Request::input('status');

        $essay->save();
        return $this->jsonReturn(1);
    }

    public function postModifySortEssay()
    {
        $type = Request::input('direction', 'increase');
        $essay = Banner::find(Request::input('id'));
        if ($type == 'increase') {
            $compareEssay = Banner::where('sort', '>', $essay->sort)->orderBy('sort', 'asc')->first();
        } else {
            $compareEssay = Banner::where('sort', '<', $essay->sort)->orderBy('sort', 'desc')->first();
        }

        if ($compareEssay) {
            $essaySort = $essay->sort;
            $essay->sort = $compareEssay->sort;
            $compareEssay->sort = $essaySort;
        }

        $essay->save();
        return $this->jsonReturn(1);
    }


    public function anyAlbumImage()
    {
        $files = \Illuminate\Support\Facades\Request::file('images');
        $count = count($files);
        if ($count != 1) {
            return json_encode(["status" => 0, "desc" => "文件个数异常"], JSON_UNESCAPED_UNICODE);
        }

        $imagesInfo = [];
        foreach ($files as $key => $file) {
            $imageExtension = $file->getClientOriginalExtension(); //上传文件的后缀
            if (!in_array($imageExtension, ['jpg', 'png', 'gif', 'jpeg'])) {
                return json_encode(['status' => 0, 'desc' => '文件格式异常'], JSON_UNESCAPED_UNICODE);
            }
            $imagesInfo[] = $imageSaveName = bin2hex(base64_encode(time() . $key)) . '.' . $imageExtension; //文件保存的名字
        }

        $res = [];
        $result = false;
        foreach ($files as $key => $file) {
            //$iamgeTempPath = $file->getRealPath(); //临时文件的绝对路径
            if ($file->move('imgsys', $imagesInfo[$key])) {
                $result = true;
                $res[] = '/imgsys/' . $imagesInfo[$key];
            } else {
                $result = false;
                break;
            }

//            $imageFileContent = file_get_contents($iamgeTempPath);

            //上传OSS
//            $oss = \App\Util\OSS\OssCommon::getInstance();
//            $upRes = $oss->uploadFileByContent($imageFileContent,['folder' => '/',
//                'fileName' => $imagesInfo[$key]]);
//
//            if(\App\Util\Kits::checkSuccessTrue($upRes)){
//                $result = true;
//                $res[] = $imagesInfo[$key];
//            }
//            else{
//                $result = false;
//                break;
//            }
        }
        if ($result) {
            return json_encode(['status' => 1, 'data' => $res]);
        } else {
            return json_encode(['status' => 0, 'desc' => "上传异常"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function getGood()
    {
        return view('admin.good')->with('product', Product::getDefaultProduct())->with('addresses', UserAddress::selfGetAddressConfig());
    }

    public function getActivityGood()
    {
        return view('admin.activity_good')->with('product', Product::find(2))->with('addresses', UserAddress::goodSelfGetAddressConfig(2));
    }

    public function postGood()
    {
        $product = Product::find(Request::input('id'));
//        $product->consumer_service_phone = Request::input('consumer_service_phone');
        $product->cover_image = Request::input('cover_image');
        $product->cover_image2 = Request::input('cover_image2');
//        $product->product_name = Request::input('title');
//        $product->context = Request::input('content');
//        $product->context_deliver = Request::input('content_deliver');
//        $product->context_server = Request::input('content_server');
        $product->food_desc = Request::input('food_desc');
        $product->fit_indi = Request::input('fit_indi');
        $product->price = Request::input('price');



        if ( Request::input('context') )
        {
            $product->context = Request::input('context');
        }


        if ( Request::input('context_deliver') )
        {
            $product->context_deliver = Request::input('context_deliver');
        }

        if ( Request::input('context_server') )
        {
            $product->context_server = Request::input('context_server');
        }

        if ( Request::input('common_ques') )
        {
            $product->common_ques = Request::input('common_ques');
        }

        if ( Request::input('sub_desc') )
        {
            $product->sub_desc = Request::input('sub_desc');
        }



        $product->save();
        return $this->jsonReturn(1);
    }


    public function postActivityGood()
    {
        $product = Product::find(2);
        $product->product_name = Request::input('title');
        $product->cover_image = Request::input('cover_image');
        $product->context = Request::input('content');
        $product->price = Request::input('price');
        $product->activity_days = Request::input('activity_days');

        $product->save();
        return $this->jsonReturn(1);
    }

    public function getGoodAttr()
    {
        return view('admin.good_attr')->with('attr', ProductAttr::find(Request::input('id')));
    }

    public function postGoodAttr()
    {
        $this->validate(Request::all(), [
            'attr_id' => 'required|exists:product_attrs,id',
            'attr_des' => 'required',
            'quantity' => 'required|integer',
            'single_price' => 'required|numeric',
            'single_up_price' => 'required|numeric',
            'single_super_price' => 'required|numeric',
            'single_direct_price' => 'required|numeric',
            'single_indirect_price' => 'required|numeric',
            'rebuy_price' => 'required|numeric',
        ]);


        $quantity = 1;

        $productAttr = ProductAttr::find(Request::input('attr_id'));
        $productAttr->attr_des = Request::input('attr_des');
        $productAttr->single_price = Request::input('single_price');
        $productAttr->price = $productAttr->single_price * $quantity;
        $productAttr->single_up_price = Request::input('single_up_price');
        $productAttr->up_price = $productAttr->single_up_price * $quantity;
        $productAttr->single_super_price = Request::input('single_super_price');
        $productAttr->super_price = $productAttr->single_super_price * $quantity;
        $productAttr->single_direct_price = Request::input('single_direct_price');
        $productAttr->direct_price = $productAttr->single_direct_price * $quantity;
        $productAttr->single_indirect_price = Request::input('single_indirect_price');
        $productAttr->indirect_price = $productAttr->single_indirect_price * $quantity;
        $productAttr->rebuy_price = Request::input('rebuy_price');
        $productAttr->rebuy_up_price = Request::input('rebuy_up_price');
        $productAttr->rebuy_super_price = Request::input('rebuy_super_price');

        $productAttr->save();
        return $this->jsonReturn(1);
    }


    public function postDealWithdraw()
    {
        $cashStream = CashStream::find(Request::input('id'));
        if (!$cashStream || $cashStream->withdraw_deal_status) {
            return $this->jsonReturn(0, '申请已处理,请勿重复操作');
        }


        $newCashStream = new CashStream();
        $newCashStream->price = $cashStream->price;
        $newCashStream->pay_status = 1;
        if (Request::input('agree') == 1) {
            //同意取现,系统多一笔支出
            $newCashStream->cash_type = $cashStream->cash_type + 1;
            $newCashStream->withdraw_type = $cashStream->withdraw_type;
            $cashStream->withdraw_deal_status = 1;
        } else {
            //拒绝取现，把钱退回用户
            $newCashStream->user_id = $cashStream->user_id;
            $newCashStream->cash_type = $cashStream->cash_type + 2;
            $newCashStream->withdraw_type = $cashStream->withdraw_type;
            $cashStream->withdraw_deal_status = 2;
        }
        $cashStream->remark = Request::input('remark');
        $cashStream->save();
        $newCashStream->save();
        return $this->jsonReturn(1);
    }


    public function postAddModAddress()
    {
        $this->validate(Request::all(), [
            'address_id' => '',
            'real_name' => 'required',
            'phone' => 'required',
            'city_code' => 'required',
            'address' => 'required'
        ]);

        $addressId = Request::input('address_id');
        if ($addressId) {
            //修改
            $address = UserAddress::where('address_id', $addressId)->first();
            if (!$address->status) {
                return $this->jsonReturn(0, '无效地址');
            }

            $address->address_name = Request::input('real_name');
            $address->mobile = Request::input('phone');
            $address->pct_code = Request::input('city_code');
            $address->pct_code_name = '';
            $address->address = Request::input('address');

            if (Request::input('good_id')) {
                $address->good_id = Request::input('good_id');
            }

            $address->save();
        } else {
            //新增
            $address = new UserAddress();
            $address->user_id = -1;
            $address->address_name = Request::input('real_name');
            $address->mobile = Request::input('phone');
            $address->pct_code = Request::input('city_code');
            $address->pct_code_name = SyncModel::pctnameByCode($address->pct_code);
            $address->address = Request::input('address');

            if (Request::input('good_id')) {
                $address->user_id = 0;
                $address->good_id = Request::input('good_id');
            }

            $address->save();
        }

        return $this->jsonReturn(1, $address->address_id);
    }

    public function getNewOrder()
    {
        return view('admin.new_order');
    }


    public function getDirectIndirectRecordAll()
    {

        //开发支出
        $direct = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_DIRECT])->where('pay_status', '>', 0)->sum('price');
        $directCount = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_DIRECT])->where('pay_status', '>', 0)->count();


        $indirect = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_INDIRECT])->where('pay_status', '>', 0)->sum('price');
        $indirectCount = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_INDIRECT])->where('pay_status', '>', 0)->count();


        $query = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_DIRECT, CashStream::CASH_TYPE_BENEFIT_INDIRECT])->leftJoin('users', 'users.id', '=', 'cash_stream.user_id')->leftJoin('users as b', 'b.id', '=', 'cash_stream.refer_user_id')->where('pay_status', '>', 0)->orderBy('cash_stream.id', 'desc')->selectRaw('cash_stream.*,users.real_name,b.real_name as refer_real_name');

        $query->where(function ($query) {
            if (Request::input('keyword') != '') {
                $query->where('users.real_name', Request::input('keyword'))->orWhere('b.real_name', Request::input('keyword'));
            }
        });

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));

        foreach ($paginate as $key => $val) {
//            $tempUser = User::find($val->refer_user_id);
//            $ownerUser = User::find($val->user_id);
//            if($tempUser) {
//                $paginate[$key]->user_model = $tempUser;
//                $paginate[$key]->owner_user_model = $ownerUser;
//            }else{
//                $paginate[$key]->user_model = (object)[
//                    "real_name"=>"未注册",
//                    "phone"=>"未注册",
//                    "created_at"=>"未注册"
//                ];
//            }
        }

        return view('admin.direct_indirect_record_all')->with('paginate', $paginate)->with('direct', $direct)->with('directCount', $directCount)->with('indirect', $indirect)->with('indirectCount', $indirectCount);
    }


    public function anyModifyUser()
    {
        $this->validate(Request::all(), [
            'user_id' => 'required',
            'new_user_phone' => 'required',
            'new_user_real_name' => 'required',
            'new_user_id_card' => 'required'
        ]);

        $user = User::where('id', Request::input('user_id'))->first();

        if (!($user instanceof User)) {
            return $this->jsonReturn(0, '用户不存在');
        }

        $user->phone = Request::input('new_user_phone');
        $user->real_name = Request::input('new_user_real_name');
        $user->id_card = Request::input('new_user_id_card');

        $user->save();

        return $this->jsonReturn(1);
    }


    public function anyAddAngleUser()
    {

    }

    public function anyOrderDetail()
    {

    }


    /**
     * 退款申请
     */
    public function getActivityTrunback()
    {
        $query = DB::table('cash_stream')->orderBy('cash_stream.id', 'desc')->leftJoin('users', 'user_id', '=', 'users.id')->where('cash_type', CashStream::CASH_TYPE_ACTIVITY_WITHDRAW)->selectRaw('*,cash_stream.created_at as withdraw_created_at,cash_stream.id as withdraw_id');

        CommKit::keywordSearch($query);
        CommKit::equalQuery($query,array_only(Request::all(),['withdraw_deal_status']));
        CommKit::betweenTime($query, 'cash_stream.created_at');
        CommKit::keywordSearch($query);

        if (Request::input('download')) {
            $list = $query->get();
            $dataList = array();
            foreach ($list as $key => $item) {

                $user = User::find($item->user_id);
                $activityOrder = $user->getActivityPayedOrder();

                $tempArray = array($item->withdraw_created_at, $item->real_name, $item->phone, number_format($item->price, 2),$activityOrder->pay_time,\App\Model\CashStream::withdrawTypeText($item->withdraw_type), $item->withdraw_bank, $item->withdraw_account, \App\Model\CashStream::withdrawStatusText($item->withdraw_deal_status),$item->remark);
                array_push($dataList, $tempArray);
            }


            $data = array(
                'title' => array('申请时间', '申请人姓名', '联系方式', '申请金额','报单时间','提现方式', '提现银行', '提现账号', '处理状态','原因备注'),
                'data' => $dataList,
                'name' => 'tixian'
            );
            DownloadExcel::publicDownloadExcel($data);
            return;
        }


        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        $totalTurnback = CashStream::where('cash_type', CashStream::CASH_TYPE_TURNBACK)->sum('price');
        return view('admin.turnback')->with('paginate', $paginate)->with('totalTurnback', $totalTurnback);
    }

    public function anyActivityPay()
    {
        //没有用户则创建用户
        $userPhone = Request::input('new_user_phone');
        $user = User::where('phone', $userPhone)->first();
        if (!($user instanceof User)) {
            //创建用户
            $user = new User();
            $user->phone = $userPhone;
            $user->id_card = Request::input('new_user_real_name');
            $user->real_name = Request::input('new_user_id_card');
            $user->save();
        }

        $order = Order::where('user_id', $user->id)->where('buy_type', Order::BUY_TYPE_ACTIVITY)->first();
        if (!$order instanceof Order) {
            //return $this->jsonReturn(0,'订单无效或不存在');
            //没有订单则创建订单哟
            $order = new Order();
            $order->user_id = $user->id;
            $order->buy_type = Order::BUY_TYPE_ACTIVITY;
            $order->need_pay = Product::find(2)->price;
            $order->quantity = 1;
            $order->save();
        }

        if ($order->pay_status) {
            return $this->jsonReturn(0, '请勿重复支付');
        }

        //上级会员信息，应该上级必须是高级会员才可以哟
        $phone = Request::input('new_user_up_phone');
        if (!$phone) {
            return $this->jsonReturn(0, '上级会员不能为空');
        }

        $immediateUser = User::where('phone', $phone)->first();
        if (!($immediateUser instanceof User)) {
            return $this->jsonReturn(0, '无效的上级会员');
        }

        if ($immediateUser->vip_level != User::LEVEL_MASTER) {
            return $this->jsonReturn(0, '无效的身份等级');
        }


        //保存最新的订单消息
        $order->pay_status = 1;
        $order->activity_by_admin = 1;
        $order->immediate_user_id = $immediateUser->id;
        $order->save();


        return $this->jsonReturn(1);
    }

    public function getTurnbackDetail()
    {
//        $withdraw = CashStream::where('id',Request::input('withdraw_id'))->where('cash_type',CashStream::CASH_TYPE_WITHDRAW)->first();
//        if(!$withdraw) {
//            dd('记录不存在');
//        }
//        return view('admin.withdraw_detail')->with('withdraw',$withdraw)->with('user',User::find($withdraw->user_id));
        $withdraw = CashStream::where('id', Request::input('withdraw_id'))->where('cash_type', CashStream::CASH_TYPE_ACTIVITY_WITHDRAW)->first();
        return view('admin.trunback_detail')->with('withdraw', $withdraw)->with('user', User::find($withdraw->user_id));
    }

    /**
 * 管理员列表
 */
    public function getPower()
    {
        $query = Admin::orderBy('id', 'desc');
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.power')->with('paginate', $paginate);
    }



    /**
     * 管理员列表
     */
    public function getServeMember()
    {
        return view('admin.serve_member');
    }


    public function anyServeMemberList()
    {
        $list = ServeUser::where('status',1)->get();
        for($i =0 ; $i < count($list); $i++ )
        {
            $list[$i]['work_no'] = Kit::workno($list[$i]->id);
        }
        return $this->jsonReturn(1,$list);
    }

    /**
     * 添加管理员
     */
    public function anyAddAdmin()
    {
        if (Admin::where('email', Request::input('email'))->count()) {
            return $this->jsonReturn(0, '用户名已被使用');
        }

        $admin = new Admin();
        $admin->email = Request::input('email');
        $admin->password = Hash::make(Request::input('password'));
        $admin->save();
        return $this->jsonReturn(1);
    }

    public function anyModifyAdmin()
    {
        $admin = Admin::find(Request::input('id'));
        if( !($admin instanceof  Admin) )
        {
            return $this->jsonReturn(0,'用户不存在');
        }

        if( Request::has('status') )
        {
            $admin->is_disable = Request::input('status');
        }

        if( Request::has('email') )
        {
            if( Admin::where('email',Request::input('email'))->whereNotIn('id',[$admin->id])->count()){
                return $this->jsonReturn(0,'用户名已被使用');
            }
            $admin->email = Request::input('email');
        }

        if( Request::input('reset_pwd') )
        {
            $admin->password = Hash::make(Request::input('password'));
        }


        if( Request::has('power_array') )
        {
            $admin->power = Request::input('power_array');
        }



        $admin->save();
        return $this->jsonReturn(1);
    }



    public function anyUpAngelUser()
    {
        $user = User::find(Request::input('user_id'));
        if( !($user instanceof  User) )
        {
            return $this->jsonReturn(0,'用户不存在');
        }

        if( $user->vip_level != User::LEVEL_VIP )
        {
            return  $this->jsonReturn(0,'改用户不是天使会员');
        }

        $user->get_good = Request::input('get_good');
        $user->vip_level = User::LEVEL_MASTER;
        $user->save();
        return $this->jsonReturn(1);
    }

    public function anyUpActivityUser()
    {
        $user = User::find(Request::input('user_id'));
        if( !($user instanceof  User) )
        {
            return $this->jsonReturn(0,'用户不存在');
        }

        if( $user->vip_level )
        {
            return  $this->jsonReturn(0,'已经是高级用户');
        }

        $upPhone = Request::input('new_user_up_phone');
        if( $upPhone )
        {
            $upUser = User::where('phone',$upPhone)->first();
            if( !( $upUser instanceof  User))
            {
                return $this->jsonReturn(0,'上级用户不存在');
            }

            if( !$upUser->vip_level )
            {
                return $this->jsonReturn(0,'无效高级用户');
            }

            $user->parent_id = $upUser->id;
            $user->indirect_id = $upUser->parent_id;
        }


        $user->vip_level = Request::input('vip_level');
        if( $user->vip_level == User::LEVEL_VIP)
        {
            $user->angle_get_good = Request::input('get_good');
        } else {
            $user->get_good = Request::input('get_good');
        }
        $user->save();
        return $this->jsonReturn(1);
    }



    public function anyGetRemark()
    {
        $signRecord = User::find(Request::input('user_id'));
        if (!$signRecord instanceof User) {
            return $this->jsonReturn(0, '用户不存在');
        }

        $signRecord->get_remark = Request::input('get_remark');
        $signRecord->save();

        return $this->jsonReturn(1);
    }


    public function anyEditMark()
    {
        $signRecord = User::find(Request::input('user_id'));
        if (!$signRecord instanceof User) {
            return $this->jsonReturn(0, '用户不存在');
        }

        $signRecord->do_mark = Request::input('mark');
        $signRecord->save();

        return $this->jsonReturn(1);
    }


    /* this is start */



    public function anyCleanBill()
    {
        $query = Order::orderBy('orders.id','desc')->where([
            'pay_status'=>1,
            'buy_type'=>Order::BUY_TYPE_CLEAN
        ])->leftJoin('users','users.id','=','orders.user_id')->selectRaw('orders.*,users.phone,users.real_name');

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));


        return view('admin.clean_bill')->with('paginate', $paginate);
    }


    public function getCleanBillByDay()
    {
        return view('admin.cleanbill');
    }


    /**
     * 根据日期获得
     */
    public function postCleanBillByDay()
    {

    }


    /**
     * 送餐备注
     */
    public function anyFoodbillRemark()
    {
        $subFoodOrder = SubFoodOrders::find(Request::input('id'));
//        $subFoodOrder = SubFoodOrders::find(Request::input('id'));
        $subFoodOrder->remark = Request::input('remark');
        $subFoodOrder->save();
        return $this->jsonReturn(1);
    }


    /**
     * 参见金融服务的用户列表
     */
    public function anyFinanceUser()
    {


        /**
         * 当前的金融产品
         */
        $product = Product::activeFinance();

        $query = Book::orderBy('books.id','desc')->leftJoin('users','users.id','=','books.user_id')->leftJoin('book_finance_count','book_finance_count.user_id','=','books.user_id')->selectRaw('books.*,users.phone,users.real_name,count')->where('product_id',$product->id);

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));

        return view('admin.finance_user')->with('product',$product)->with('paginate', $paginate);
    }


    public function anyCleanManager()
    {

        $query = Product::where('type',1)->whereIn('status',[0,1,2]);
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.clean_manager')->with('paginate', $paginate);
    }

    public function anyFoodManager()
    {
        $query = Product::where('type',2);
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.food_manager')->with('paginate', $paginate);
    }


    public function anyFoodBill()
    {

        /**
         * 三种菜谱当天的菜单
         */
        //A餐


        //B餐

        //C餐
        $foodMenu = [
            ['productId'=>4,'menu'=>[FoodMenu::getMenuArr(4,date('Y-m-d'),1),FoodMenu::getMenuArr(4,date('Y-m-d'),2)]],
            ['productId'=>5,'menu'=>[FoodMenu::getMenuArr(5,date('Y-m-d'),1),FoodMenu::getMenuArr(5,date('Y-m-d'),2)]],
            ['productId'=>6,'menu'=>[FoodMenu::getMenuArr(6,date('Y-m-d'),1)]]
        ];

        return view('admin.foodbill')->with('food_menu',$foodMenu);
    }


    /**
     * 根据日期获得
     */
    public function anyFoodBillByDay()
    {
        $list = SubFoodOrders::where('date',date('Y-m-d'))->leftJoin('orders','orders.id','=','sub_food_orders.order_id')->selectRaw('orders.*,sub_food_orders.id as sub_id,status,type,has_print,sub_food_orders.remark as remark2')->get();
        return $this->jsonReturn(1,$list);
    }


    /**
     * 设置为已送达
     */
    public function anyDoDeliver()
    {
        $subFoodOrder = SubFoodOrders::find(Request::input('id'));
        $subFoodOrder->status = 2;
        $subFoodOrder->save();
        return $this->jsonReturn(1);
    }


    public function anyHealthTask(){
        return view('admin.segment.health_task');
    }


    public function anyCleanTask(){
        $query = Order::orderBy('orders.id','desc')->where([
            'pay_status'=>1,
            'buy_type'=>Order::BUY_TYPE_CLEAN
        ])->leftJoin('users','users.id','=','orders.user_id')->selectRaw('orders.*,users.phone,users.real_name');

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));

        return view('admin.segment.clean_task')->with('paginate', $paginate);
    }

    public function anyFinanceTask(){
        $product = Product::activeFinance();
        return view('admin.segment.finance_task')->with('product',$product);
    }

    public function anyFoodTask(){

        return view('admin.segment.food_task')->with('date',Request::input('date',date('Y-m-d')));
    }


    public function anyCleanDetail()
    {
        $product = Product::find(Request::input('id'));
        if( $product->type == 1) {
            return view('admin.clean_detail')->with('product', $product);
        } else {
            return view('admin.food_detail')->with('product',$product)->with('clWeekMenu',$product->clWeekMenu());
        }
    }

    /**
     * 编辑食物订单
     */
    public function anyEditFoodMenu()
    {
        $id = Request::input('id');
        if( !( $foodMenu = FoodMenu::find($id) ) )
        {
            $foodMenu = new FoodMenu();
        }

        $foodMenu->product_id = Request::input('product_id');
        $foodMenu->date = Request::input('date');
        $foodMenu->type = Request::input('type');
        $foodMenu->foods = Request::input('foods');
        $foodMenu->cover_img = Request::input('cover_img');
        $foodMenu->save();
        return $this->jsonReturn(1);
    }

    public function anyAddOrModifyProductAttr()
    {
        if( $id = Request::input('id') )
        {
            $productAttr = ProductAttr::find($id);
        } else
        {
            $productAttr = new ProductAttr();
        }

        $productAttr->price = Request::input('price');
        $productAttr->product_id = Request::input('product_id');
        $product = Product::find($productAttr->product_id);
        if ( $product->isCleanProduct() )
        {
            $productAttr->size = Request::input('size');
            if( $neighborhood = Neighborhood::find(Request::input('neighborhood_name',0)) )
            {
                $productAttr->neighborhood_id = $neighborhood->id;
                $productAttr->neighborhood_name = $neighborhood->neighborhood_name;
            }

        } else
        {
            $period = Period::find(Request::input('period_id'));
            $productAttr->period_id = $period->id;
            $productAttr->period_name = $period->period_name;

        }

        $productAttr->save();

        return $this->jsonReturn(1);
    }


    public function anyFinanceClass()
    {
        $query = FinanceClass::where('status','>',0);
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.finance_class')->with('paginate', $paginate);
    }


    /**
     * 设置状态
     */
    public function anySetStatus()
    {
        $product = Product::find(Request::input('id'));
        $product->status = Request::input('status');
        $product->save();

        return $this->jsonReturn(1);
    }

    /**
     *
     *
     */
    public function  anyAddProduct()
    {
        $product = new Product();
        $product->type = 1;
        $product->save();

        return $this->jsonReturn(1,$product->id);
    }


    public function anyDataManager()
    {
        $list = Banner::getBannerList();
        return view('admin.data_manager')->with('list',$list);
    }

    /**
     * 对banner图进行排序哟
     */
    public function anySortDataManager()
    {
        $ids = Request::input('ids');
        //这个要么一条语句一条语句的，要么批量更新，我更喜欢批量更新
        $banner = new Banner();
        $newData = [];
        foreach ($ids as $key=>$val)
        {
            $newData[] = ['id'=>$val,'sort'=>count($ids) - $key];
        }

        $banner->updateBatch(
            $newData
        );

        return $this->jsonReturn(1);
    }

    /**
     * 编辑
     */
    public function anyEditDataManager()
    {
        $banner = Banner::find(Request::input('id'));
        $banner->cover_image = Request::input('cover_image');
        if( Request::input('status') )
        {
            $banner->status = Request::input('status');
        }
        $banner->save();
        return $this->jsonReturn(1);
    }

    public function anyAddBanner()
    {
        $banner = new Banner();
        $banner->title = Request::input('title');
        $banner->type = 1;
        $banner->status = 0;
        $banner->save();
        return $this->jsonReturn(1);
    }


    public function anyBannerDetail()
    {
        $banner = Banner::find(Request::input('id'));
        return view('admin.banner_detail')->with('banner',$banner);
    }
    /* this is end*/


    public function anyInvited()
    {
        $paginate = DB::table('random_get')->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.invited')->with('paginate', $paginate);
    }

    /**
     * 生成邀请码
     */
    public function anyMakeInvited()
    {
        set_time_limit(360);

        $productId = Request::input('product_id');
        $quantity = Request::input('quantity');

        $loops = Request::input('loops');

        for( $i = 0; $i < $loops ; $i++)
        {
            $invitedCode = RandomPool::make();
            $randomGet = new RandomGet();
            $randomGet->product_id = $productId;
            $randomGet->code = $invitedCode;
            $randomGet->quantity = $quantity;
            $randomGet->save();
        }

        return $this->jsonReturn(1);
    }


    /**
     *
     */
    public function getHealthBill()
    {
        $foodMenu = [
            ['productId'=>4,'menu'=>[FoodMenu::getMenuArr(4,date('Y-m-d'),1),FoodMenu::getMenuArr(4,date('Y-m-d'),2)]],
            ['productId'=>5,'menu'=>[FoodMenu::getMenuArr(5,date('Y-m-d'),1),FoodMenu::getMenuArr(5,date('Y-m-d'),2)]],
            ['productId'=>6,'menu'=>[FoodMenu::getMenuArr(6,date('Y-m-d'),1)]]
        ];

        //服务人员列表
        $users = ServeUser::where('status',1)->where('type',2)->get();

        return view('admin.health_bill')->with('food_menu',$foodMenu)->with('serveUser',$users->toJson());
    }

    public function postHealthBill()
    {
        $list = Book::where('product_id',25)->get();
        return $this->jsonReturn(1,$list);
    }


    /**
     * 健康
     */
    public function anyDoHealth()
    {
        $subFoodOrder = Book::find(Request::input('id'));
        $subFoodOrder->status = 2;
        $subFoodOrder->serve_id = Request::input('serve_id');
        $subFoodOrder->save();
        return $this->jsonReturn(1);
    }


    public function anyAddOrModifyServeMember()
    {
        if( $id = Request::input('id') )
        {
            $serveMember = ServeUser::find($id);
        } else
        {
            $serveMember = new ServeUser();
        }


        $serveMember->real_name = Request::input('real_name');
        $serveMember->type = Request::input('type');
        $serveMember->id_card = Request::input('id_card');
        $serveMember->mobile = Request::input('mobile');
        $serveMember->status = 1;

        $serveMember->save();
        return $this->jsonReturn(1);

    }


    /**
     *
     */
    public function anyBookAddress()
    {
        $book = Book::find(Request::input('id'));
        $book->address = Request::input('address');
        $book->save();

        return $this->jsonReturn(1);
    }


    /**
     * 菜单
     */
    public function anyFoodMenu()
    {

        $low = Carbon::now()->subMonth()->format('Y-m-01');
        $high = Carbon::now()->addMonth()->addMonth()->format('Y-m-01');

        $list = FoodMenu::where('product_id',Request::input('product_id'))->orderBy('date','desc')->orderBy('type','asc')->where('date','<',$high)->where('date','>=',$low)->get();

        return $this->jsonReturn(1,$list);
    }

    public function anySetPrint()
    {
        $order = SubFoodOrders::find(Request::input('id'));
        if ( $order->has_print ) return $this->jsonReturn(1);

        $order->has_print = 1;
        $order->save();
        return $this->jsonReturn(1);
    }


    /**
     * 助餐订单
     */
    public function anyFoodOrder()
    {
        $query = Order::where('pay_status',1)->whereIn('product_id',[4,5,6])->orderBy('id','desc');
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('food_order')->with('paginate', $paginate);
    }


    public function anyFoodOrderDetail()
    {
        return view('food_order_detail');
    }

}