@extends('admin.master',['headerTitle'=>'会员管理 <span class="title-gap">></span> 会员详情 <span class="title-gap">></span> 辅导记录'])
@section('style')
    <style>

    </style>
@stop
@section('left_content')
    <form id="data_form">

        <div class="block-card m-t-10">
            <div class="row paginate-list-row">
                <div class="col-md-1 col-lg-1">订单编号</div>
                <div class="col-md-1 col-lg-1">姓名</div>
                <div class="col-md-1 col-lg-1">手机</div>
                <div class="col-md-1 col-lg-1">建筑面积</div>
                <div class="col-md-2 col-lg-2">上门时间</div>
                <div class="col-md-1 col-lg-1">服务项目</div>
                <div class="col-md-2 col-lg-2">地址</div>
                <div class="col-md-1 col-lg-1">备注</div>
                <div class="col-md-1 col-lg-1">详情</div>
                <div class="col-md-1 col-lg-1">处理</div>
            </div>

            @foreach($paginate as $key=>$val)
                <div class="row paginate-list-row">
                    <div class="col-md-1 col-lg-1">{{$val->id}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->real_name}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->phone}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->size}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->service_time}}</div>
                    <div class="col-md-1 col-lg-1">?</div>
                    <div class="col-md-2 col-lg-2">?</div>
                    <div class="col-md-1 col-lg-1">{{$val->remark}}</div>
                    <div class="col-md-1 col-lg-1">?</div>
                    <div class="col-md-1 col-lg-1">?</div>
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