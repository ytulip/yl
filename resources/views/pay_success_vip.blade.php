@extends('_layout.master')
@section('title')
    <title>开通会员</title>
@stop
@section('style')
    <style>

        html,body{background-color: #f9f9fb;}
        /*footer a{line-height: 40px;display: block;font-size: 16px;background: #0000C2;color:#ffffff;text-align: center;}*/

    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'购买完成'])--}}
    <div style="padding: 80px 48px 0 48px;">
        <div class="t-al-c">
            <img class="in-bl-v-m" style="width: 32px;" src="/images/icon_scuess_nor@3x.png"/><span style="margin-left: 14px;" class="in-bl-v-m fs-24-fc-000000-m">支付成功</span>
        </div>

        <div style="margin-top: 75px">
            <a class="yl_btn1" href="javascript:goHome()">返回首页</a>
        </div>

        <div style="margin-top: 24px">
            <a class="yl_btn1 btn-white" href="javascript:goDetail()">查看详情</a>
        </div>


        <div class="fs-16-fc-4A4A4A-r t-al-c" style="position: fixed;left:0;right: 0;bottom: 32px;" onclick="serve()" id="serve">
            花甲服务人
        </div>

        {{--<div class="fs-16-fc-4A4A4A-r t-al-c" style="position: fixed;left:0;right: 0;bottom: 32px;" id="">--}}
            {{--已提交 花甲服务人--}}
        {{--</div>--}}



        <div class="layer-shadow dpn">
            <div class="layer-center" style="padding: 24px;">

                <div class="f-f-m t-al-c" style="border-bottom:  1px solid #E1E1E1;">
                    <input class="fs-18-fc-2E3133-m t-al-c" style="padding: 20px 0;width: 100%;border: none;box-sizing: border-box;background: #F9F9FB;" placeholder="输入6位工号" name="workno"/>
                </div>

                <div class="cus-row" style="margin-top: 24px;">
                    <div class="cus-row-col-6">
                        <a class="yl_btn1 btn-none" href="javascript:cancelLayer()">取消</a>
                    </div>
                    <div class="cus-row-col-6">
                        <a class="yl_btn1" href="javascript:nextStep()">确定</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('script')
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script>



        var pageConfig =
            {
                id:'{{\Illuminate\Support\Facades\Request::input('id')}}'
            }

        function cancelLayer() {
            $('.layer-shadow').addClass('dpn');
        }

        function doYuyue()
        {
            console.log($('#serve').attr('attr_lock') );
            if ( $('#serve').attr('attr_lock') ) {
                return;

            }            $('.dpn').removeClass('dpn');
        }

        function serve()
        {
            if ( $('#serve').attr('attr_lock') ) {
                return;

            }
            $('.dpn').removeClass('dpn');
        }


        function nextStep()
        {

            var workNo = $("input[name='workno']").val();
            if( !workNo )
            {
                mAlert('请输入工号');
                return;
            }

            $.get('/passport/serve-member',{id:pageConfig.id,work_no:workNo},function(data){
                if( data.status )
                {
                    $('.layer-shadow').addClass('dpn');
                    $('#serve').html('已提交 花甲服务人');
                    $('#serve').attr('attr_lock',1);

                } else
                {
                    mAlert(data.desc);
                }
            },'json');
        }

        function goHome()
        {
            wx.miniProgram.switchTab({
                url: '/pages/index/main'
            });
        }

        function goDetail()
        {
            wx.miniProgram.redirectTo(
                {
                    url:'/pages/vip/main'
                }
            );
        }
    </script>
@stop