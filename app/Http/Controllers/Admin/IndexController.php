<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Log\Facades\Logger;
use App\Model\Admin;
use App\Model\Banner;
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
use App\Model\SignRecord;
use App\Model\SyncModel;
use App\Model\User;
use App\Model\UserAddress;
use App\Util\CommKit;
use App\Util\DownloadExcel;
use App\Util\Kit;
use App\Util\OrderStatical;
use App\Util\SmsTemplate;
use App\Util\TotalStatical;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class IndexController extends Controller
{
    public function getHome()
    {
        return view('admin.index');
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
        //提现支出
        $withdraw = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_WITHDRAW_AGREE])->sum('price');
        $withdrawCount = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_WITHDRAW_AGREE])->count();

        //开发支出
        $directIndirect = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_DIRECT, CashStream::CASH_TYPE_BENEFIT_INDIRECT])->sum('price');
        $directIndirectCount = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_DIRECT, CashStream::CASH_TYPE_BENEFIT_INDIRECT])->count();

        //辅导分红
        $upSuper = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_UP, CashStream::CASH_TYPE_BENEFIT_SUPER])->sum('price');
        $upSuperCount = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_UP, CashStream::CASH_TYPE_BENEFIT_SUPER])->count();


        $totalStatical = new TotalStatical();
        $totalStatical->init();

        return view('admin.total_finance')->with('withdraw', $withdraw)->with('directIndirect', $directIndirect)->with('upSuper', $upSuper)->with('withdrawCount', $withdrawCount)->with('directIndirectCount', $directIndirectCount)->with('upSuperCount', $upSuperCount)->with('totalStatical', $totalStatical);
    }

    public function getMembers()
    {
        $query = DB::table('users')->orderBy('id', 'desc')->where('vip_level', 2)->where(function ($query) {
            if (Request::input('keyword') != '') {
                $query->where('real_name', 'like', '%' . Request::input('keyword') . '%')->orWhere('phone', 'like', '%' . Request::input('keyword') . '%');
            }
        });

        Kit::compareBelowZeroQuery($query,array_only(Request::all(),['charge']));

        if (Request::input('download')) {
            $list = $query->get();
            $dataList = array();
            foreach ($list as $key => $item) {
                $tempArray = array($item->id, $item->real_name, \App\Model\User::levelText($item->vip_level), $item->phone, $item->id_card, $item->get_remark,$item->created_at);
                array_push($dataList, $tempArray);
            }


            $data = array(
                'title' => array('会员ID', '姓名', '身份等级', '手机号码', '身份证号码','报单/复购/活动标记' ,'注册时间'),
                'data' => $dataList,
                'name' => 'tixian',
                'format_text_array' => [0, 0, 0, 1, 1, 0]
            );
            DownloadExcel::publicDownloadExcel($data);
            return;
        }

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.members')->with('paginate', $paginate);
    }

    public function getActivityMembers()
    {
        $query = User::where('activity_pay', 1)->orderBy('id', 'desc')->where(function ($query) {
            if (Request::input('keyword') != '') {
                $query->where('real_name', 'like', '%' . Request::input('keyword') . '%')->orWhere('phone', 'like', '%' . Request::input('keyword') . '%');
            }
        });

        if (Request::input('download')) {
            $list = $query->get();
            $dataList = array();

            foreach ($list as $key => $item) {

                $order = $item->getActivityPayedOrder();
                $recommendId = '';
                $recommendPhone = '';
                $recommendName = '';
                if( $order instanceof  Order)
                {
                    $recommendUser = User::find($order->immediate_user_id);
                    if( $recommendUser instanceof  User)
                    {
                        $recommendId = $recommendUser->id;
                        $recommendPhone = $recommendUser->phone;
                        $recommendName = $recommendUser->real_name;
                    }
                }

                $tempArray = array($item->id, $item->real_name, \App\Model\User::levelText($item->vip_level), $item->phone, $item->id_card, $recommendId,$recommendPhone,$recommendName,$item->do_mark,$item->get_remark, $item->created_at);
                array_push($dataList, $tempArray);
            }


            $data = array(
                'title' => array('会员ID', '姓名', '身份等级', '手机号码', '身份证号码', '活动推荐人ID','活动推荐人手机号','活动推荐人姓名','活动会员备注','报单/复购/活动标记','注册时间'),
                'data' => $dataList,
                'name' => 'tixian',
                'format_text_array' => [0, 0, 0, 1, 1, 0]
            );
            DownloadExcel::publicDownloadExcel($data);
            return;
        }

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));

        foreach ($paginate as $key=>$val)
        {
            $paginate[$key]->recommendId = '';
            $paginate[$key]->recommendPhone = '';
            $paginate[$key]->recommendName = '';

            $order = $val->getActivityPayedOrder();
            if( $order instanceof  Order)
            {
                $recommendUser = User::find($order->immediate_user_id);
                if( $recommendUser instanceof  User)
                {
                    $paginate[$key]->recommendId = $recommendUser->id;
                    $paginate[$key]->recommendPhone = $recommendUser->phone;
                    $paginate[$key]->recommendName = $recommendUser->real_name;
                }
            }

        }

        return view('admin.activity_members')->with('paginate', $paginate);
    }

    public function getAngleMembers()
    {
        $query = User::where('vip_level', User::LEVEL_VIP)->orderBy('id', 'desc')->where(function ($query) {
            if (Request::input('keyword') != '') {
                $query->where('real_name', 'like', '%' . Request::input('keyword') . '%')->orWhere('phone', 'like', '%' . Request::input('keyword') . '%');
            }
        });

        if (Request::input('download')) {
            $list = $query->get();
            $dataList = array();

            foreach ($list as $key => $item) {

                $tempArray = array($item->id, $item->real_name, \App\Model\User::levelText($item->vip_level), $item->phone, $item->id_card, $item->created_at);
                array_push($dataList, $tempArray);
            }


            $data = array(
                'title' => array('会员ID', '姓名', '身份等级', '手机号码', '身份证号码','注册时间'),
                'data' => $dataList,
                'name' => 'tianshihuiyuan'
            );
            DownloadExcel::publicDownloadExcel($data);
            return;
        }

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));

        foreach ($paginate as $key=>$val)
        {
            $paginate[$key]->recommendId = '';
            $paginate[$key]->recommendPhone = '';
            $paginate[$key]->recommendName = '';

            $order = $val->getActivityPayedOrder();
            if( $order instanceof  Order)
            {
                $recommendUser = User::find($order->immediate_user_id);
                if( $recommendUser instanceof  User)
                {
                    $paginate[$key]->recommendId = $recommendUser->id;
                    $paginate[$key]->recommendPhone = $recommendUser->phone;
                    $paginate[$key]->recommendName = $recommendUser->real_name;
                }
            }

        }

        return view('admin.angle_members')->with('paginate', $paginate);
    }

    public function getSubMemberList()
    {
        $query = DB::table('users')->orderBy('id', 'desc')->where(function ($query) {
            $query->where('parent_id', Request::input('user_id'))->orWhere('indirect_id', Request::input('user_id'));
        })->where(function ($query) {
            if (Request::input('sub_type') == 1) {
                $query->where('parent_id', Request::input('user_id'));
            } else if (Request::input('sub_type') == 2) {
                $query->where('indirect_id', Request::input('user_id'));
            };

            if (Request::input('keyword') != '') {
                $query->where('real_name', Request::input('keyword'))->orWhere('phone', Request::input('keyword'));
            }


        });
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.sub_member_list')->with('paginate', $paginate);
    }

    public function getMemberDetail()
    {
        $userId = Request::input('user_id');
        $user = User::find($userId);
        if (!$user) {
            dd('用户不存在');
        }

        $order = $user->hasTakePartInActivity();
        $signQuantity = 0;
        if ($order) {
            $signQuantity = $order->sign_quantity ? $order->sign_quantity : $signQuantity;
        }

        return view('admin.member_detail')->with('user', $user)->with('relationMap', $user->relationMap())->with('staticalCashStream', $user->staticalCashStream())->with('signQuantity', $signQuantity);
    }

    public function getOrders()
    {

        //
        $orderStatical = new OrderStatical();

        $query = Order::orderBy('pay_time', 'desc')->where('pay_status', 1)->leftJoin('users', 'user_id', '=', 'users.id')->selectRaw('*,orders.id as order_id');

        CommKit::equalQuery($query, ['order_status' => Request::input('order_status')]);

        //开始时间
        if (Request::input('start_time')) {
            $query->where('pay_time', '>=', Request::input('start_time'));
        }

        if (Request::input('end_time')) {
            $endTime = Request::input('end_time');
            $query->where('pay_time', '<', date('Y-m-d', strtotime("$endTime +1 day")));
        }

        if (Request::input('download')) {
            $list = $query->get();
            $dataList = array();
            foreach ($list as $key => $item) {
                $user = User::find($item->immediate_user_id);
                $immediateUserId = $item->immediate_user_id;
                $immediateUserRealName = '';
                $immediateUserIdCard = '';

                if ($user instanceof User) {
                    $immediateUserRealName = $user->real_name;
                    $immediateUserIdCard = $user->id_card;
                }

                $tempArray = array($item->order_id, $item->pay_time, $item->real_name, $item->phone, $item->quantity, $item->need_pay, $item->buyTypeText(), $immediateUserId, $immediateUserRealName, $immediateUserIdCard);
                array_push($dataList, $tempArray);
            }


            $data = array(
                'title' => array('订单编号', '购买时间', '购买人姓名', '联系方式', '购买数量', '购买价格', '购买类型', '直接开发者ID', '直接开发者姓名', '直接开发者手机号'),
                'data' => $dataList,
                'name' => 'tixian'
            );
            DownloadExcel::publicDownloadExcel($data);
            return;
        }

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.orders')->with('paginate', $paginate)->with('orderStatical', $orderStatical)->with('attrPercent', ['attr1_count' => $orderStatical->countValidOrder([1]), 'attr2_count' => $orderStatical->countValidOrder([2]), 'attr1' => ProductAttr::find(1), 'attr2' => ProductAttr::find(2)]);
    }

    public function getOrderDetail()
    {
        $orderId = Request::input('order_id');
        $order = Order::find($orderId);
        if (!$order) {
            dd('订单不存在');
        }
        return view('admin.order_detail')->with('order', $order)->with('direct', User::find($order->immediate_user_id))->with('productAttr', ProductAttr::find($order->product_attr_id))->with('orderCash', $order->cashInfo())->with('invitedCode', $order->invitedCodeInfo());
    }


    public function postSetGetStatus()
    {
        $orderId = Request::input('order_id');
        $targetStatus = Request::input('status');
        $order = MonthGetGood::find($orderId);
        if (!$order) {
            return $this->jsonReturn(0, '订单不存在');
        }

        if ($targetStatus == Order::ORDER_STATUS_SELF_GOT && $order->get_status == Order::ORDER_STATUS_WAIT_SELF_GET) {
            $order->get_status = $targetStatus;


        } else if ($targetStatus == Order::ORDER_STATUS_DELIVERED && $order->get_status == Order::ORDER_STATUS_WAIT_DELIVER) {
            $order->get_status = $targetStatus;

            $deliverArray = json_decode(Request::input('deliver_array'));
            if (!is_array($deliverArray)) {
                return $this->jsonReturn(0, '物流信息输入有误');
            }
            $order->deliver_array = Request::input('deliver_array');

            foreach ($deliverArray as $item) {
                //            //物流信息
//            //发送物流通知短信
                $smsTemplate = new SmsTemplate(SmsTemplate::DELIVER_SMS);
                $smsTemplate->sendSms(User::find($order->user_id)->phone, ['company' => $item->deliver_company_name, 'billno' => $item->deliver_number]);
            }

//            $order->deliver_company_name = Request::input('deliver_company_name');
//            $order->deliver_number = Request::input('deliver_number');
//
//            //物流信息
//            //发送物流通知短信
//            $smsTemplate = new SmsTemplate(SmsTemplate::DELIVER_SMS);
//            $smsTemplate->sendSms(User::find($order->user_id)->phone,['company'=>$order->deliver_company_name,'billno'=>$order->deliver_number]);


        } else {
            return $this->jsonReturn(0, '状态异常');
        }

        $order->save();
        return $this->jsonReturn(1);
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

    public function getWithdraw()
    {
        $query = DB::table('cash_stream')->orderBy('cash_stream.id', 'desc')->leftJoin('users', 'user_id', '=', 'users.id')->where('cash_type', CashStream::CASH_TYPE_WITHDRAW)->where(function ($query) {
            if (Request::input('keyword') != '') {
                $query->where('real_name', 'like', '%' . Request::input('keyword') . '%')->orWhere('phone', 'like', '%' . Request::input('keyword') . '%');
            }
        })->selectRaw('*,cash_stream.created_at as withdraw_created_at,cash_stream.id as withdraw_id');

        CommKit::betweenTime($query, 'cash_stream.created_at');
        CommKit::equalQuery($query,array_only(Request::all(),['withdraw_deal_status']));


        if (Request::input('download')) {
            $list = $query->get();
            $dataList = array();
            foreach ($list as $key => $item) {
                $tempArray = array($item->withdraw_created_at, $item->real_name, $item->phone, number_format($item->price, 2), \App\Model\CashStream::withdrawTypeText($item->withdraw_type), $item->withdraw_bank, $item->withdraw_account, \App\Model\CashStream::withdrawStatusText($item->withdraw_deal_status),$item->remark);
                array_push($dataList, $tempArray);
            }


            $data = array(
                'title' => array('申请时间', '申请人姓名', '联系方式', '申请金额', '提现方式', '提现银行', '提现账号', '处理状态','原因备注'),
                'data' => $dataList,
                'name' => 'tixian'
            );
            DownloadExcel::publicDownloadExcel($data,true);
            return;
        }

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.withdraw')->with('paginate', $paginate)->with('withdrawInfo', TotalStatical::withdrawInfo());
    }


    public function getWithdrawDetail()
    {
        $withdraw = CashStream::where('id', Request::input('withdraw_id'))->where('cash_type', CashStream::CASH_TYPE_WITHDRAW)->first();
        if (!$withdraw) {
            dd('记录不存在');
        }
        return view('admin.withdraw_detail')->with('withdraw', $withdraw)->with('user', User::find($withdraw->user_id));
    }

    public function getDirectInDirect()
    {
        return view('admin.direct_indirect');
    }

    public function getUpSuper()
    {
        return view('admin.up_super');
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
//        $product->product_name = Request::input('title');
//        $product->context = Request::input('content');
//        $product->context_deliver = Request::input('content_deliver');
//        $product->context_server = Request::input('content_server');
        $product->food_desc = Request::input('food_desc');
        $product->fit_indi = Request::input('fit_indi');



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

    public function postNewOrder()
    {
        $this->validate(Request::all(), [
            'user_id' => 'required',
            'product_attr_id' => 'required',
            'immediate_phone' => 'required|exists:users,phone',
//            'deliver_type'=>'required',
//            'self_get_deliver_address'=>'exists:user_address,address_id',
//            'mine_deliver_address'=>'exists:user_address,address_id'
        ]);

        $indirectUser = User::find(Request::input('user_id'));
        if ($indirectUser->vip_level != User::LEVEL_MASTER) {
            return $this->jsonReturn(0, '成为高级会员才能提单');
        }

        $immediateUser = User::where('phone', Request::input('immediate_phone'))->first();

        $productAttr = ProductAttr::find(Request::input('product_attr_id'));


//        if( Deliver::DELIVER_HOME == Request::input('deliver_type') ) {
//            $address = UserAddress::find(Request::input('mine_deliver_address'));
//        } else {
//            $address = UserAddress::find(Request::input('self_get_deliver_address'));
//        }

        //生产订单
        $order = new Order();
        $order->user_id = $indirectUser->id;
        $order->immediate_user_id = $immediateUser->id;
        $order->product_id = $productAttr->product_id;
        $order->product_attr_id = $productAttr->id;
        $order->need_pay = 0;
//        $order->address = $address->pct_code_name . $address->address;
//        $order->address_name = $address->address_name;
//        $order->address_phone = $address->mobile;
//        $order->deliver_type = Request::input('deliver_type');
        $order->pay_status = 1;
        $order->order_status = Order::ORDER_STATUS_ADMIN_BUY;
        $order->pay_type = Order::PAY_ADMIN;
        $order->pay_time = date('Y-m-d H:i:s');

        $order->save();


        //支付订单逻辑
        InvitedCodes::makeRecord($order->id);

        return $this->jsonReturn(1, $order->id);
    }

    public function anyGetUserByName()
    {
        $res = User::where('real_name', Request::input('real_name'))->get();
        if (!$res) {
            $res = [];
        }

        foreach ($res as $key => $val) {
            $upUser = User::find($val->parent_id);
            $superUser = User::find($val->indirect_id);

            $res[$key]->vip_level_text = User::levelText($val->vip_level);
            $res[$key]->up_phone = isset($upUser->phone) ? $upUser->phone : '';
            $res[$key]->up_real_name = isset($upUser->real_name) ? $upUser->real_name : '';


            $res[$key]->super_phone = isset($superUser->phone) ? $superUser->phone : '';
            $res[$key]->super_real_name = isset($superUser->real_name) ? $superUser->real_name : '';
        }

        return $this->jsonReturn(1, $res);
    }

    public function getInvitedCode()
    {
        $order = Order::find(Request::input('order_id'));
        return view('admin.invited_code')->with('order', $order);
    }


    public function getUpSuperRecord()
    {
        $userId = Request::input('user_id');
        $user = User::find($userId);
        if (!$user) {
            dd('用户不存在');
        }

        $query = CashStream::where('user_id', $user->id)->whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_UP, CashStream::CASH_TYPE_BENEFIT_SUPER]);

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));

        foreach ($paginate as $key => $val) {
            $tempUser = User::find($val->refer_user_id);
            if ($tempUser) {
                $paginate[$key]->user_model = $tempUser;
            } else {
                $paginate[$key]->user_model = (object)[
                    "real_name" => "未注册",
                    "phone" => "未注册",
                    "created_at" => "未注册"
                ];
            }
        }

        return view('admin.up_super_record')->with('paginate', $paginate)->with('user', $user);
    }

    public function getUpSuperRecordAll()
    {
        //开发支出
        $direct = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_UP])->where('pay_status', '>', 0)->sum('price');
        $directCount = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_UP])->where('pay_status', '>', 0)->count();


        $indirect = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_SUPER])->where('pay_status', '>', 0)->sum('price');
        $indirectCount = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_SUPER])->where('pay_status', '>', 0)->count();


        $query = CashStream::whereIn('cash_type', [CashStream::CASH_TYPE_BENEFIT_UP, CashStream::CASH_TYPE_BENEFIT_SUPER])->leftJoin('users', 'users.id', '=', 'cash_stream.user_id')->leftJoin('users as b', 'b.id', '=', 'cash_stream.refer_user_id')->where('pay_status', '>', 0)->orderBy('cash_stream.id', 'desc')->selectRaw('cash_stream.*,users.real_name,b.real_name as refer_real_name');

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

        return view('admin.up_super_record_all')->with('paginate', $paginate)->with('up', $direct)->with('upCount', $directCount)->with('super', $indirect)->with('superCount', $indirectCount);
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
        return view('admin.power')->with('paginate', $paginate);;
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

    /**
     * 系统所有用户搜索
     */
    public function anyUsers()
    {

        $query = User::orderBy('id', 'desc')->where(function ($query) {
            if (Request::input('keyword') != '') {
                $query->where('real_name', 'like', '%' . Request::input('keyword') . '%')->orWhere('phone', 'like', '%' . Request::input('keyword') . '%');
            }
        });

        if( Request::input('activity_turn') )
        {
            $activityUserId = Order::where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('pay_status',1)->lists('user_id');
            $turnUserId = CashStream::where('cash_type',CashStream::CASH_TYPE_ACTIVITY_WITHDRAW)->whereNotIn('withdraw_deal_status',[2])->lists('user_id');
            $query->whereIn('id',$activityUserId)->whereNotIn('id',$turnUserId);
        }

        $getStatus = Request::input('get_status');
        switch ($getStatus)
        {
            case '1':
                Kit::compareBelowZeroQuery($query,['get_good'=>1]);
                break;
            case '2':
                Kit::compareBelowZeroQuery($query,['re_get_good'=>1]);
                break;
            case '3':
                Kit::compareBelowZeroQuery($query,['activity_get_good'=>1]);
                break;
        }

        if (Request::input('download')) {
            $list = $query->get();
            $dataList = array();
            foreach ($list as $key => $item) {
                $tempArray = array($item->id, $item->real_name, \App\Model\User::levelText($item->vip_level), $item->phone, $item->id_card, $item->get_good,$item->re_get_good,$item->activity_get_good,($item->needToTurn()?'是':'否'));
                array_push($dataList, $tempArray);
            }


            $data = array(
                'title' => array('会员ID', '姓名', '身份等级', '手机号码', '身份证号码', '报单','提货','活动','未退款'),
                'data' => $dataList,
                'name' => 'tixian'
            );
            DownloadExcel::publicDownloadExcel($data);
            return;
        }

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.users')->with('paginate', $paginate);
    }


    public function anyGetRemark()
    {
        $signRecord = User::find(Request::input('user_id'));
        if (!$signRecord instanceof User) {
            return $this->jsonReturn(0, '用户不存在');
        }

        $signRecord->get_remark = Request::input('get_remark');
        $signRecord->save();

//        Message::addSignComment($signRecord->id);

        //发送评论消息

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

//        Message::addSignComment($signRecord->id);

        //发送评论消息

        return $this->jsonReturn(1);
    }

    /**
     * 变更上级
     */
    public function getChangeFather()
    {
        $userId = Request::input('user_id');
        $fatherId = Request::input('father_id');

        $user = User::find($userId);
        $father = User::find($fatherId);

        if( !($user instanceof  User) ){
            echo $userId . '不存在';
            exit;
        }

        echo '用户' . $user->real_name . '的上级为' . User::tryGetRealName($user->parent_id,'???') . '上上为' . User::tryGetRealName($user->indirect_id,'????') . '<br/>';

        if( !($father instanceof  User) ){
            echo $fatherId . '父级不存在';
            exit;
        }

        //判断是否存在关联信息
        $count = User::whereIn('parent_id',[$userId])->orWhereIn('indirect_id',[$userId])->count();
        if( $count )
        {
            echo '有'.$count.'条关联数据';
            exit;
        }

        $user->parent_id = $father->id;
        $user->indirect_id = $father->parent_id;
        $user->save();

        echo '用户' . $user->real_name . '的上级调整为' . User::tryGetRealName($user->parent_id,'???') . '上上级调整为' . User::tryGetRealName($user->indirect_id,'????');
        exit;
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


    /**
     * 参见金融服务的用户列表
     */
    public function anyFinanceUser()
    {
        $query = FinanceUser::orderBy('finance_user.id','desc')->leftJoin('users','users.id','=','finance_user.user_id')->selectRaw('finance_user.*,users.phone,users.real_name');

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));

        return view('admin.finance_user')->with('paginate', $paginate);
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
        return view('admin.segment.finance_task');
    }

    public function anyFoodTask(){
        return view('admin.segment.food_task');
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
        $banner->title = '破鞋';
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

}