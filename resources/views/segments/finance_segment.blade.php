@extends('_layout.master')
@section('title')
    <title>下级会员</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 10px;}
        .income-list{font-size: 12px;}
        .mui-table-view{background-color: inherit;}
        .mui-table-view:before{display: none;}
        .mui-table-view:after{display: none;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'开发记录'])--}}
    <div class=" ">
        <ul class="mui-table-view income-list">
            @if(count($list))
                @foreach($list as $key=>$item)
                    <li style="padding: 22px;margin-bottom: 14px;background-color: #ffffff;border-top: 1px solid #ebeaea;border-bottom:1px solid #ebeaea;" data-id="{{$item->id}}">
                        <div class="cus-row cus-row-v-m">
                            <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;">讲课内容</span></div>
                            <div class="cus-row-col-6 t-al-r"><a href="" class="fs-14-fc-98CC3D">{{$item->title}}</a></div>
                        </div>
                        <div class="cus-row cus-row-v-m">
                            <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;">主讲人:{{$item->owner_name}}</span></div>
                        </div>

                        <div class="cus-row cus-row-v-m">
                            <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;">主讲时间:{{\App\Util\Kit::dateFormatDay($item->service_time)}}</span></div>
                        </div>
                    </li>
                @endforeach
            @else
                <div class="t-al-c" style="margin-top: 80px;"><img src="/images/icon_img.png" style="display: inline-block;"/></div>
                <div class="t-al-c" style="margin-top: 24px;"><span style="font-size: 16px;color:#a8a8a8;">暂无内容</span></div>
            @endif
        </ul>
    </div>
@stop

@section('script')
@stop