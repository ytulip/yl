@extends('_layout.master')
@section('title')
    <title>购买</title>
@stop
@section('style')
<link href="/js/plugin/mobile-select/mobileSelect.css" rel="stylesheet"/>
<style>

    #city *{
        padding: 0;margin: 0;
    }
    html,body{background-color: #f8f8f8;}
    .item-footer{margin-top: 4px;}
    .mui-input-row{margin-top: 2px;}
    .item-header,.item-footer{background-color: #ffffff;padding: 10px;}

    .city-mask{
        position: fixed;
        top:0;
        bottom: 0;
        right: 0;
        left: 0;
        background-color: rgba(0,0,0,.6);
        z-index: 999;
    }

    .city-panel{
        position: absolute;
        height: 215px;
        background-color: #ffffff;
        display: inline-block;
        right: 0;
        left: 0;
        bottom: 0;
    }

    .city-panel li{
        line-height: 37px;;
        list-style: none;
    }

    .city-panel-header{
        overflow: hidden;
    }
    .city-panel-header a{
        line-height: 32px;
        padding: 0 12px;
    }

    .city-panel-header a:nth-child(1){
        float: left;
    }

    .city-panel-header a:nth-child(2){
        float: right;
        color:#47b5ca;
    }

    .city-panel-body{
        height: 185px;
        position: relative;
        overflow: hidden;
    }
    .province-list{
        position: absolute;
        top:0;
        bottom:0;
        left: 0;
        width: 100%;
        text-align: center;
    }

    .city-list{
        position: absolute;
        top:0;
        bottom:0;
        right: 0;
        width: 50%;
        text-align: center;
    }

    .city-barrier{
        position: absolute;
        height: 37px;
        right: 0;
        left: 0;
        top:74px;
        border-top: solid 1px #e2e2e2;
        border-bottom: solid 1px #e2e2e2;
    }

    #city input{border: none;padding: 10px 0; }

    .info-item{width: 60%;}
    .opr-item{width: 40%;}

    .btn-bg-d1{background-color: #d1d1d1;}
    .btn-bg-9b{background-color: #9b9b9b;}
    .btn-bg-f5{background-color: #f5a623;}

    /*.select-default{font-size: 14px;color:#bebebe;}*/
    /*.select-default option{color:#212229}*/
    /*.select-default option:nth-child(1){color:#bebebe!important;}*/

    input::-webkit-input-placeholder{color: #bebebe;  }

</style>
    @stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'购买/邀请新会员'])--}}
    <div class="padding-container">
        <div><i class="back-icon"></i><span class="fs-26-fc-black">填写订单</span></div>

        <div class="white-bg-card">
            <div class="cus-row">
                <div class="cus-row-col-6">
                    <div class="in-bl-v-m"><i class="agree-icon"></i></div>
                    <div class="in-bl-v-m fs-16-fc-030303">订购服务</div>
                </div>
            </div>

            <div class="cus-row">
                <div class="cus-row-col-3 fs-16-fc-030303">备注</div>
                <div class="cus-row-col-9 fs-16-fc-030303"><input placeholder="请备注您的特殊需求" id="remark"/></div>
            </div>
        </div>


        <div class="white-bg-card">
            <div class="cus-row">
                <div class="cus-row-col-6">
                    <div class="in-bl-v-m"><i class="agree-icon"></i></div>
                    <div class="in-bl-v-m fs-16-fc-030303">服务地址</div>
                </div>
                <div class="cus-row-col-6 fs-16-fc-030303" onclick="modifyAddress()">
                    修改
                </div>
            </div>
        </div>



        <div class="white-bg-card" id="service_time">


            <div class="cus-row">
                <div class="cus-row-col-3 fs-16-fc-030303">服务面积</div>
                <div class="cus-row-col-8 fs-16-fc-030303">
                    {{--<input placeholder="请选择服务时间"/>--}}
                    <input v-model="size"/>
                </div>
                <div class="cus-row-col-1 fs-16-fc-030303"><i class="next-icon"></i></div>
            </div>


            <div class="cus-row">
                <div class="cus-row-col-3 fs-16-fc-030303">服务时间</div>
                <div class="cus-row-col-8 fs-16-fc-030303">
                    {{--<input placeholder="请选择服务时间"/>--}}
                    <select id="clean_service_time">
                        <option v-for="item in serviceTime" v-bind:value="item"> @{{item}} </option>
                    </select>
                </div>
                <div class="cus-row-col-1 fs-16-fc-030303"><i class="next-icon"></i></div>
            </div>
        </div>

    </div>



    <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-6" id="total_price">
                <span class="fs-24-fc-212229">￥</span><span class="fs-24-fc-212229" id="price_label">@{{totalPrice}}</span>
            </div>
            <div class="cus-row-col-6">
                <a class="btn-block1 m-t-20" href="javascript:void(0)" id="next_step" style="margin-top: 0;">立即付款</a>
            </div>
        </div>
    </footer>



@stop

@section('script')
<script src="/js/vue.js"></script>
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
<script src="/js/plugin/mobile-select/mobileSelect.js"></script>
<script src="/js/cus-select.js"></script>
<script src="/js/utils/isMP.js"></script>
<script>


    function modifyAddress() {
        wx.miniProgram.navigateTo(
            {
                url: "/pages/address/main?openid=" + pageConfig.openid
            });
    }

    var pageConfig = {
        service_time:{!!  \App\Model\YlConfig::value('clean_service_time') !!},
        productPrice:{{$product->price}},
        productId:{{$product->id}},
        openid:'{{\Illuminate\Support\Facades\Request::input('openid')}}'
    }


    var serviceTimeVue = new Vue({
            el: '#service_time',
            data:{
                serviceTime:pageConfig.service_time,
                size:'7'
            },
            watch: {
                size: function (val) {
//                    console.log(val);
                    totalPriceVue.size = val;
                }
            }
        }
    );

    var totalPriceVue = new Vue(
        {
            el:'#total_price',
            data:{size:0},
            computed: {
                // 计算属性的 getter
                totalPrice: function () {
                    // `this` 指向 vm 实例
                    return this.size * pageConfig.productPrice
                }
            }
        }
    );

    function chooseAddress(){
        addressVue.show();
    }


    new CusSelect({

    });


    $('input[name="quantity"]').bind('input propertychange', function() {

    })

    isWeChatApplet().then(function (res) {
        console.log(res);
    });
//    console.log();

    new CusSelect({
        itemArr:pageConfig.buyType,
        triggerEl:'input[name="product_id"]',
        idSpecial:2,
        callback:function(id){

            if(id == 2)
            {
                $('#immediate_line').hide();
                $('#quantity_line').show();
            } else if( id == 1)
            {
                $('#immediate_line').show();
                $('#quantity_line').hide();
            }

            var price = 0.00;
            $.each(pageConfig.buyType,function(ind,obj){
                console.log(obj.value);
                if(obj.value == id)
                {
                    if( id == 2)
                    {
                        price = obj.price * parseInt($('input[name="quantity"]').val());
                        $('#price_label').html(price);
                        return false;
                    } else {
                        price = obj.price;
                        $('#price_label').html(price);
                        return false;
                    }
                }
            });
        }
    });


    $('.cus-select').change(function(){

//        alert(123);

        if($(this).val()==''){
            $(this).addClass('select-default');
        }else{
            $(this).removeClass('select-default');
        }
    });

    $('.cus-select').change();



$(function(){

/*配送方式改变*/
    $('select[name="deliver_type"]').change(function(){
        if($(this).val() == 1) {
            $('select[name="self_get_deliver_address"]').show();
            $('select[name="mine_deliver_address"]').hide();
        } else if($(this).val() == 2) {
            $('select[name="self_get_deliver_address"]').hide();
            $('select[name="mine_deliver_address"]').show();
        }  else {
            $('select[name="self_get_deliver_address"]').hide();
            $('select[name="mine_deliver_address"]').hide();
        }

    });

    $('select[name="deliver_type"]').change();

});

$('select[name="product_attr_id"]').change(function(){
    var attrId = $(this).val();
    if(attrId == 1)
    {
        $('#total_price').html('合计:￥' + pageConfig.attr1_price).addClass('btn-bg-f5');
        $('#next_step a').removeClass('btn-bg-9b');
    } else if (attrId == 2 )
    {
        $('#total_price').html('合计￥' + pageConfig.attr2_price).addClass('btn-bg-f5');
        $('#next_step a').removeClass('btn-bg-9b');
    }else
    {
        $('#total_price').html('合计￥' + '00.00').removeClass('btn-bg-f5');
        $('#next_step a').addClass('btn-bg-9b');
    }
});

    $('select[name="product_attr_id"]').change();

new SubmitButton({
    selectorStr:"#next_step",
    url:"/user/report-bill",
    prepositionJudge:function()
    {
//        if(!$('input[name="product_id"]').attr('cus-select-value')){
//            mAlert('请选择购买方式');
//            return false;
//        }


//        if( $('input[name="product_id"]').attr('cus-select-value') == 2)
//        {
//            var quantity = $('input[name="quantity"]').val();
//            if((quantity % 10 != 0) || (quantity/10 < 3) )
//            {
//                mAlert('购买数量必须为10的整数倍，30起购');
//                return false;
//            }
//        }

//        if(!$('input[name="immediate_phone"]').val()){
//            mAlert('请输入直接开发者');
//            return false;
//        }
//
//        if(!$('input[name="deliver_type"]').attr('cus-select-value')){
//            mAlert('请选择收货方式');
//            return false;
//        }
//
//
//        if(!$('input[name="address-show"]').attr('cus-select-value')){
//            mAlert('请选择收货地址');
//            return false;
//        }

        return true;
    },
    callback:function(obj,data){
        if(data.status) {
            location.href = "/user/pay-bill?order_id=" + data.data;
        } else {
            mAlert(data.desc);
        }
    },
    data:function()
    {
        return {product_id:pageConfig.productId,size:serviceTimeVue.size,remark:$('#remark').val(),openid:pageConfig.openid,service_time:$('#clean_service_time').val()};
    }
});

//var addressVue = new Vue({
//    el:'.address-list-vue',
//    data:{showFlag:0,currentValue:0},
//    created:function(){
//        $('.vue-none').removeClass('vue-none');
//    },
//    methods:{
//        bingo:function(event){
//            $dataAddress = $(event.currentTarget).attr('data-address');
//            $('input[name="address-show"]').val($dataAddress);
//            $('input[name="address-show"]').attr('cus-select-value',$(event.currentTarget).attr('data-id'));
//            this.showFlag = 0;
//        },
//        show:function(){
//            this.showFlag = 1;
//        }
//    }
//});

</script>
@stop