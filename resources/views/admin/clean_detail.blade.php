@extends('admin.master',['headerTitle'=>'服务管理 <span class="title-gap">></span> 保洁服务 <span class="title-gap">></span> 服务编辑'])
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

            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>详情封面</p>
                    <a href="javascript:uploadCover3()"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                    <a id="do_publish" style="margin-left: 36px;"><i class="fa fa-save" aria-hidden="true"></i></a>

                    <div class="essay_img2">
                        <img src="{{isset($product->cover_image2)?$product->cover_image2:'/imgsys/1.jpg'}}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="block-card mt-32">
            <h4 class="">价格和简介</h4>

            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <p class="sm-tag-text">价格</p>
                    <p class="text-desc-decoration">李海波</p>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">服务简介</p>
                    <p class="text-desc-decoration">
                        <textarea name="sub_desc" style="width: 100%;height: 60px;">{{$product->sub_desc}}</textarea>
                    </p>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">服务描述</p>
                    <p class="text-desc-decoration">
                        <textarea name="context" style="width: 100%;height: 60px;">{{$product->context}}</textarea>
                    </p>
                </div>
            </div>


            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">资费说明</p>
                    <p class="text-desc-decoration">
                        <textarea name="context_deliver" style="width: 100%;height: 60px;">{{$product->context_deliver}}</textarea>
                    </p>
                </div>
            </div>


            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">服务范围及标准</p>
                    <p class="text-desc-decoration">
                        <textarea name="context_server" style="width: 100%;height: 60px;">{{$product->context_server}}</textarea>
                    </p>
                </div>
            </div>


            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">常见问题</p>
                    <p class="text-desc-decoration">
                        <textarea name="common_ques" style="width: 100%;height: 60px;">{{$product->common_ques}}</textarea>
                    </p>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-col-12">
                    <button class="btn btn-primary" id="do_publish" style="margin-left: 15px;">保存</button>
                </div>
            </div>

        </div>

    </div>
@stop

@section('script')
    <script>

        var pageConfig = {
            product_id:{{$product->id}},
            foodMenuTempData:{},
            uploadImgId:'',
        }


        function uploadCover(){
            pageConfig.uploadImgId = '';
            $('input[name="images[]"]').click();
        }

        function uploadCover2(){
            pageConfig.uploadImgId = 2;
            $('input[name="images[]"]').click();
        }

        function uploadCover3(){
            pageConfig.uploadImgId = 3;
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
                return {
                    product_id:pageConfig.product_id,
                    foods:$('input[name="foods"]').val(),
                    cover_img:$('#food_img').attr('src'),
                    type:$('select[name="type"]').val(),
                    date:$('input[name="date"]').val()

                }
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
                return {id:pageConfig.product_id,cover_image:$('.essay_img').find('img').attr('src'),sub_desc:$("textarea[name='sub_desc']").val(),context:$("textarea[name='context']").val(),context_deliver:$("textarea[name='context_deliver']").val(),context_server:$("textarea[name='context_server']").val(),common_ques:$("textarea[name='common_ques']").val()};
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
                            if(pageConfig.uploadImgId == 2){
                                $('#food_img').attr('src',data.data[0]);
                            } if(pageConfig.uploadImgId == 3) {
                                $('.essay_img2').find('img').attr('src', data.data[0]);
                            }else {
                                $('.essay_img').find('img').attr('src', data.data[0]);
                            }
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