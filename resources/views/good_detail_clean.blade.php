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


        .pre-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            word-break: break-all;
        }

        @if( \Illuminate\Support\Facades\Request::input('isIpx'))
            .fix-bottom
        {
            padding-bottom:48px !important;;
        }
        @endif
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
                <div class="cus-row-col-8">
                    <span class="fs-18-fc-000000-m">{{$product->product_name}}</span>
                </div>
                <div class="cus-row-col-3 t-al-r">

                </div>
            </div>


            <div class="fs-14-fc-7E7E7E-r pre-text"   style="margin-top: 14px;line-height: 18px;margin-bottom: 22px;">{{$product->context}}</div>





            {{--<iframe src="/passport/good-detail?product_id={{$product->id}}&index=0" frameborder="0" scrolling="no" style="width: 100%"></iframe>--}}


            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-1 t-a-l">
                    <div class="red-v-l"></div>
                </div>
                <div class="cus-row-col-8">
                    <span class="fs-18-fc-000000-m">资费说明</span>
                </div>
                <div class="cus-row-col-3 t-al-r">

                </div>
            </div>

            <div class="fs-14-fc-7E7E7E-r pre-text" style="margin-top: 14px;line-height: 18px;margin-bottom: 22px;">{{$product->context_deliver}}</div>

        </div>


        <div class="m-t-16" style="background: #FFFFFF;
    box-shadow: 0 2px 6px 0 #E7E9F0;
    border-radius: 5px;padding:24px;transform: translateY(-24px)">
            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-1 t-a-l">
                    <div class="red-v-l"></div>
                </div>
                <div class="cus-row-col-8">
                    <span class="fs-18-fc-000000-m">服务范围及标准</span>
                </div>
                <div class="cus-row-col-3 t-al-r">

                </div>
            </div>

            <div class="fs-14-fc-7E7E7E-r pre-text" style="margin-top: 14px;line-height: 18px;margin-bottom: 22px;">{{$product->context_server}}</div>



            {{--<iframe src="/passport/good-detail?product_id={{$product->id}}&index=0" frameborder="0" scrolling="no" style="width: 100%"></iframe>--}}
        </div>


        {{--<div class="m-t-24" style="background: #FFFFFF;--}}
    {{--box-shadow: 0 2px 6px 0 #E7E9F0;--}}
    {{--border-radius: 5px;padding:24px;transform: translateY(-24px);" onclick="commonQue()">--}}
            {{--<div class="cus-row cus-row-v-m">--}}
                {{--<div class="cus-row-col-1 t-a-l">--}}
                    {{--<div class="red-v-l"></div>--}}
                {{--</div>--}}
                {{--<div class="cus-row-col-8">--}}
                    {{--<span class="fs-18-fc-000000-m">常见问题</span>--}}
                {{--</div>--}}
                {{--<div class="cus-row-col-3 t-al-r">--}}
                    {{--<span class="next-icon"></span>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<iframe src="/passport/good-detail?product_id={{$product->id}}&index=0" frameborder="0" scrolling="no" style="width: 100%"></iframe>--}}
        {{--</div>--}}

        <div style="margin-bottom: 100px;"></div>

    </div>



    <div id="calder_vue" style="display: none;">

        <div style="position: fixed;top:0;bottom: 0;left: 0;right: 0;background-color: rgba(28,36,75,0.80);z-index:9999" id="calder" v-if="calderSwitch">
            <div style="position: absolute;left:0;bottom: 0;right: 0;">


                <div style="padding-left: 16px;margin-bottom: 16px;">
                    <img src="/images/icon_unsuess_nor@3x.png" style="width: 24px;height: 24px;" v-on:click="closeCalderSwitch">
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

                    <div style="margin:26px 0;border: 1px solid #e1e1e1;"></div>

                    <div style="">
                        <span class="fs-18-fc-212229-m">预定时间</span><span style="margin-left: 16px;" class="fs-14-fc-7E7E7E-r">单次预定限五天内 节假日暂不供应</span>
                    </div>

                    <div class="t-al-c" style="font-size: 0;margin-top: 26px;">
                        <div class="in-bl" style="background: #F9F9FB;border: 1px solid #E1E1E1;border-radius: 17px 0px 0px 17px;" v-bind:class="{ 'active-type': (tabIndex == 1) }" v-on:click="setTab(1)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">单次</span></div>
                        <div class="in-bl" style="background: #F9F9FB;border-top: 1px solid #E1E1E1;border-bottom: 1px solid #E1E1E1;" v-bind:class="{ 'active-type': (tabIndex == 2) }" v-on:click="setTab(2)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">按周</span></div>
                        <div class="in-bl" style="background: #F9F9FB;border: 1px solid #E1E1E1;border-radius: 0px 17px 17px 0px;" v-bind:class="{ 'active-type': (tabIndex == 3) }" v-on:click="setTab(3)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">按月</span></div>
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

                        <div class="cus-row cus-row-v-m" v-for="(ind,item) in lines" style="margin-top: 22px;">
                            <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m" v-bind:class="{'op3':data[item][0].forbiddenChosen,'chosen':data[item][0].chosen}" v-on:click="setBegin(data[item][0].day)">@{{data[item][0].day}}</span></div>
                            <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][1].forbiddenChosen,'chosen':data[item][1].chosen}" v-on:click="setBegin(data[item][1].day)">@{{data[item][1].day}}</span></div>
                            <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][2].forbiddenChosen,'chosen':data[item][2].chosen}" v-on:click="setBegin(data[item][2].day)">@{{data[item][2].day}}</span></div>
                            <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][3].forbiddenChosen,'chosen':data[item][3].chosen}" v-on:click="setBegin(data[item][3].day)">@{{data[item][3].day}}</span></div>
                            <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][4].forbiddenChosen,'chosen':data[item][4].chosen}" v-on:click="setBegin(data[item][4].day)">@{{data[item][4].day}}</span></div>
                            <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m"  v-bind:class="{'op3':data[item][5].forbiddenChosen,'chosen':data[item][5].chosen}" v-on:click="setBegin(data[item][5].day)">@{{data[item][5].day}}</span></div>
                            <div class="cus-row-col-1-7"><span class="fs-16-fc-212229-m" v-bind:class="{'op3':data[item][6].forbiddenChosen,'chosen':data[item][6].chosen}" v-on:click="setBegin(data[item][6].day)">@{{data[item][6].day}}</span></div>
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
        <a class="yl_btn1 m-t-20" href="javascript:buy()" style="margin-top: 0;display: block;">立即预定</a>
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
            commonQues:'{{urlencode($product->common_ques)}}'
        }
        
        function commonQue() {
            //信息框
            // layer.open({
            //     content: '<p class="pre-text t-al-l">' + decodeURI(pageConfig.commonQues) + '</p>'
            //     ,btn: '关闭'
            // });
            location.href = '/passport/common-ques?product_id=' + pageConfig.product_id ;
        }

        function buy(){
            // alert(123);
            // calderVue.openCalderSwitch();
            wx.miniProgram.navigateTo(
                {
                    url: "/pages/fillbill/main?product_id=" + pageConfig.product_id +"&openid=" + pageConfig.openid
                });
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
                startDay:'',
                calderSwitch:false,
                chosenDay:'',
                chosenType:''
            },
            created:function(){
                let fullDay = new Date(this.year,this.month,0).getDate();
                let startWeek = new Date(this.year,this.month - 1,1).getDay();
                this.currentDay = new Date();
                // this.startDay = this.currentDay;
                this.chosenDay = this.startDay;


                this.year = this.currentDay.getFullYear();
                this.month = this.currentDay.getMonth() + 1;

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
                        if( this.quantity > 2)
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
                            if( _.indexOf([0,6],beginDay.getDay()) !== -1 )
                            {
                                continue;
                            }

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

                            if(mod == 0 || mod == 6 || (new Date(this.year,this.month - 1,i + 1) < this.currentDay))
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