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
        <div class="col-md-4 col-lg-4">体检机构</div>
        <div class="col-md-4 col-lg-4">待处理人数</div>
        <div class="col-md-4 col-lg-4">操作</div>
    </div>


    <div class="row paginate-list-row">
        <div class="col-md-4 col-lg-4">爱国健康大检查</div>
        <div class="col-md-4 col-lg-4">{{\App\Model\Book::where('product_id',25)->where('status',1)->count()}}</div>
        <div class="col-md-4 col-lg-4"> <a class="deliver" href="javascript:goParentHref('/admin/index/health-bill')">去处理</a> </div>
    </div>


@stop