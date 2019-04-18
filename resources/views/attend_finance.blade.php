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
            border-radius: 14px;
            height: 28px;
            width: 28px;
            display: inline-block;
            color: #ffffff !important;
            line-height: 28px !important;
            opacity: 1;
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

        .time-list .time-item:nth-child(2n)
        {
            padding-left:8px;
        }

        .time-list .time-item:nth-child(2n-1)
        {
            padding-right: 8px;
        }

        .time-item-box
        {
            width: 100%;
            background: #FFFFFF;
            border: 1px solid #F3F3F3;
            border-radius: 4px;
            padding-top: 16px;
            padding-bottom: 16px;
            margin-bottom: 16px;
            position: relative;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.css">
@stop
@section('container')
    <div style="padding: 16px;">
        <div>
            <img src="{{ env('IMAGE_HOST') . $product->cover_image}}" style="width: 100%;"/>
        </div>


        <div class="common-panel-24-16">
            <div class="fs-18-fc-000000-m">花甲金融顾问 ：张志国</div>
            <div class="fs-14-fc-7E7E7E-r m-t-16">
                高级经济师，毕业于南开大学国际金融专业，研究生学历，经济学硕士。现任中国中信集团有限公司执行董事。
            </div>
        </div>

        <div class="fs-14-fc-7E7E7E-r m-t-24">花甲联合各家专业机构为会员提供专业的金融资讯建议，建立完备的社区金融服务。充分为老人梳理完备的金融知识建立正确的金融价值观。
        </div>

        <div class="fs-14-fc-000000-m m-t-16">服务地点:{{$product->context_deliver}}</div>
        <div class="fs-14-fc-000000-m" style="margin-top: 6px;">服务时间:{{\App\Util\Kit::dateFormat4($product->start_time)}}  {{\App\Util\Kit::dateFormatHi($product->start_time)}}-{{\App\Util\Kit::dateFormatHi($product->end_time)}}</div>

    </div>

    <div style="margin-bottom: 100px;"></div>


    <div id="calder_vue">

        <div style="position: fixed;top:0;bottom: 0;left: 0;right: 0;background-color: rgba(28,36,75,0.80);z-index:9999" id="calder" v-if="calderSwitch">
            <div style="position: absolute;left:0;bottom: 0;right: 0;">


                <div style="padding-left: 16px;margin-bottom: 16px;">
                    <img src="/images/icon_close3_nor@3x.png" style="width: 24px;height: 24px;" v-on:click="closeCalderSwitch">
                </div>



                <div style="padding: 26px 16px 6px; background-color: #ffffff;border-top-left-radius: 16px;border-top-right-radius: 16px;">


                    <div class="cus-row ">
                        <div class="cus-row-col-6">
                            <span class="fs-18-fc-212229-m">预约时间</span>
                        </div>

                    </div>

                    <div class="time-list" style="margin-top: 24px;">
                        <div class="in-bl t-al-c time-item" style="width: 50%;" v-for="(item,index) in timeList">
                            <div class="fs-18-fc-000000-m time-item-box" v-on:click="setTab(index)">@{{item.text}}

                                <div style="width: 0;height: 0;border-style: solid;border-width: 24px 24px 0 0;border-color: #CE388E transparent transparent transparent;position: absolute;top:0;left: 0;"  v-if="tabIndex == index"></div>

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

    @if($booked)
        <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
            <a class="yl_btn1 m-t-20 btn-gray" style="margin-top: 0;display: block;">已预约</a>
        </footer>
        @else
    <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
        <a class="yl_btn1 m-t-20" id="next_step" style="margin-top: 0;display: block;">立即预约</a>
    </footer>
    @endif

@stop

@section('script')
    <script src="/js/vue.js"></script>
    <script src="/js/underscore.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.js"></script>
    <script type="text/javascript">

        var pageConfig = {
            product_id: {{$product->id}},
            user_id:'{{\Illuminate\Support\Facades\Request::input('user_id')}}',
            timeList:{!! json_encode($timeList) !!}
        }




        $('#next_step').click(function(){
            calderVue.openCalderSwitch();
        });

        // $(function () {
        //     new SubmitButton({
        //         selectorStr:"#next_step",
        //         url:'/index/book-finance',
        //         data:function()
        //         {
        //             return {product_id:pageConfig.product_id,user_id:pageConfig.user_id};
        //         },
        //         callback:function(el,data)
        //         {
        //             $('#next_step').remove();
        //             $('footer').append('<a class="yl_btn1 m-t-20 btn-gray" style="margin-top: 0;display: block;">已预约</a>');
        //         }
        //     });
        // });





        var calderVue = new Vue({
            el:"#calder_vue",
            data:{
                tabIndex:-1,
                currentDay:'',
                startDay:'',
                calderSwitch:false,
                chosenDay:'',
                chosenType:'',
                timeList:pageConfig.timeList
            },
            created:function(){
                $('.dpn').removeClass('dpn');
            },
            methods:
                {
                    setTab:function(index){
                        this.tabIndex = index;
                    },
                    openCalderSwitch(){
                        this.calderSwitch = true;
                    },
                    closeCalderSwitch(){
                        this.calderSwitch = false;
                    }
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
                                return this.getDateArr(this.startDay,5);
                            } else {
                                return this.getDateArr(this.startDay,22);
                            }
                        } else
                        {
                            return [];
                        }
                    },
                    confirmText:function()
                    {
                        var daysArr = [1,5,22]
                        return this.quantity + '人份 ' + daysArr[this.tabIndex - 1] +'天';
                    }
                }

        });
    </script>
@stop