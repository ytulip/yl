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
                <div class="col-md-1 col-lg-1">名称</div>
            </div>

            @foreach($paginate as $key=>$val)
                <div class="row paginate-list-row" onclick="goHref('/admin/index/clean-detail?id={{$val->id}}')">
                    <div class="col-md-2 col-lg-2">{{$val->id}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->product_name}}</div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->render(); ?></div>
    </form>

    <div style="position: fixed;right: 60px;bottom: 60px;">
        <div style="width: 56px;height:56px;border-radius:96px;background-color:#697b8c; "><a style="width: 100%;display: block;line-height: 56px;text-align: center;font-size: 40px;color:#ffffff;" href="javascript:openAddMember()"><i class="fa fa-plus"></i></a></div>
    </div>
@stop

@section('script')
    <script>
        /**
         * 添加用户
         */
        function openAddMember()
        {
            new SubmitButton(
                {
                    url:'/admin/index/add-product',
                    callback:function(el,res){
                        location.href = '/admin/index/clean-detail?id=' + res.data;
                    }
                })
        }
    </script>
@stop