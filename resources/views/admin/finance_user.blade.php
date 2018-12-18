@extends('admin.master',['headerTitle'=>'会员管理 <span class="title-gap">></span> 会员详情 <span class="title-gap">></span> 辅导记录'])
@section('style')
    <style>

    </style>
@stop
@section('left_content')
    <form id="data_form">

        <div class="block-card m-t-10">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">序号</div>
                <div class="col-md-1 col-lg-1">姓名</div>
                <div class="col-md-1 col-lg-1">手机</div>
                <div class="col-md-2 col-lg-2">报名时间</div>
                <div class="col-md-2 col-lg-2">住址</div>
                <div class="col-md-2 col-lg-2">参加次数</div>
                <div class="col-md-2 col-lg-2">详情</div>
            </div>

            @foreach($paginate as $key=>$val)
                <div class="row paginate-list-row">
                    <div class="col-md-2 col-lg-2">{{$val->id}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->real_name}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->phone}}</div>
                    <div class="col-md-2 col-lg-2">{{date('Y-m-d',$val->created_at)}}</div>
                    <div class="col-md-2 col-lg-2">?</div>
                    <div class="col-md-2 col-lg-2">0</div>
                    <div class="col-md-2 col-lg-2">详情</div>
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