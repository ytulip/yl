@extends('admin.master_segment')
@section('style')
    <style>
        .nav-tabs a
        {
            line-height: 32px !important;
            padding: 0 8px !important;
        }


        .paginate-list-row {
            padding-top: 0;
            font-size: 14px;
            border: 1px solid #EAEEF7;
            background-color: #ffffff;
            height: 35px;
        }

        .paginate-list-row div{
            border-right: 1px solid #EAEEF7;
            background-color: #ffffff;
            height: 100%;
            line-height: 35px;
        }

        .deliver{cursor: pointer;}
    </style>
@stop
@section('segment_content')

    <div class="row paginate-list-row mt-32">
        <div class="col-md-2 col-lg-2">期号</div>
        <div class="col-md-2 col-lg-2">开讲时间</div>
        <div class="col-md-2 col-lg-2">金融顾问</div>
        <div class="col-md-2 col-lg-2">报名人数</div>
        <div class="col-md-3 col-lg-3">授课地址</div>
        <div class="col-md-1 col-lg-1">详情</div>
    </div>


    <div class="row paginate-list-row">
        <div class="col-md-2 col-lg-2">{{$product->period_no}}</div>
        <div class="col-md-2 col-lg-2">{{\App\Util\Kit::dateFormat2($product->start_time)}} - {{\App\Util\Kit::dateFormat2($product->end_time)}}</div>
        <div class="col-md-2 col-lg-2">{{\App\Model\ServeUser::find($product->owner_id)->real_name}}</div>
        <div class="col-md-2 col-lg-2">{{\App\Model\Book::where('product_id',$product->id)->count()}}</div>
        <div class="col-md-3 col-lg-3">{{$product->context_deliver}}</div>
        <div class="col-md-1 col-lg-1"> <a class="deliver" href="javascript:goParentHref('/admin/index/finance-user')">点击查看</a></div>
    </div>
@stop