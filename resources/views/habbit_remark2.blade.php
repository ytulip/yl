@extends('_layout.master')
@section('title')
    <title>服务备注</title>
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

        .habbit-item{
            background: #FFFFFF;
            border: 1px solid #E1E1E1;
            border-radius: 17px;
            line-height: 34px;
            padding: 0 16px;
            display: inline-block;
            font-family: PingFangSC-Medium;
            font-size: 16px;
            color: #2E3133;
            text-align: center;
            margin-right: 14px;
            margin-top: 14px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.css">
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'辣木膳购买系统'])--}}

    <!--轮播-->
    <div class="p16 dpn" id="app">
        <textarea class="tare" placeholder="输入清洁要求" v-model="habitText"></textarea>

        <div class="cus-row m-t-24 cus-row-v-m">
            <div class="cus-row-col-6">
                <span class="fs-14-fc-2E3133-m">快捷标签</span>
            </div>
            <div class="cus-row-col-6 t-al-r">
                <span class="fs-14-fc-c50081-m" v-on:click="save" v-if="editFlg">保存</span>
                <span class="fs-14-fc-7E7E7E-m" v-on:click="edit" v-else>编辑</span>
            </div>
        </div>

        <div class="in-bl in-bl-line" style="margin-top: 8px;">
            <div class="habbit-item" @click="addText('重点是厨房')">重点是厨房</div>
            <div class="habbit-item" @click="addText('重点是卧室')">重点是卧室</div>
            <div class="habbit-item" @click="addText('重点是卫生间')">重点是卫生间</div>
            <div class="habbit-item" @click="addText('重点是阳台')">重点是阳台</div>
            <div class="habbit-item" @click="addText('小区有门禁')">小区有门禁</div>
            <div class="habbit-item" @click="addText('电话提前联系我')">电话提前联系我</div>
            <div class="habbit-item" @click="addText('家里有狗')">家里有狗</div>
            <div class="habbit-item" @click="addText(item.habit)" v-for="(item,index) in habit" style="position: relative" v-if="!item.hide">
                @{{ item.habit }}
                <div style="position: absolute;right: -10px;top: -10px;" v-if="editFlg" v-on:click="addDelete(index)">
                    <img src="/images/icon_close2_nor@3x.png" style="width: 20px;"/>
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


        var pageConfig = {habit:{!! json_encode($habit) !!}}


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
                el:'#app',
                data:
                    {
                        editFlg:false,
                        habit:pageConfig.habit,
                        habitText:''
                    },
                created:function()
                {
                    $('.dpn').removeClass('dpn');
                },
                methods:
                    {
                        addText(text)
                        {
                            if( this.editFlg )
                            {
                                return false;
                            }

                            this.habitText += ' ' + text;

                        },
                        edit(){
                            this.editFlg = true;
                        },
                        save()
                        {
                            this.editFlg = false;

                            var ids = [];
                            for( var i=0 ; i < this.habit.length; i++)
                            {
                                if ( this.habit[i].hide )
                                {
                                    ids.push(this.habit[i].id);
                                }
                            }

                            console.log(ids);

                            if( !ids.length )
                            {
                                return;
                            }

                            var _self = this;
                            $.post('/user/update-habit2',{ids:ids.join(',')},function(data){

                            },'json');
                        },
                        addDelete(ind)
                        {
                            this.habit[ind].hide = true;
                            console.log(this.habit);
                            this.$forceUpdate();
                        },
                        saveHabit()
                        {
                            //找出hide为true的所有id，然后请求接口删除
                            // var ids = [];
                            // for( var i=0 ; i < this.habit.length; i++)
                            // {
                            //     if ( this.habit[i].hide )
                            //     {
                            //         ids.push(this.habit[i].id);
                            //     }
                            // }
                            //
                            // console.log(ids);
                            //
                            // if( !ids.length )
                            // {
                            //     return;
                            // }
                            // $.post('/user/update-habit',{ids:ids.join(',')},function(data){
                            //
                            // },'json');
                        }
                    }
            }
        );
    </script>
@stop