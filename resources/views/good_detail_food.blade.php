@extends('_layout.master')
@section('title')
    <title>商品详情</title>
@stop
@section('style')
    <style>
        html,body{
            background-color: #f9f9fb;
        }
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

        .red-v-l
        {
            height: 16px;
            border-left: 4px solid #C50081;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.css">
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'辣木膳购买系统'])--}}



    <!--轮播-->
    {{--<div class="padding-container">--}}

        {{--<div><span class="fs-26-fc-black">日常保洁</span></div>--}}

        {{--<div class="swiper-container">--}}
            {{--<div class="swiper-wrapper">--}}
                {{--<div class="swiper-slide">--}}
                    {{--<img src="http://graphis.zhuyan.me/1.jpg"/>--}}
                {{--</div>--}}
                {{--<div class="swiper-slide">--}}
                    {{--<img src="http://graphis.zhuyan.me/2.jpg"/></div>--}}
                {{--<div class="swiper-slide">--}}
                    {{--<img src="http://graphis.zhuyan.me/2.jpg"/>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<!-- 如果需要分页器 -->--}}
            {{--<div class="swiper-pagination"></div>--}}

            {{--<!-- 如果需要导航按钮 -->--}}
        {{--<div class="swiper-button-prev"></div>--}}
        {{--<div class="swiper-button-next"></div>--}}

        {{--<!-- 如果需要滚动条 -->--}}
            {{--<div class="swiper-scrollbar"></div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div>
        <img src="{{ env('IMAGE_HOST') . $product->cover_image}}" style="width: 100%;"/>
    </div>

    <div style="padding: 0 16px;">
        <div style="background: #FFFFFF;
    box-shadow: 0 2px 6px 0 #E7E9F0;
    border-radius: 5px;padding:24px;transform: translateY(-24px)">
            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-1 t-a-l">
                    <div class="red-v-l"></div>
                </div>
                <div class="cus-row-col-8">
                    <span class="fs-18-fc-000000-m">食谱简介</span>
                </div>
                <div class="cus-row-col-3 t-al-r">

                </div>
            </div>


            <iframe src="/passport/good-detail?product_id={{$product->id}}&index=0" frameborder="0" scrolling="no" style="width: 100%"></iframe>


            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-1 t-a-l">
                    <div class="red-v-l"></div>
                </div>
                <div class="cus-row-col-8">
                    <span class="fs-18-fc-000000-m">适宜人群</span>
                </div>
                <div class="cus-row-col-3 t-al-r">

                </div>
            </div>
        </div>



        <div  style="overflow: hidden;position: relative;margin-bottom: 16px;padding-left: 84px;box-sizing: border-box">


            <div style="position: absolute;width: 120px;height: 120px;border-radius: 4px;top:18px;left: 0;">
                <img src="" class="slide-image" style="width: 100%;height: 100%;"/>
            </div>


            <div class="info-panel">
                <div class="fs-18-fc-000000-m" style="line-height: 25px;">本周菜单</div>
                <div class="fs-12-fc-7E7E7E-r">1月14日-1月18日</div>
                <div>￥18.5</div>
            </div>
        </div>




        <div  style="overflow: hidden;position: relative;margin-bottom: 200px;padding-left: 84px;box-sizing: border-box;">


            <div style="position: absolute;width: 120px;height: 120px;border-radius: 4px;top:18px;left: 0;">
                <img src="" class="slide-image" style="width: 100%;height: 100%;"/>
            </div>


            <div class="info-panel">
                <div class="fs-18-fc-000000-m" style="line-height: 25px;">下周菜单</div>
                <div class="fs-12-fc-7E7E7E-r">1月14日-1月18日</div>
                <div>￥18.5</div>
            </div>
        </div>

    </div>



    <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
        <a class="yl_btn1 m-t-20" href="javascript:buy()" style="margin-top: 0;display: block;">购买</a>
    </footer>

@stop

@section('script')
    <script src="/js/vue.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.js"></script>
    <script type="text/javascript">

        var pageConfig = {
            product_id: {{$product->id}},
            openid:"{{\Illuminate\Support\Facades\Request::input('openid')}}"
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



        function buy(){
            wx.miniProgram.navigateTo(
                {
                    url: "/pages/fillfoodbill/main?product_id=" + pageConfig.product_id +"&openid=" + pageConfig.openid
                });
        }

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





//        new Vue({
//            el:".info-vue",
//            data:{tabIndex:1},
//            methods:{
//                setTab:function(index){
//                    this.tabIndex = index;
//                }
//            }
//        });
    </script>
@stop