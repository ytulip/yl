@extends('_layout.master')
@section('title')
    <title>商品详情</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f9f9fb;}
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

        .tare{
            background: #FFFFFF;
            box-shadow: 0 2px 6px 0 #E7E9F0;
            border-radius: 5px;
            height: 144px;
            font-family: PingFangSC-Medium;
            font-size: 16px;
            color: #000000;
            letter-spacing: -0.39px;
            padding: 16px;
            box-sizing: border-box;
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
            background: url('/images/icon_next_nor@3x.png');
            background-size: 8px 13px;
        }


        .prev-icon
        {
            display: inline-block;
            width: 8px;
            height: 13px;
            background: url('/images/icon_next_nor@3x.png');
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.css">
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'辣木膳购买系统'])--}}

    <!--轮播-->
    <div class="p16">
        <textarea class="tare" placeholder="输入口味、偏好要求等"></textarea>
    </div>


    <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
        <a class="yl_btn1 m-t-20" href="javascript:buy()" style="margin-top: 0;display: block;">确定</a>
    </footer>

    <div style="position: fixed;top:0;bottom: 0;left: 0;right: 0;background-color: rgba(28,36,75,0.80);z-index:9999" id="calder" v-if="calderSwitch">
        <div style="position: absolute;left:0;bottom: 0;right: 0;">


            <div style="padding-left: 16px;margin-bottom: 16px;">
                <img src="/images/icon_unsuess_nor@3x.png" width="24px;">
            </div>


            <div style="padding: 26px 16px 6px; background-color: #ffffff">
                <div class="cus-row" style="margin-bottom: 26px;">
                    <div class="cus-row-col-7">
                        <span class="fs-18-fc-212229-m">订餐份数</span><span style="margin-left: 16px;" class="fs-14-fc-7E7E7E-r">一份餐仅供一人</span>
                    </div>
                    <div class="cus-row-col-5 t-al-r">
                        <div class="in-bl v-a-m quantity-plus-icon" v-on:click="deQuantity"><image src="/images/icon_out_nor@3x.png" class="quantity-plus-icon"/></div>
                        <div class="in-bl v-a-m" style="margin: 0 30px;"><span class="quantity-plus">    @{{quantity}}    </span></div>
                        <div class="in-bl v-a-m quantity-plus-icon" v-on:click="addQuantity"><image src="/images/icon_add_nor@3x.png" class="quantity-plus-icon"/></div>
                    </div>
                </div>

                <div class="barr-line"></div>

                <div style="margin-top: 26px;">
                    <span class="fs-18-fc-212229-m">预定时间</span><span style="margin-left: 16px;" class="fs-14-fc-7E7E7E-r">单次预定限五天内 节假日暂不供应</span>
                </div>

                <div class="t-al-c" style="font-size: 0;margin-top: 26px;">
                    <div class="in-bl" style="background: #F9F9FB;
border: 1px solid #E1E1E1;
border-radius: 17px 0px 0px 17px;" v-bind:class="{ 'active-type': (tabIndex == 1) }" v-on:click="setTab(1)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">单次</span></div>
                    <div class="in-bl" style="background: #F9F9FB;
border-top: 1px solid #E1E1E1;border-bottom: 1px solid #E1E1E1;" v-bind:class="{ 'active-type': (tabIndex == 2) }" v-on:click="setTab(2)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">按周</span></div>
                    <div class="in-bl" style="background: #F9F9FB;
border: 1px solid #E1E1E1;
border-radius: 0px 17px 17px 0px;" v-bind:class="{ 'active-type': (tabIndex == 3) }" v-on:click="setTab(3)"><span class="fs-16-fc-080808-r" style="line-height: 36px;padding: 0 24px;">按月</span></div>
                </div>

                <div class="t-al-c" style="margin-top: 40px;">
                    <div class="cus-row">
                        <div class="cus-row-col-3 v-a-m t-al-l">
                            <span class="fs-16-fc-212229-m op3">@{{prveMonth}}</span>
                        </div>
                        <div class="cus-row-col-6 v-a-m">
                            <div class="cus-row">
                                <div class="cus-row-col-2 t-al-l">
                                    <i class="prev-icon"></i>
                                </div>
                                <div class="cus-row-col-8">
                                    <span class="fs-16-fc-212229-m">@{{currentMonth}}</span>
                                </div>
                                <div class="cus-row-col-2 t-al-r">
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
                    <a class="yl_btn1 m-t-20" href="javascript:buy()" style="margin-top: 0;display: block;">确定2</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
        <a class="yl_btn1 m-t-20" href="javascript:buy()" style="margin-top: 0;display: block;">确定</a>
    </footer>


@stop

@section('script')
    <script src="/js/vue.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.js"></script>
    <script type="text/javascript">
        function buy()
        {
            var jsonData = JSON.stringify({ habbitRemark:$('.tare').val() });
            console.log(jsonData);
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


        new Vue(
            {
                el:"#calder",
                data:{
                    year:2019,
                    month:1,
                    data:[
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}]
                    ],
                    s:'12',
                    lines:[0,1,2,3,4],
                    tabIndex:1,
                    quantity:0,
                    currentDay:'',
                    calderSwitch:true
                },
                methods:{
                    openCalder:function()
                    {
                        this.calderSwitch = true;
                    },
                    setTab:function(index){
                        this.tabIndex = index;
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
                        console.log(day);
                        //判断是否可以点击
                        day = day - 1;
                        let mo  = parseInt((startWeek + day) / 7);
                        let mod = (day+startWeek)%7;

                        if ( this.data[mo][mod].forbiddenChosen )
                        {
                            return;
                        }

                        if( this.data[mo][mod].chosen )
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



                        this.data[mo][mod].chosen = true;



//                        let tmpData = this.data;
//                        this.data = tmpData;
//
//                        console.log(this.data[mo][mod].chosen);
                        this.$forceUpdate();

                    }
                },
                created:function(){

                    var fullDay = new Date(this.year,this.month,0).getDate();
//                    console.log(fullDay);
//                    console.log(this.year);
//                    console.log(this.month);
                    startWeek = new Date(this.year,this.month - 1,1).getDay();
//                    total = (fullDay+startWeek)%7 == 0 ? (fullDay+startWeek) : fullDay+startWeek+(7-(fullDay+startWeek)%7);//元素总个数
//
//                    console.log(total)
//                  ;
                    this.currentDay = new Date();


                    var tmpData =  [
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}],
                        [{day:''},{day:''},{day:''},{day:''},{day:''},{day:''},{day:''}]

                    ];
                    for(i = 0;i<fullDay;i++)
                    {
                        let mo  = parseInt((i+startWeek) / 7);
                        let mod = (i+startWeek)%7;


                        //周六、周日不可点击
                        tmpData[mo][mod].day = i+1;

                        if(mod == 0 || mod == 6 || (new Date(this.year,this.month - 1,i + 1) < this.currentDay))
                        {
                            tmpData[mo][mod].forbiddenChosen = true;
                        }
                    }

                    this.data = tmpData;

//                    console.log(tmpData);

//                    console.log(new Date(this.year,this.month - 1,1).toDateString());



//                    total = (fullDay+startWeek)%7 == 0 ? (fullDay+startWeek) : fullDay+startWeek+(7-(fullDay+startWeek)%7);//元素总个数
//                    console.log(startWeek);

//                    startWeek2 = new Date('2019-01-01').getDay();
//                    console.log(startWeek2);

//                    console.log(total);
//                        lastMonthDay = new Date(this.year,this.month,0).getDate();



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
                    }
                }
            }
        );
    </script>
@stop