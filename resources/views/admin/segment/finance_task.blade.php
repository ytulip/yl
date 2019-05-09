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


@stop