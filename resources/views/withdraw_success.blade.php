@extends('_layout.master')
@section('title')
    <title>提现申请</title>
@stop
@section('style')
    <style>
        html,body{background-color: rgb(239,243,246);}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 10px;}
        .income-list{font-size: 12px;}
    </style>
@stop
@section('container')
<div class="cus-row p-l-r-14">
    <div class="cus-row-col-4"></div>
    <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">提现申请</span></div>
    <div class="cus-row-col-4 t-al-r"></div>
</div>

    <div class="p-l-r-14">
        <div style="background-color: #ffffff;border: 1px solid #EBE9E9;">
            <div style="margin-top: 60px;text-align: center;"><img src="/images/icon_success.png" style="display: inline-block;"/></div>

            <p style="padding: 46px;" class="fs-14-fc-212229 t-al-c">审核时间为48小时，结果会在第一时间通过手机短信通知您，请注意查收</p>
        </div>
    </div>

    <div style="padding: 0 42px;margin-top: 42px;"><a href="javascript:goHome()" style="border: 1px solid #98CC3D;
border-radius: 100px;line-height: 40px;text-align: center;display: inline-block;width: 100%;"><span style="font-size: 17px;
color: #98CC3D;">返回主页</span></a></div>
    {{--@include('segments.header',['headerTile'=>'提现申请'])--}}
    {{--<p>申请成功</p>--}}
    {{--<p></p>--}}
    {{--<div class="fix-bottom">--}}
        {{--<a class="btn-block1 remove-radius" href="/user/center">完成</a>--}}
    {{--</div>--}}
@stop

@section('script')
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
<script type="text/javascript" src="https://underscorejs.org/underscore-min.js"></script>
<script>
    function goHome()
    {
        wx.miniProgram.switchTab({
            url: '/pages/index/main'
        });
    }


    var date1 = new Date(2019,0,24).toTimeString();
    var date2 = new Date(2019,0,25).toTimeString();
    var date3 = new Date(2019,0,26).toTimeString();

    var currentDate = new Date(2019,0,26).toTimeString();

    var dateList = [date1,date2,date3];
    console.log( _.indexOf(dateList,currentDate));

</script>
@stop