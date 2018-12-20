@extends('admin.master',['headerTitle'=>'会员管理 <span class="title-gap">></span> 会员详情 <span class="title-gap">></span> 辅导记录'])
@section('style')
    <style>

    </style>
@stop
@section('left_content')
    <div class="mt-32">
        <div class="block-card">
            <h3 class="">价格</h3>

            <div class="row">
                <div class="col-md-3 col-lg-3">小区</div>
                <div class="col-md-3 col-lg-3">面积范围</div>
                <div class="col-md-3 col-lg-3">价格</div>
                <div class="col-md-3 col-lg-3">操作</div>
            </div>


            @foreach($product->getAttrs() as $item)
                <div class="row">
                    <div class="col-md-3 col-lg-3">{{$item->neighborhood_name}}</div>
                    <div class="col-md-3 col-lg-3">{{$item->size}}</div>
                    <div class="col-md-3 col-lg-3">{{$item->price}}</div>
                    <div class="col-md-3 col-lg-3"></div>
                </div>
            @endforeach


            <div class="row">
                <div class="col-md-3 col-lg-3">{!! \App\Model\SyncModel::neighborhoods('neighborhood_name') !!}</div>
                <div class="col-md-3 col-lg-3"><input id="size"/></div>
                <div class="col-md-3 col-lg-3"><input id="price"/></div>
                <div class="col-md-3 col-lg-3"><div class="btn btn-dark" id="add_attr">新增</div></div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>

        var pageConfig = {
            product_id:{{$product->id}}
        }


        new SubmitButton({
            selectorStr:'#add_attr',
            url:'/admin/index/add-or-modify-product-attr',
            prepositionJudge:function()
            {
                return true;
            },
            callback:function(el,data)
            {
                if(data.status){
                    mAlert('添加成功');
                    location.reload();
                } else {
                    mAlert(data.desc);
                }
            },
            data:function(){
                return {size:$('#size').val(),product_id:pageConfig.product_id,price:$('#price').val(),neighborhood_name:$('select[name="neighborhood_name"]').val()};
            }
        });
    </script>
@stop