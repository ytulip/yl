@extends('admin.master',['headerTitle'=>'会员管理 > 会员详情'])
@section('style')
<style>
    .user-header{
        width: 48px;
        height: 48px;
        border-radius: 48px;
        overflow: hidden;
        position: absolute;
        bottom: 0;
        left:15px;
    }

    .user-header img{
        width: 48px;
        height: 48px;
        border-radius: 48px;
    }

    .statical-record{
        line-height: 40px;
        font-size: 14px;
        padding: 8px;
        margin-bottom: 10px;
    }

    .statical-record span{
        line-height: 40px;
        display: inline-block;
        float: right;
        font-size: 12px;
    }

    .has-bg{background-color: #f0f0f0;}


    .paginate-list-row {
        padding-top: 0;
        font-size: 14px;
        border: 1px solid #EAEEF7;
        background-color: #ffffff;
        height: 35px;
    }

    .paginate-list-row div{
        border-right: 1px solid #EAEEF7;
        background-color: #ffffff;
        height: 100%;
        line-height: 35px;
    }

</style>
@stop
@section('left_content')
<div class="mt-32 padding-col">
    <h4>基础信息</h4>
    <div class="block-card">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">订单编号</p>
                <p class="text-desc-decoration">{{$order->id}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">姓名</p>
                <p class="text-desc-decoration">{{$order->address_name}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">手机号</p>
                <p class="text-desc-decoration">{{$order->address_phone}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">购买时间</p>
                <p class="text-desc-decoration">{{$order->created_at}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">支付方式</p>
                <p class="text-desc-decoration">￥{{$order->need_pay}}(代金券{{count($order->couponsArr())}}张)</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">支付金额</p>
                <p class="text-desc-decoration">{{$order->origin_pay}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">订购套餐</p>
                <p class="text-desc-decoration">{{$order->product_name}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">订购数量</p>
                <p class="text-desc-decoration">{{$order->quantity}}人x{{$order->days}}天</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">备注</p>
                <p class="text-desc-decoration">{{$order->remark}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">送餐地址</p>
                <p class="text-desc-decoration">{{$order->address}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">订购方式</p>
                <p class="text-desc-decoration">{{\App\Model\Order::buyTypeText($order->days)}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">送餐时间</p>
                <p class="text-desc-decoration">{{$order->service_start_time}}{{$order->service_end_time?('-' . $order->service_end_time):''}}</p>
            </div>
        </div>

    </div>

    <h4 class="m-t-16">服务记录</h4>

    <div class="block-card">

        <div class="row paginate-list-row">
            <div class="col-md-2 col-lg-2">日期</div>
            <div class="col-md-2 col-lg-2">午/晚餐类别</div>
            <div class="col-md-2 col-lg-2">是否出单</div>
            <div class="col-md-3 col-lg-3">是否配送</div>
            <div class="col-md-3 col-lg-3">是否延后</div>
        </div>


        @foreach($list as $key=>$val)
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">{{$val->date}}</div>
                <div class="col-md-2 col-lg-2">{{($val->type == 1)?'午餐':'晚餐'}}</div>
                <div class="col-md-2 col-lg-2">{{$val->has_print?'已出单':'未出单'}}</div>
                <div class="col-md-3 col-lg-3">{{($val->status == 2)?'已配送':'未配送'}}</div>
                <div class="col-md-3 col-lg-3">{{($val->status == 100)?'是':'否'}}</div>
            </div>
        @endforeach
    </div>



</div>
@stop

@section('script')
<script>
</script>
@stop