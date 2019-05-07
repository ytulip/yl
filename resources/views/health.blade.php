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

        .dpn{display: none;}


        .huajia-icon
        {
            width: 16px;
            height: 16px;
            background-image:url('/images/logo_small_nor@3x.png');
            background-size: 16px 16px;
            display: inline-block;
            vertical-align: middle;
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
    <div style="padding: 16px;">
        <div>
            <img src="{{ env('IMAGE_HOST') . $product->cover_image}}" style="width: 100%;"/>
        </div>


        <div class="common-panel-24-16">
            <div class="fs-18-fc-000000-m"><span class="huajia-icon"></span><span class="in-bl-v-m" style="margin-left: 10px;">体检机构 ：爱康国宾体检中心</span></div>
            <div class="fs-14-fc-7E7E7E-r m-t-16" style="line-height: 20px;">
                爱康集团（iKang Healthcare Group）依托旗下健康医疗服务中心、IT技术平台和强大的客户服务体系，每年为数百万客户提供健康体检、疾病检测、齿科服务、私人医生、职场医疗、疫苗接种、抗衰老等健康管理服务。
            </div>
        </div>

        <div class="fs-14-fc-7E7E7E-r m-t-24" style="line-height: 20px;">1. 基础检查(内、外、眼、耳鼻喉科)<br/>
            内科：心肺有无异常，肝脾有无肿大，腹部能否扪及包块等 外科：浅表淋巴结有无肿大，甲状腺，能否扪及包块，四肢，脊椎有无明显畸形 眼科：视力及色觉 耳鼻喉科：外耳道，鼻前庭及咽喉部检查
            <br/><br/>
            2. 肛门指检<br/>
            了解肛门、直肠病变的大小、位置、分泌物、出血、溃疡等情况<br/><br/>

            3. 口腔科<br/>
            口腔检查可对龋齿、牙龈炎、牙周炎进行早期检查和诊治<br/><br/>

            4. 眼底照相<br/>
            暂无介绍<br/><br/>

            5. 裂隙灯检查<br/>
            巩膜、虹膜、角膜、瞳孔、玻璃体、晶状体、前房等有无异常情况<br/><br/>

            6. 眼压<br/>
            不适人群:妊娠、哺乳以及备孕状态女性。<br/><br/>
        </div>

    </div>

    <div style="margin-bottom: 100px;"></div>

    @if($booked)
        <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
            <a class="yl_btn1 m-t-20 btn-gray" style="margin-top: 0;display: block;">已预约</a>
        </footer>
    @else
        <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
            <a class="yl_btn1 m-t-20" id="next_step" href="javascript:doYuyue()" style="margin-top: 0;display: block;">立即预约</a>
        </footer>
    @endif


    <div class="layer-shadow dpn">
        <div class="layer-center" style="padding: 24px;">

            <div class="t-al-c fs-16-fc-000000-m">提示</div>

            <div class="f-f-m t-al-c">
                <div class="fs-16-fc-7E7E7E-r" style="margin-top: 14px;" >确定预约体检？随后陪护人员将联系您</div>
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

@stop

@section('script')
    <script src="/js/vue.js"></script>
    <script src="/js/underscore.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.js"></script>
    <script type="text/javascript">

        var pageConfig = {
            product_id: {{$product->id}},
            user_id:'{{\Illuminate\Support\Facades\Request::input('user_id')}}'
        }



        function cancelLayer() {
            $('.layer-shadow').addClass('dpn');
        }

        function doYuyue()
        {
            $('.dpn').removeClass('dpn');
        }

        function nextStep()
        {
            $('.layer-shadow').addClass('dpn');
            $.get('/index/book-health',{product_id:pageConfig.product_id,user_id:pageConfig.user_id},function(data){
                $('#next_step').remove();
                $('footer').append('<a class="yl_btn1 m-t-20 btn-gray" style="margin-top: 0;display: block;">已预约</a>');
            },'json');
        }

        $(function () {
            new SubmitButton({
                selectorStr:"#next_step1",
                url:'/index/book-health',
                data:function()
                {
                    return {product_id:pageConfig.product_id,user_id:pageConfig.user_id};
                },
                callback:function(el,data)
                {
                    $('#next_step').remove();
                    $('footer').append('<a class="yl_btn1 m-t-20 btn-gray" style="margin-top: 0;display: block;">已预约</a>');
                }
            });
        });






    </script>
@stop