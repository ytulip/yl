@extends('admin.master',['headerTitle'=>'会员管理 <span class="title-gap">></span> 会员详情 <span class="title-gap">></span> 辅导记录'])
@section('style')
    <style>
        #edui1{width:100%!important;}
        .essay_img{position: absolute;top:50%;right: 40px;width: 60px;height: 60px;border-radius: 8px;transform: translateY(-50%);-webkit-transform: translateY(-50%);overflow: hidden;}
        .essay_img img{width: 60px;height: 60px;border-radius: 8px;}
    </style>
@stop
@section('left_content')
    <div class="mt-32">


        <form style="display: none;" id="data-form">
            <input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>

        </form>

        <div class="row mt-32">
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>封面图片</p>
                    <a href="javascript:uploadCover()"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                    <a id="do_publish" style="margin-left: 36px;"><i class="fa fa-save" aria-hidden="true"></i></a>

                    <div class="essay_img">
                        <img src="{{isset($product->cover_image)?$product->cover_image:'/imgsys/1.jpg'}}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="block-card mt-32">
            <h3 class="">价格</h3>

            <div class="row">
                <div class="col-md-3 col-lg-3">订单时长</div>
                <div class="col-md-3 col-lg-3">价格</div>
                <div class="col-md-3 col-lg-3">操作</div>
            </div>


            @foreach($product->getAttrs() as $item)
                <div class="row">
                    <div class="col-md-3 col-lg-3">{{$item->period_name}}</div>
                    <div class="col-md-3 col-lg-3">{{$item->price}}</div>
                    <div class="col-md-3 col-lg-3">
                    </div>
                </div>
            @endforeach


            <div class="row">
                <div class="col-md-3 col-lg-3">{!! \App\Model\SyncModel::period('period') !!}</div>
                <div class="col-md-3 col-lg-3"><input id="price"/></div>
                <div class="col-md-3 col-lg-3"><div class="btn btn-dark" id="add_attr">新增</div></div>
            </div>
        </div>


        <div class="block-card mt-32">
            <h3 class="">菜单编辑</h3>

            <div class="row">
                <div class="col-md-3 col-lg-3">日期</div>
                <div class="col-md-3 col-lg-3">菜单</div>
            </div>


            @foreach($clWeekMenu as $item)
                <div class="row">
                    <div class="col-md-3 col-lg-3"><input class="form-control no-border-input bt-line-1" value="{{$item->date}}" name="date"></div>
                    <div class="col-md-3 col-lg-3"><input class="form-control no-border-input bt-line-1" value="{{$item->foods}}" name="remark"></div>
                    <div class="col-md-3 col-lg-3"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            @endforeach


            <div class="row edit-row">
                <div class="col-md-3 col-lg-3"><input class="form-control no-border-input bt-line-1" value="" name="date"></div>
                <div class="col-md-3 col-lg-3"><input class="form-control no-border-input bt-line-1" value="" name="foods"></div>
                <div class="col-md-3 col-lg-3">
                    <select name="type">
                        <option value="1">午餐</option>
                        <option value="2">晚餐</option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-3"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil edit-tmp-menu" aria-hidden="true"></i></a></div>
            </div>

            <input type="hidden" id="edit_food_menu"/>
        </div>

    </div>
@stop

@section('script')
    <script>

        var pageConfig = {
            product_id:{{$product->id}},
            foodMenuTempData:{}
        }


        function uploadCover(){
            $('input[name="images[]"]').click();
        }


        $('.edit-tmp-menu').click(function(){
            var editRow = $(this).parents('.edit-row');


            pageConfig.foodMenuTempData.product_id = pageConfig.product_id;
            pageConfig.foodMenuTempData.type = $(editRow).find('select[name="type"]').val();
            pageConfig.foodMenuTempData.foods = $(editRow).find('input[name="foods"]').val();
            pageConfig.foodMenuTempData.date = $(editRow).find('input[name="date"]').val();
            pageConfig.foodMenuTempData.id = $(editRow).find('input[name="id"]').val();

            $('#edit_food_menu').trigger('click');
        });


        new SubmitButton({
            selectorStr:"#edit_food_menu",
            url:'/admin/index/edit-food-menu',
            prepositionJudge:function(){
                return true;
            },
            data:function(){
                return pageConfig.foodMenuTempData;
            },
            callback:function (el,val)
            {
                location.reload();
            }
        });


        new SubmitButton({
            selectorStr:"#do_publish",
            url:'/admin/index/good',
            prepositionJudge:function(){
                return true;
            },
            data:function(){
                return {id:pageConfig.product_id,cover_image:$('.essay_img').find('img').attr('src')};
            },
            callback:function (el,val)
            {
                location.reload();
            }
        });


        $('body').on('change','input[name="images[]"]',function(){
            if(this.value){
                var formData = new FormData($("#data-form")[0]);
                $.ajax({
                    url:'/admin/index/album-image',
                    data:formData,
                    type:'post',
                    contentType: false,
                    processData: false,
                    dataType:'json',
                    success:function(data){
                        $('input[name="images[]"]').replaceWith('<input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>');
                        if(data.status) {
                            $('.essay_img').find('img').attr('src',data.data[0]); 'http://static.liaoliaoy.com/' + data.data[0];
                        } else {
                            alert(data.desc);
                        }
                    }
                });
            }
        });


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
                return {product_id:pageConfig.product_id,price:$('#price').val(),period_id:$('select[name="period"]').val()};
            }
        });
    </script>
@stop