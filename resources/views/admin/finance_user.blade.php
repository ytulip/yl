@extends('admin.master',['headerTitle'=>'任务管理 <span class="title-gap">></span> 金融服务'])
@section('style')
    <style>
.m-t-16{margin-top: 16px;}
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

    </style>
@stop
@section('left_content')
    <form id="data_form">

        <div class="block-card m-t-10">



            <div>主讲人：{{$product->sub_desc}}           联系方式：{{$product->fit_indi}}</div>
            <div class="m-t-16">开讲时间：{{\App\Util\Kit::dateFormat2($product->start_time)}} - {{\App\Util\Kit::dateFormat2($product->end_time)}}</div>
            <div class="m-t-16">授课地址：{{$product->context_deliver}}</div>
            <div class="m-t-16">授课内容：{{$product->context_server}}</div>


            <div style="margin: 24px 0;border-bottom: 1px solid #f0f0f0;"></div>


            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">序号</div>
                <div class="col-md-2 col-lg-2">姓名</div>
                <div class="col-md-2 col-lg-2">手机</div>
                <div class="col-md-3 col-lg-3">报名时间</div>
                <div class="col-md-3 col-lg-3">参加次数</div>
            </div>

            @foreach($paginate as $key=>$val)
                <div class="row paginate-list-row">
                    <div class="col-md-2 col-lg-2">{{$val->id}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->real_name}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->phone}}</div>
                    <div class="col-md-3 col-lg-3">{{$val->time_text}}</div>
                    <div class="col-md-3 col-lg-3">{{$val->count}}</div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->render(); ?></div>
    </form>

@stop

@section('script')
    <script>

    </script>
@stop