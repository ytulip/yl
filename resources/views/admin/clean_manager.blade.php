@extends('admin.master',['headerTitle'=>'会员管理 <span class="title-gap">></span> 会员详情 <span class="title-gap">></span> 辅导记录'])
@section('style')
    <style>

    </style>
@stop
@section('left_content')


    <input id="setStatus" type="hidden"/>

    <div id="data_form">

        <div class="block-card m-t-10">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">序号</div>
                <div class="col-md-7 col-lg-7">名称</div>
                <div class="col-md-3 col-lg-3">操作</div>
            </div>

            @foreach($paginate as $key=>$val)
                <div class="row paginate-list-row">
                    <div class="col-md-2 col-lg-2">{{$val->id}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->product_name}}</div>
                    <div class="col-md-9 col-lg-9">
                        <a class="btn btn-primary" href="/admin/index/clean-detail?id={{$val->id}}">编辑</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->render(); ?></div>
    </div>

    {{--<div style="position: fixed;right: 60px;bottom: 60px;">--}}
        {{--<div style="width: 56px;height:56px;border-radius:96px;background-color:#697b8c; "><a style="width: 100%;display: block;line-height: 56px;text-align: center;font-size: 40px;color:#ffffff;" href="javascript:void(0)" id="add_user"><i class="fa fa-plus"></i></a></div>--}}
    {{--</div>--}}
@stop

@section('script')
    <script>


        var pageConfig = {
            tmp_id:0,
            tmp_status:0
        }

        function setStatus(id,status)
        {
            pageConfig.tmp_id = id;
            pageConfig.tmp_status = status;
            $('#setStatus').trigger('click');
        }


        new SubmitButton(
            {
                selectorStr:'#setStatus',
                url:'/admin/index/set-status',
                data:function(){
                    return {id:pageConfig.tmp_id,status:pageConfig.tmp_status}
                },
                callback:function(el,res){
                    location.reload();
                }
        })

        /**
         * 添加用户
         */
        new SubmitButton(
            {
                selectorStr:'#add_user',
                url:'/admin/index/add-product',
                callback:function(el,res){
                    //location.href = '/admin/index/clean-detail?id=' + res.data;
                }
            })
    </script>
@stop