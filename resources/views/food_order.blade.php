@extends('admin.master',['headerTitle'=>'任务管理 <span class="title-gap">></span>助餐服务' ])
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
@section('left_content')
    <div id="app">
        <div class="block-card mt-32">

            <div class="row paginate-list-row mt-32">
                <div class="col-md-2 col-lg-2">订单编号</div>
                <div class="col-md-2 col-lg-2">购买时间</div>
                <div class="col-md-1 col-lg-1">姓名</div>
                <div class="col-md-2 col-lg-2">电话</div>
                <div class="col-md-1 col-lg-1">订购方式</div>
                <div class="col-md-2 col-lg-2">购买价格</div>
                <div class="col-md-2 col-lg-2">详情</div>
            </div>

            @foreach( $paginate as $item)
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">{{$item->id}}</div>
                <div class="col-md-2 col-lg-2">{{$item->created_at}}</div>
                <div class="col-md-1 col-lg-1">{{$item->address_name}}</div>
                <div class="col-md-2 col-lg-2">{{$item->address_phone}}</div>
                <div class="col-md-1 col-lg-1">{{\App\Model\Order::buyTypeText($item->days)}}</div>
                <div class="col-md-2 col-lg-2">{{$item->origin_pay}}</div>
                <div class="col-md-2 col-lg-2"><a class="deliver" href="/admin/index/food-order-detail?id={{$item->id}}">查看</a></div>
            </div>
                @endforeach

            <div class="fl-r" style="margin-top: 30px;"><?php echo $paginate->appends(\Illuminate\Support\Facades\Request::all())->render(); ?></div>

        </div>
    </div>
@stop


@section('script')
    <script src="/js/vue.js"></script>
@stop