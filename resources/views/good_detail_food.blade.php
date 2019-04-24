@extends('_layout.master')
@section('title')
    <title>商品详情</title>
    <script>
        ;(function(designWidth, maxWidth) {
            var doc = document,
                win = window;
            var docEl = doc.documentElement;
            var tid;
            var rootItem,rootStyle;

            function refreshRem() {
                var width = docEl.getBoundingClientRect().width;
                if (!maxWidth) {
                    maxWidth = 540;
                };
                if (width > maxWidth) {
                    width = maxWidth;
                }
                //与淘宝做法不同，直接采用简单的rem换算方法1rem=100px
                var rem = width * 16 / designWidth;
                //兼容UC开始
                rootStyle="html{font-size:"+rem+'px !important}';
                rootItem = document.getElementById('rootsize') || document.createElement("style");
                if(!document.getElementById('rootsize')){
                    document.getElementsByTagName("head")[0].appendChild(rootItem);
                    rootItem.id='rootsize';
                }
                if(rootItem.styleSheet){
                    rootItem.styleSheet.disabled||(rootItem.styleSheet.cssText=rootStyle)
                }else{
                    try{rootItem.innerHTML=rootStyle}catch(f){rootItem.innerText=rootStyle}
                }
                //兼容UC结束
                docEl.style.fontSize = rem + "px";
            };
            refreshRem();

            win.addEventListener("resize", function() {
                clearTimeout(tid); //防止执行两次
                tid = setTimeout(refreshRem, 300);
            }, false);

            win.addEventListener("pageshow", function(e) {
                if (e.persisted) { // 浏览器后退的时候重新计算
                    clearTimeout(tid);
                    tid = setTimeout(refreshRem, 300);
                }
            }, false);

            if (doc.readyState === "complete") {
                doc.body.style.fontSize = "16px";
            } else {
                doc.addEventListener("DOMContentLoaded", function(e) {
                    doc.body.style.fontSize = "16px";
                }, false);
            }
        })(350, 370);
    </script>
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



        .fs-16-fc-212229-m{
            font-family: PingFangSC-Medium;
            font-size: 16px;
            color: #212229;
            line-height: 16px;
        }


        .fs-18-fc-212229-m{
            font-family: PingFangSC-Medium;
            font-size: 18px;
            color: #212229;
            line-height: 18px;
        }

        .op3{opacity: 0.3;}



        .next-icon{
            display: inline-block;
            width: 8px;
            height: 13px;
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACcAAAA/CAYAAABjJtHDAAAAAXNSR0IArs4c6QAAAYdJREFUaAXN2t1tgzAQB/C6L4yRbpIMwAMjdLSOwAZJNknGQEKiXFUsCDbxx/3vzi9gY+GfzrqHw7iPSOu67msYhp+mab77vn9EpkGHP0Nv/4fdpmk6z8Ab9UPz0GPudYEV7LQ8c8495whepCO4wYVgmkCPO4JpAf9wKTANYDAhFkjoOifJSSpJsrZ1jZVIEo+jhXO2l+ajgRucNeAOZwkYxFkBRnEWgIc4beBbnCYwCacFTMZpALNw0sBsnCSwCCcFLMZJAKtwaGA1DglkwaGAbDgEkBXHDWTHcQIhOC4gDMcBhOJqgXBcDVAER8C2bS/jOF7pPqXNZeddBFdaD8NxpTD63AbF1cBo62G4WhgMxwGD4Lhg7DhOGCuOG8aGQ8BYcChYNQ4Jq8KhYcU4CVgRTgqWjZOEZeGkYck4DVgSTgv2FqcJO8Rpw6I4C7Agzgpsh7ME2+CswTzOIoxw2cfo6ANgQi3Nl4Yp0ZOEEdDjqHMElIbtcDGgBiyIewVqwaK4FVD1L7BfS2TUzqpVOAIAAAAASUVORK5CYII=');
            background-size: 8px 13px;
        }


        .prev-icon
        {
            display: inline-block;
            width: 8px;
            height: 13px;
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACcAAAA/CAYAAABjJtHDAAAAAXNSR0IArs4c6QAAAYdJREFUaAXN2t1tgzAQB/C6L4yRbpIMwAMjdLSOwAZJNknGQEKiXFUsCDbxx/3vzi9gY+GfzrqHw7iPSOu67msYhp+mab77vn9EpkGHP0Nv/4fdpmk6z8Ab9UPz0GPudYEV7LQ8c8495whepCO4wYVgmkCPO4JpAf9wKTANYDAhFkjoOifJSSpJsrZ1jZVIEo+jhXO2l+ajgRucNeAOZwkYxFkBRnEWgIc4beBbnCYwCacFTMZpALNw0sBsnCSwCCcFLMZJAKtwaGA1DglkwaGAbDgEkBXHDWTHcQIhOC4gDMcBhOJqgXBcDVAER8C2bS/jOF7pPqXNZeddBFdaD8NxpTD63AbF1cBo62G4WhgMxwGD4Lhg7DhOGCuOG8aGQ8BYcChYNQ4Jq8KhYcU4CVgRTgqWjZOEZeGkYck4DVgSTgv2FqcJO8Rpw6I4C7Agzgpsh7ME2+CswTzOIoxw2cfo6ANgQi3Nl4Yp0ZOEEdDjqHMElIbtcDGgBiyIewVqwaK4FVD1L7BfS2TUzqpVOAIAAAAASUVORK5CYII=');
            background-size: 8px 13px;
            transform: rotate(180deg);
        }


        .barr-line{
            background: #FFFFFF;
            border: 1px solid #E1E1E1;
        }

        .active-type{
            border: 1px solid #C50081 !important;
            background-color: #ffffff !important;
        }

        .chosen{
            background: #C50081;
            border-radius: 16px;
            /*height: 28px;*/
            /*width: 28px;*/
            display: inline-block;
            color: #ffffff !important;
            line-height: 28px !important;
            opacity: 1;
            padding: 0 6px;
            position: relative;
        }

        .begin:after{
            position: absolute;
            content: "起送";
            color:#C50081;
            font-family: PingFangSC-Medium;
            font-size: 12px;
            left: 0;
            right: 0;
            bottom: -22px;
        }

        .cus-row-col-1-7 span{line-height: 22px;}


        .fs-16-fc-080808-r {
            font-family: PingFangSC-Regular;
            font-size: 16px;
            color: #080808;
            letter-spacing: -0.39px;
            text-align: center;
            line-height: 16px;
        }


        .quantity-plus
        {
            font-family: PingFangSC-Medium;
            font-size: 20px;
            color: #212229;
        }

        .quantity-plus-icon
        {
            width: 21px;
            height: 21px;
        }

        .pre-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            word-break: break-all;
        }




        #cal-day{}
        #cal-day .fs-16-fc-212229-m{font-size: 1rem}
        #cal-day .chosen{
            background: #C50081;
            border-radius: 1rem;
            /*height: 28px;*/
            /*width: 28px;*/
            display: inline-block;
            color: #ffffff !important;
            line-height: 1.75rem !important;
            opacity: 1;
            width: 1.81rem;
            position: relative;
        }

        .chosen-tomo{
            width: 3rem !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.css">
@stop
@section('container')
    <div>
        <img src="{{ env('IMAGE_HOST') . $product->cover_image2}}" style="width: 100%;"/>
    </div>

    <div style="padding: 0 16px;">
        <div style="background: #FFFFFF;
    box-shadow: 0 2px 6px 0 #E7E9F0;
    border-radius: 5px;padding:24px;transform: translateY(-24px)">
            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-1 t-a-l">
                    <div class="red-v-l"></div>
                </div>

                <div class="cus-row-col-7">
                    <span class="fs-18-fc-000000-m">{{$product->product_name}}</span>
                </div>

                <div class="cus-row-col-4 t-al-r">
                    <span class="fs-14-fc-c50081-m" style="font-size: 12px;">￥</span><span class="fs-14-fc-c50081-m">{{$product->price}}</span>
                </div>
            </div>

            <div class="fs-14-fc-7E7E7E-r pre-text" style="margin-top: 14px;margin-bottom: 22px;line-height: 18px;">{{$product->food_desc}}</div>


            {{--<iframe src="/passport/good-detail?product_id={{$product->id}}&index=0" frameborder="0" scrolling="no" style="width: 100%"></iframe>--}}


            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-1 t-a-l">
                    <div class="red-v-l"></div>
                </div>
                <div class="cus-row-col-8">
                    <span class="fs-18-fc-000000-m">适宜人群</span>
                </div>
            </div>

            <div class="fs-14-fc-7E7E7E-r pre-text" style="margin-top: 14px;line-height: 18px;">{{$product->fit_indi}}</div>
        </div>

        <div class="cus-row cus-row-v-m" style="margin-bottom: 14px;">
            <div class="cus-row-col-6"><span class="fs-18-fc-000000-m">健康菜单</span></div>
            <div class="cus-row-col-6 t-al-r">
                <span class="fs-14-fc-484848-r">仅显示2周内菜单</span>
            </div>
        </div>

        @if($thisWeek)

        <div  style="overflow: hidden;position: relative;margin-bottom: 16px;padding-left: 84px;box-sizing: border-box" onclick="goMenuDetail(1)">


            <div style="position: absolute;width: 120px;height: 120px;border-radius: 4px;top:18px;left: 0;">
                <img src="{{$thisWeek->cover_img}}" class="slide-image" style="width: 100%;height: 100%;"/>
            </div>


            <div class="info-panel">
                <div class="fs-18-fc-000000-m" style="line-height: 25px;">本周菜单</div>
                <div class="fs-12-fc-7E7E7E-r m-t-10">{{\App\Util\Kit::dateFormat3($thisWeekList[0])}}-{{\App\Util\Kit::dateFormat3($thisWeekList[6])}}</div>
                <div class="fs-12-fc-7E7E7E-r" style="margin-top: 24px;line-height: 18px;"> {{$thisWeek->foods}}</div>
            </div>
        </div>
@endif



        @if($nextWeek)

        <div  style="overflow: hidden;position: relative;margin-bottom: 200px;padding-left: 84px;box-sizing: border-box;" onclick="goMenuDetail(2)">


            <div style="position: absolute;width: 120px;height: 120px;border-radius: 4px;top:18px;left: 0;">
                <img src="{{$nextWeek->cover_img}}" class="slide-image" style="width: 100%;height: 100%;"/>
            </div>


            <div class="info-panel">
                <div class="fs-18-fc-000000-m" style="line-height: 25px;">下周菜单</div>
                <div class="fs-12-fc-7E7E7E-r m-t-10">{{\App\Util\Kit::dateFormat3($nextWeekList[0])}}-{{\App\Util\Kit::dateFormat3($nextWeekList[6])}}</div>
                <div class="fs-12-fc-7E7E7E-r " style="margin-top: 24px;line-height: 18px;"> {{$nextWeek->foods}}</div>
            </div>
        </div>

            @endif

    </div>


    <div style="margin-bottom: 100px;"></div>

    <div id="calder_vue" class="dpn">

    <div style="position: fixed;top:0;bottom: 0;left: 0;right: 0;background-color: rgba(28,36,75,0.80);z-index:9999" id="calder" v-if="calderSwitch">
        <div style="position: absolute;left:0;bottom: 0;right: 0;">


            <div style="padding-left: 16px;margin-bottom: 16px;">
                <img src="/images/icon_close3_nor@3x.png" style="width: 24px;height: 24px;" v-on:click="closeCalderSwitch">
            </div>



            <div style="padding: 26px 16px 6px; background-color: #ffffff;border-top-left-radius: 16px;border-top-right-radius: 16px;">


                <div class="cus-row ">
                    <div class="cus-row-col-6">
                    <span class="fs-18-fc-212229-m">订餐份数</span><span style="margin-left: 16px;" class="fs-14-fc-7E7E7E-r">1份餐仅供1人</span>
                    </div>

                    <div class="cus-row-col-6 t-al-r">
                        <div class="in-bl v-a-m quantity-plus-icon" v-on:click="deQuantity"><image src="/images/icon_out_nor@3x.png" class="quantity-plus-icon"/></div>
                        <div class="in-bl v-a-m" style="margin: 0 30px;"><div class="quantity-plus">    @{{quantity}}    </div></div>
                        <div class="in-bl v-a-m quantity-plus-icon" v-on:click="addQuantity"><image src="/images/icon_add_nor@3x.png" class="quantity-plus-icon"/></div>
                    </div>
                </div>

                <div style="margin:26px 0;border: .5px solid #e1e1e1;"></div>

                <div style="">
                    <span class="fs-18-fc-212229-m">预定时间</span><span style="margin-left: 16px;" class="fs-14-fc-7E7E7E-r">单次预定限七日内 节假日暂不供应</span>
                </div>

                <div class="t-al-c" style="font-size: 0;margin-top: 26px;">
                    <div class="in-bl" style="background: #F9F9FB;border: 1px solid #E1E1E1;border-radius: 17px 0px 0px 17px;" v-bind:class="{ 'active-type': (tabIndex == 1) }" v-on:click="setTab(1)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">单次</span></div>
                    <div class="in-bl" style="background: #F9F9FB;border-top: 1px solid #E1E1E1;border-bottom: 1px solid #E1E1E1;" v-bind:class="{ 'active-type': (tabIndex == 2) }" v-on:click="setTab(2)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">周(7日)</span></div>
                    <div class="in-bl" style="background: #F9F9FB;border: 1px solid #E1E1E1;border-radius: 0px 17px 17px 0px;" v-bind:class="{ 'active-type': (tabIndex == 3) }" v-on:click="setTab(3)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">月(30日)</span></div>
                </div>

                <div class="t-al-c" style="margin-top: 40px;">
                    <div class="cus-row">
                        <div class="cus-row-col-3 v-a-m t-al-l">
                            <span class="fs-16-fc-212229-m op3">@{{prveMonth}}</span>
                        </div>
                        <div class="cus-row-col-6 v-a-m">
                            <div class="cus-row">
                                <div class="cus-row-col-2 t-al-l" v-on:click="monthGo(-1)">
                                    <i class="prev-icon"></i>
                                </div>
                                <div class="cus-row-col-8">
                                    <span class="fs-16-fc-212229-m">@{{currentMonth}}</span>
                                </div>
                                <div class="cus-row-col-2 t-al-r" v-on:click="monthGo(1)">
                                    <i class="next-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="cus-row-col-3 v-a-m t-al-r">
                            <span class="fs-16-fc-212229-m op3">@{{nextMonth}}</span>
                        </div>
                    </div>
                    <div class="cus-row" style="margin-top: 22px;">
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m">日</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m">一</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m">二</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m">三</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m">四</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m">五</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m">六</span></div>
                    </div>

                    <div style="height: 180px;overflow: scroll;" id="cal-day">
                    <div class="cus-row cus-row-v-m" v-for="(ind,item) in lines" style="margin-top: 22px;">
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m" v-bind:class="{'op3':data[item][0].forbiddenChosen,'chosen':data[item][0].chosen,begin:(beginStr == data[item][0].ymd),'chosen-tomo':(data[item][0].chosen && (calCurrent && (data[item][0].day == currentDate)))}" v-on:click="setBegin(data[item][0].day)">@{{(calCurrent && (data[item][0].day == currentDate))?'明天':data[item][0].day}}</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][1].forbiddenChosen,'chosen':data[item][1].chosen,begin:(beginStr == data[item][1].ymd),'chosen-tomo':(data[item][1].chosen && (calCurrent && (data[item][1].day == currentDate)))}" v-on:click="setBegin(data[item][1].day)">@{{(calCurrent && (data[item][1].day == currentDate))?'明天':data[item][1].day}}</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][2].forbiddenChosen,'chosen':data[item][2].chosen,begin:(beginStr == data[item][2].ymd),'chosen-tomo':(data[item][2].chosen && (calCurrent && (data[item][2].day == currentDate)))}" v-on:click="setBegin(data[item][2].day)">@{{(calCurrent && (data[item][2].day == currentDate))?'明天':data[item][2].day}}</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][3].forbiddenChosen,'chosen':data[item][3].chosen,begin:(beginStr == data[item][3].ymd),'chosen-tomo':(data[item][3].chosen && (calCurrent && (data[item][3].day == currentDate)))}" v-on:click="setBegin(data[item][3].day)">@{{(calCurrent && (data[item][3].day == currentDate))?'明天':data[item][3].day}}</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][4].forbiddenChosen,'chosen':data[item][4].chosen,begin:(beginStr == data[item][4].ymd),'chosen-tomo':(data[item][4].chosen && (calCurrent && (data[item][4].day == currentDate)))}" v-on:click="setBegin(data[item][4].day)">@{{(calCurrent && (data[item][4].day == currentDate))?'明天':data[item][4].day}}</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][5].forbiddenChosen,'chosen':data[item][5].chosen,begin:(beginStr == data[item][5].ymd),'chosen-tomo':(data[item][5].chosen && (calCurrent && (data[item][5].day == currentDate)))}" v-on:click="setBegin(data[item][5].day)">@{{(calCurrent && (data[item][5].day == currentDate))?'明天':data[item][5].day}}</span></div>
                        <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m" v-bind:class="{'op3':data[item][6].forbiddenChosen,'chosen':data[item][6].chosen,begin:(beginStr == data[item][6].ymd) ,'chosen-tomo':(data[item][6].chosen && (calCurrent && (data[item][6].day == currentDate)))}" v-on:click="setBegin(data[item][6].day)">@{{(calCurrent && (data[item][6].day == currentDate))?'明天':data[item][6].day}}</span></div>
                    </div>

                    </div>
                </div>

                <div style="margin-top: 26px;">
                    <a class="yl_btn1 m-t-20" v-on:click="setChosenDay" style="margin-top: 0;display: block;" v-if="canSend">@{{confirmText}}</a>
                    <a class="yl_btn1 m-t-20 btn-gray" style="margin-top: 0;display: block;" v-else>请选择起送日期</a>
                </div>
            </div>
        </div>
    </div>

    </div>

    <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
        <a class="yl_btn1 m-t-20" href="javascript:buy()" style="margin-top: 0;display: block;">购买</a>
    </footer>

@stop

@section('script')
    <script src="/js/vue.js"></script>
    <script src="/js/underscore.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.js"></script>
    <script type="text/javascript">

        var pageConfig = {
            product_id: {{$product->id}},
            openid:"{{\Illuminate\Support\Facades\Request::input('openid')}}",
            thisWeekList:'{{    implode(',',$thisWeekList) }}',
            nextWeekList:'{{    implode(',',$nextWeekList) }}'
        }

        function buy(){
            calderVue.openCalderSwitch();
        }


        function goMenuDetail(type)
        {
            if( type == 1)
            {
                // console.log();
                location.href = "/passport/menu?product_id="+pageConfig.product_id+"&dates=" + pageConfig.thisWeekList;
            } else
            {
                location.href = "/passport/menu?product_id="+pageConfig.product_id+"&dates=" + pageConfig.nextWeekList;
            }
        }



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





       var calderVue = new Vue({
           el:"#calder_vue",
           data:{
               quantity:1,
               year:2019,
               month:1,
               data:[
                   [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                   [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                   [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                   [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                   [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                   [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}]
               ],
               s:'12',
               lines:[0,1,2,3,4,5],
               tabIndex:1,
               currentDay:'',
               currentDate:'',
               startDay:'',
               calderSwitch:false,
               chosenDay:'',
               chosenType:''
           },
           created:function(){

               $('.dpn').removeClass('dpn');

               let fullDay = new Date(this.year,this.month,0).getDate();
               let startWeek = new Date(this.year,this.month - 1,1).getDay();
               this.currentDay = new Date();
               // this.startDay = this.currentDay;
               this.chosenDay = this.startDay;


               this.year = this.currentDay.getFullYear();
               this.month = this.currentDay.getMonth() + 1;
               this.currentDate = this.currentDay.getDate() + 1;

               // console.log('currentDate:' + this.currentDate);

               this.updateCalder();
           },
           methods:
               {
                   closeCalderSwitch:function()
                   {
                       this.calderSwitch = false;
                   },
                   openCalderSwitch:function()
                   {
                       this.calderSwitch = true;
                   },
                   deQuantity:function () {
                       if( this.quantity > 1)
                       {
                           this.quantity = this.quantity - 1;
                       }
                   },
                   addQuantity:function(){
                       this.quantity = this.quantity + 1;
                   },
                   setBegin:function(day)
                   {
                       let tmpStartDay = new Date(this.year,this.month - 1,day);

                       let startWeek = new Date(this.year,this.month - 1,1).getDay();
                       day = day - 1;
                       let mo  = parseInt((startWeek + day) / 7);
                       let mod = (day+startWeek)%7;

                       if ( this.data[mo][mod].forbiddenChosen )
                       {
                           return;
                       }


                       if ( tmpStartDay == this.startDay)
                       {
                           return;
                       }



                       for(let i = 0 ;i < 35;i++)
                       {
                           let mo2  = parseInt(i / 7);
                           let mod2 = i % 7;

                           if (this.data[mo2][mod2].chosen)
                           {
                               this.data[mo2][mod2].chosen = false;
                           }
                       }

                       this.startDay = tmpStartDay;
                       this.updateCalder()
                   },
                   setTab:function(index){
                       this.tabIndex = index;

                       this.updateCalder();
                   },
                   getDateArr:function(startDay,n)
                   {
                       let conFlag = true;
                       let arr = [startDay.toString()];
                       let beginDay = new Date(startDay.getFullYear(),startDay.getMonth(),startDay.getDate());
                       while(conFlag)
                       {
                           //
                           beginDay = new Date(beginDay.getFullYear(),beginDay.getMonth(),beginDay.getDate() + 1);
                           // if( _.indexOf([0,6],beginDay.getDay()) !== -1 )
                           // {
                           //     continue;
                           // }

                           arr.push(beginDay.toString());

                           if( arr.length > (n - 1))
                           {
                               conFlag = false;
                           }
                       }
                       return arr;
                   },
                   monthGo:function(direction)
                   {
                       let currTmp = new Date(this.year,this.month - 1 + direction,1);
                       this.year = currTmp.getFullYear();
                       this.month = currTmp.getMonth() + 1;
//                console.log(currTmp.getFullYear());
//                console.log(currTmp.getMonth + 1);

                       this.updateCalder();

                   },
                   updateCalder:function()
                   {
                       let fullDay = new Date(this.year,this.month,0).getDate();
                       let startWeek = new Date(this.year,this.month - 1,1).getDay();

                       // console.log(fullDay);
                       // console.log(startWeek);

                       let tmpData =  [
                           [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                           [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                           [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                           [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                           [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                           [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}]

                       ];

                       for(let i = 0;i<fullDay;i++)
                       {
                           let mo  = parseInt((i+startWeek) / 7);
                           let mod = (i+startWeek)%7;

                           // console.log(mo);
                           // console.log(mod);

                           //周六、周日不可点击
                           tmpData[mo][mod].day = i+1;
                           tmpData[mo][mod].ymd = this.year + '-' + (this.month) + '-' + (i+1);

                           // if(mod == 0 || mod == 6 || (new Date(this.year,this.month - 1,i + 1) < this.currentDay))
                           // {
                           //     tmpData[mo][mod].forbiddenChosen = true;
                           // }


                           if((new Date(this.year,this.month - 1,i + 1) < this.currentDay))
                           {
                               tmpData[mo][mod].forbiddenChosen = true;
                           }

                           //设置为选中
                           // console.log('選中的日期');
                           // console.log(this.chosenDays);
                           if( _.indexOf(this.chosenDays, new Date(this.year,this.month - 1,i + 1).toString()) !== -1 ) {
                               tmpData[mo][mod].chosen = true;
                           }
                       }

                       this.data = tmpData;
                       console.log('渲染日历');
                       console.log(JSON.stringify(this.data));
                       this.$forceUpdate();

                   },
                   setChosenDay:function () {

                       // this.chosenDay = this.startDay;
                       // this.chosenType = this.tabIndex;
                       // this.closeCalderSwitch();

                       var url = "/pages/fillfoodbill/main?product_id=" + pageConfig.product_id +"&openid=" + pageConfig.openid + '&tabIndex=' + this.tabIndex + '&quantity=' + this.quantity + '&startDay=' + this.startDay.Format('yyyy-MM-dd');
                       console.log(url);
                       wx.miniProgram.navigateTo(
                           {
                               url: url
                           });
                   },
               },
           computed:
               {
                   prveMonth:function()
                   {
//                        return (this.month - 1) + '月';
                       return (new Date(this.year,this.month - 2).getMonth() + 1) + '月';
                   },
                   currentMonth:function()
                   {
                       return this.year + '年' + this.month + '月';
                   },
                   nextMonth:function()
                   {
                       return (new Date(this.year,this.month).getMonth() + 1) + '月';
                   },
                   canSend:function()
                   {
                       if( this.startDay )
                       {
                           return true;
                       } else {
                           return false;
                       }
                   },
                   chosenDays:function()
                   {
                       if ( this.startDay > this.currentDay )
                       {
                           if( this.tabIndex == 1 )
                           {
                               return [this.startDay.toString()];
                           } else if( this.tabIndex == 2)
                           {
                               return this.getDateArr(this.startDay,7);
                           } else {
                               return this.getDateArr(this.startDay,30);
                           }
                       } else
                       {
                           return [];
                       }
                   },
                   confirmText:function()
                   {
                       var daysArr = [1,7,30]
                       return this.quantity + '人份 ' + daysArr[this.tabIndex - 1] +'天';
                   },
                   calCurrent:function()
                   {
                       // return this.year + '年' + this.month + '月';
                      if( (this.year == this.currentDay.getFullYear()) && (this.month == this.currentDay.getMonth() + 1) )
                      {
                          return true;
                      } else
                      {
                          return false;
                      }
                   },
                   beginStr:function()
                   {
                       if( this.startDay )
                       {
                           console.log('起送日期:' + this.startDay.Format('yyyy-M-d'));
                           return this.startDay.Format('yyyy-M-d');
                       }else
                       {
                           return '';
                       }
                   }
               }

       });
    </script>
@stop