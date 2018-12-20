@extends('_layout.master')
@section('title')
    <title>商品详情</title>
@stop
@section('style')
    <style>
        .low-alert{position: fixed;left:0;right: 0;bottom: 90px;text-align: center;}
        .item-opr span{line-height: 40px;display: inline-block;}
        .show-img{width: 100%;border-radius: 12px;}
        .pro-essay-barr{border-bottom: 1px solid #9c9c9c;margin: 20px 0;}


        .active-tab{font-weight: bold;position: relative;}


        .active-tab:after{
            border-bottom:solid 4px #98CC3D;
            position: absolute;
            right: 0;
            left: 0;
            content:'';
            display: block;
            top:22px;
        }



        .active-iframe{
            display: block !important;
        }

        .btn3{background-image: linear-gradient(-137deg, #B9E77D 0%, #78CD09 50%);  box-shadow: 0 8px 16px 0 rgba(139,217,75,0.46);border-radius: 44px;line-height: 44px;font-size: 16px;color:#ffffff;font-weight: 800;text-align: center;}

        .btn3:hover{color:#ffffff;}

        .swiper-container{width: 100%;}
        .swiper-slide img{width: 100%;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.css">
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'辣木膳购买系统'])--}}



    <!--轮播-->
    <div class="padding-container">

        <div><span class="fs-26-fc-black">日常保洁</span></div>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="http://graphis.zhuyan.me/1.jpg"/>
                </div>
                <div class="swiper-slide">
                    <img src="http://graphis.zhuyan.me/2.jpg"/></div>
                <div class="swiper-slide">
                    <img src="http://graphis.zhuyan.me/2.jpg"/>
                </div>
            </div>
            <!-- 如果需要分页器 -->
        <div class="swiper-pagination"></div>

        <!-- 如果需要导航按钮 -->
        {{--<div class="swiper-button-prev"></div>--}}
        {{--<div class="swiper-button-next"></div>--}}

        <!-- 如果需要滚动条 -->
            {{--<div class="swiper-scrollbar"></div>--}}
        </div>
    </div>


    <div class="info-vue" style="margin-top: 16px;">

        <div style="border-bottom:solid 1px #e9e9e9;margin-top: 10px;"></div>

        <div class="padding-container">



            <div class="">
                <div class="cus-row"></div>
                <div>

                </div>
            </div>


            <div>
                <div class="cus-row">
                    <div class="cus-row-col-6">
                        <div class="in-bl-v-m"><i class="agree-icon"></i></div>
                        <div class="in-bl-v-m fs-16-fc-030303">服务价格</div>
                    </div>
                    <div class="cus-row-col-6 t-al-r fs-16-fc-f89a03">按照平方计算</div>
                </div>
            </div>

            {{--<div class="product-list">--}}
            {{--<div class="product-item">--}}
            {{--<div class="item-info">--}}
            {{--<img class="show-img" src="{{$product->cover_image}}"/>--}}
            {{--</div>--}}
            {{--<div class="item-opr"><span>{{$product->product_name}}</span></div>--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--<div style="padding: 0 10px;"><div class="pro-essay-barr"></div></div>--}}
            <div>服务介绍</div>

            <iframe src="/passport/good-detail?product_id={{$product->id}}&index=0" frameborder="0" scrolling="no" id="test" onload="this.height=100" style="width: 100%;margin-bottom: 50px;display: none;" v-bind:class="{ 'active-iframe': (tabIndex == 1) }"></iframe>

            <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">

                <a class="btn3 m-t-20" href="javascript:buy()" style="margin-top: 0;display: block;">购买</a>
                {{--<div class="mui-row" style="line-height: 40px;">--}}
                {{--<a class="mui-col-sm-2 mui-col-xs-2 btn-block2" href="tel:{{$product->consumer_service_phone}}">客服</a>--}}
                {{--<a class="mui-col-sm-10 mui-col-xs-10 btn-block1 remove-radius" href="/user/report-bill?product_id={{$product->id}}">立即下单</a>--}}
                {{--</div>--}}
            </footer>
        </div>

    </div>
@stop

@section('script')
    <script src="/js/vue.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.js"></script>
    <script type="text/javascript">

        var pageConfig = {
            product_id: {{\Illuminate\Support\Facades\Request::input('product_id',1)}},
            openid:{{\Illuminate\Support\Facades\Request::input('openid')}}
        }

        function reinitIframe(){
            var iframe = document.getElementById("test");
            try{
//                var bHeight = iframe.contentWindow.document.body.scrollHeight;
//                var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
//                var height = Math.max(bHeight, dHeight);
                iframe.height = iframe.contentWindow.document.body.clientHeight;
//                console.log(height);
            }catch (ex){}

            var iframe = document.getElementById("test1");
            try{
//                var bHeight = iframe.contentWindow.document.body.scrollHeight;
//                console.log(iframe.contentWindow.document.body.clientHeight);
//                var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
//                console.log(bHeight,dHeight);
//                var height = Math.max(bHeight, dHeight);
                iframe.height = iframe.contentWindow.document.body.clientHeight;
//                console.log(height);
            }catch (ex){}


            var iframe = document.getElementById("test2");
            try{
//                var bHeight = iframe.contentWindow.document.body.scrollHeight;
//                var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
//                var height = Math.max(bHeight, dHeight);
                iframe.height = iframe.contentWindow.document.body.clientHeight;
//                console.log(height);
            }catch (ex){}
        }
        window.setInterval("reinitIframe()", 200);
   



        var mySwiper = new Swiper ('.swiper-container', {
//            direction: 'vertical', // 垂直切换选项
            loop: true, // 循环模式选项

            // 如果需要分页器
            pagination: {
                el: '.swiper-pagination',
            },

//            // 如果需要前进后退按钮
//            navigation: {
//                nextEl: '.swiper-button-next',
//                prevEl: '.swiper-button-prev',
//            },
//
//            // 如果需要滚动条
//            scrollbar: {
//                el: '.swiper-scrollbar',
//            },
        })



        $(function () {
            new SubmitButton({
                selectorStr:"#next_step",
                url:'/passport/login',
                data:function()
                {
                    return $('#data_form').serialize();
                },
                redirectTo:'/index'
            });
        });

        function buy(){
            wx.miniProgram.navigateTo(
                {
                    url: "/pages/fillbill/main?product_id=" + pageConfig.product_id +"&openid=" + pageConfig.openid
                });
        }




        new Vue({
            el:".info-vue",
            data:{tabIndex:1},
            methods:{
                setTab:function(index){
                    this.tabIndex = index;
                }
            }
        });
    </script>
@stop