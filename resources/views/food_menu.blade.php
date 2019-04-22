@extends('_layout.master')
@section('title')
    <title>个人信息</title>
@stop
@section('style')
    <style>
        html,body{background-color: rgb(239,243,246);}
        footer .in-bl-line{line-height: 40px;}
        .info-item{width: 60%;}
        .opr-item{width: 40%;}

        .user-header-img {
            width: 34px;
            height: 34px;
            border-radius: 34px;
            overflow: hidden;
            display: inline-block;
            border: 1px solid #eeeeee;
        }

        .card-item{font-size: 14px;position: relative;}

        .card-item.navigate:after{
            right: 15px;
            content: '\e583';
            font-family: Muiicons;
            font-size: inherit;
            line-height: 1;
            position: absolute;
            top: 50%;
            display: inline-block;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            text-decoration: none;
            color: #bbb;
            -webkit-font-smoothing: antialiased;
        }

        .swiper-wrapper .active:after{
            content: "";
            position: absolute;
            border-bottom: 4px #C50081 solid;
            right: 0;
            left: 0;
            bottom: 0;
        }

        .swiper-wrapper .active
        {
            color:#C50081 !important;
        }

        #swiper1 .swiper-slide{width: auto!important;padding-top:10px;padding-bottom: 10px; }
        .topmenu{background-color: white;border-bottom: 1px solid #CCCCCC;padding: 0 10px;}

        .food-img{width: 100%;}
        .food-img img{width: 100%;}

        #swiper2 img{width: 100%;}
        #swiper2 .swiper-slide{width: 100%;overflow: hidden;}

        #swiper2 .swiper-slide img{border-radius: 8px;}

        body,html{background: #f9f9fb;}
    </style>
    <link href="https://cdn.bootcss.com/Swiper/4.0.6/css/swiper.min.css" rel="stylesheet">
@stop
@section('container')
    <div class="topmenu">
        <div class="swiper-container" id="swiper1">
            <div class="swiper-wrapper">
                @foreach($dates as $key=>$item)
                <span class="swiper-slide fs-16-fc-484848 @if($key== 0) active @endif" style="line-height: 22px;" data-date="{{$item}}">{{\App\Util\Kit::dateFormat3($item)}}</span>
                    @endforeach
            </div>
        </div>
    </div>

    <!---->
    {{--<div class="swiper-img">--}}
        {{--<div class="swiper-wrapper">--}}
            {{--<div>--}}
                {{--<img src=""/>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}


    <div class="bg-f9f9fb p16">


        <div class="swiper-container" id="swiper2">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="/imgsys/4d5455314e546b794d6a67774e44413d.jpg"></div><div class="swiper-slide"><img src="/imgsys/4d5455314e546b794d6a67304e44413d.jpg"></div>
            </div>
        </div>


    <div class="common-panel-24-16" style="margin-top: 14px;">
        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-1 t-a-l">
                <div class="red-v-l"></div>
            </div>
            <div class="cus-row-col-8">
                <span class="fs-18-fc-000000-m">午饭</span>
            </div>
            <div class="cus-row-col-3 t-al-r" >

            </div>
        </div>

        <div class="fs-16-fc-484848 m-t-16" style="line-height: 24px;" id="lunch">

        </div>
    </div>


    <div class="common-panel-24-16 m-t-16">
        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-1 t-a-l">
                <div class="red-v-l"></div>
            </div>
            <div class="cus-row-col-8">
                <span class="fs-18-fc-000000-m">晚餐</span>
            </div>
            <div class="cus-row-col-3 t-al-r">

            </div>
        </div>

        <div class="fs-16-fc-484848 m-t-16" style="line-height: 24px;" id="dinner">

        </div>
    </div>

    </div>
@stop

@section('script')
    <script src="https://cdn.bootcss.com/Swiper/4.0.6/js/swiper.min.js"></script>
    <script>

        var pageConfig =
            {
            dataJson:{!! json_encode($res) !!}
        };

        $(function () {
            var swiper = new Swiper('#swiper1', {
                spaceBetween: 20,
                slidesPerView: 'auto',
                freeMode: true
            });


            var imgSwiper = new Swiper('#swiper2', {
                autoplay:true,
            });


            $('.swiper-slide').click(function () {
                if ($(this).hasClass('active')) {
                    return;
                }
                $('.active').removeClass('active');
                $(this).addClass('active');

                var date = $(this).attr('data-date');


                // $('#swiper2 .swiper-wrapper').html('<div class="swiper-slide"><img src="' + (pageConfig.dataJson[date]['lunch'] ? pageConfig.dataJson[date]['lunch']['cover_img'] : '') + '"/></div><div class="swiper-slide"><img src="' + (pageConfig.dataJson[date]['dinner'] ? pageConfig.dataJson[date]['dinner']['cover_img'] : '') + '"/></div>');
                // mySwiper.updateSlides();
                imgSwiper.removeAllSlides();
                // mySwiper.ad
                imgSwiper.appendSlide([ '<div class="swiper-slide"><img src="' + (pageConfig.dataJson[date]['lunch'] ? pageConfig.dataJson[date]['lunch']['cover_img'] : '') + '"/></div>', '<div class="swiper-slide"><img src="' + (pageConfig.dataJson[date]['dinner'] ? pageConfig.dataJson[date]['dinner']['cover_img'] : '') + '"/></div>' ]);


                $('#lunch').html(pageConfig.dataJson[date]['lunch']['foods'] ? pageConfig.dataJson[date]['lunch']['foods'] : '');
                $('#dinner').html(pageConfig.dataJson[date]['dinner']['foods'] ? pageConfig.dataJson[date]['dinner']['foods'] : '');

            });
        });
    </script>
@stop