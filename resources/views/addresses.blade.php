@extends('_layout.master')
@section('title')
    <title>收货地址管理</title>
@stop
@section('style')
    <style>
        html,body{background-color: rgb(239,243,246);}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 14px;border-top: 1px solid #ebeaea;border-bottom: 1px solid #ebeaea;}

        .item-footer{padding-top: 17px;padding-bottom: 17px;}

        .address-default{width: 24px;height: 24px;display:inline-block;background: url('/images/location_icon_nor@2x.png') no-repeat;background-size: 24px 24px;}

        .in-bl-line-item{vertical-align: middle;}

        .address-default.default{background: url('/images/location_icon_sel@2x.png') no-repeat;background-size: 24px 24px;}

    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'收货地址管理'])--}}
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">收货地址管理</span></div>
        <div class="cus-row-col-4 t-al-r" onclick="goAddMode()"><a href="javascript:void(0);"><span class="fs-16-fc-212229">新增</span></a></div>
    </div>


<div class="m-t-20 m-b-60">
    <div class="address-list">
        @if(count($addressList))
        @foreach( $addressList as $address )
        <div class="address-item" style="margin-bottom: 20px;">
            <div class="item-header" onclick="chooseAddress({{$address->address_id}},'{{$address->address_name}}','{{$address->mobile}}','{{$address->pct_code}}','{{$address->pct_code_name}}','{{$address->address}}')">
                <div><span>{{$address->address_name}}</span>&nbsp;&nbsp;<span>{{\App\Util\Kit::phoneHide($address->mobile)}}</span></div>
                <p><span>{{$address->pct_code_name}}</span>&nbsp;&nbsp;<span>{{$address->address}}</span></p>
            </div>
            <div class="item-footer">
                <div class="in-bl-line">
                    <div class="in-bl-line-item" style="width: 50%">
                        <a style="display: inline-block" href="javascript:setDefaultAddress({{$address->address_id}})"><span class="address-default @if($address->is_default) default @endif" style="display: inline-block;vertical-align: middle;"></span><span style="vertical-align: middle;margin-left: 4px;">默认收货地址</span></a></div>
                    <div class="in-bl-line-item" style="width: 25%"><a href="/user/add-mod-address?address_id={{$address->address_id}}">编辑</a></div>
                    <div class="in-bl-line-item" style="width: 25%" onclick="deleteAddress({{$address->address_id}})"><a>删除</a></div>
                </div>
            </div>
        </div>
            @endforeach
        @else
            <div class="t-al-c" style="margin-top: 80px;"><img src="/images/icon_img.png" style="display: inline-block;"/></div>
            <div class="t-al-c" style="margin-top: 24px;"><span style="font-size: 16px;color:#a8a8a8;">暂无内容</span></div>
        @endif
    </div>
</div>

{{--<footer class="fix-bottom">--}}
    {{--<div><a class="btn-block1 remove-radius" href="/user/add-mod-address">新增地址</a></div>--}}
{{--</footer>--}}
@stop

@section('script')
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script>
        
        function chooseAddress(id,name,phone,pct,pct_code_name,address) {
            var jsonData = JSON.stringify({ id: id, name:name,phone: phone,pct:pct,pct_code_name:pct_code_name,address:address});
            wx.miniProgram.postMessage({ data: jsonData});
            wx.miniProgram.navigateBack(
                {
                    delta:1,
                    success:function()
                    {
                        console.log('999888');
                    }
                }
            );
        }


        function  goAddMode() {
            wx.miniProgram.navigateTo(
                {
                    url:'/pages/editaddress/main'
                }
            );
        }
        
        
        function deleteAddress(addressId)
        {
            $.post('/user/delete-address',{address_id:addressId},function(data){
                location.reload();
            },'json').error(function(){
                mAlert('网络异常');
            });
        }

        function setDefaultAddress(addressId)
        {
            $.post('/user/set-default-address',{address_id:addressId},function(data){
                location.reload();
            },'json').error(function(){
                mAlert('网络异常');
            });
        }
    </script>
@stop