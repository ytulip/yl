@extends('_layout.master')
@section('title')
    <title>支付结果</title>
@stop
@section('style')
    <style>

        html,body{background-color: #f9f9fb;}
        /*footer a{line-height: 40px;display: block;font-size: 16px;background: #0000C2;color:#ffffff;text-align: center;}*/

    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'购买完成'])--}}
<div style="padding: 80px 48px 0 48px;">
    <div class="t-al-c">
        <img class="in-bl-v-m" style="width: 32px;" src="/images/icon_scuess_nor@3x.png"/><span style="margin-left: 14px;" class="in-bl-v-m fs-24-fc-000000-m">支付成功</span>
    </div>

    <div style="margin-top: 75px">
        <a class="yl_btn1" href="javascript:goHome()">完成</a>
    </div>

    <div style="margin-top: 24px">
        <a class="yl_btn1 btn-white" href="javascript:goDetail()">查看详情</a>
    </div>


</div>
@stop

@section('script')
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script>

        var pageConfig = {
            id:{{$order->id}}
        }

        function goHome()
        {
            wx.miniProgram.switchTab({
                url: '/pages/index/main'
            });
        }

        function goDetail()
        {
            wx.miniProgram.navigateTo(
                {
                    url:'/pages/billdetail/main?id=' + pageConfig.id
                }
            );
        }
    </script>
@stop