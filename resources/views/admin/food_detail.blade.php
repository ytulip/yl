@extends('admin.master',['headerTitle'=>'会员管理 <span class="title-gap">></span> 会员详情 <span class="title-gap">></span> 辅导记录'])
@section('style')
    <style>
        #edui1{width:100%!important;}
        .essay_img{position: absolute;top:50%;right: 40px;width: 60px;height: 60px;border-radius: 8px;transform: translateY(-50%);-webkit-transform: translateY(-50%);overflow: hidden;}
        .essay_img2{position: absolute;top:50%;right: 40px;width: 60px;height: 60px;border-radius: 8px;transform: translateY(-50%);-webkit-transform: translateY(-50%);overflow: hidden;}
        .essay_img img{width: 60px;height: 60px;border-radius: 8px;}
        .essay_img2 img{width: 60px;height: 60px;border-radius: 8px;}


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
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">价格</p>
                    {{--<p class="text-desc-decoration">{{$product->price}}</p>--}}
                    <div class="row">
                        <div class="col-md-11 col-lg-11"><input class="form-control no-border-input" name="price" value="{{$product->price}}"/></div>
                        <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                    </div>
                </div>
            </div>


            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">列表简介</p>
                    <p class="text-desc-decoration">
                        <textarea name="sub_desc" style="width: 100%;height: 60px;">{{$product->sub_desc}}</textarea>
                    </p>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">食谱简介</p>
                    <p class="text-desc-decoration">
                        <textarea name="food_desc" style="width: 100%;height: 60px;">{{$product->food_desc}}</textarea>
                    </p>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">适宜人群</p>
                    <p class="text-desc-decoration">
                        <textarea name="fit_indi" style="width: 100%;height: 60px;">{{$product->fit_indi}}</textarea>
                    </p>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-col-12">
                 <button class="btn btn-primary" id="do_publish" style="margin-left: 15px;">保存</button>
                </div>
            </div>
            
        </div>

        <!--默认显示上个月，这个月，下个月的菜单-->

        <div class="block-card mt-32" id="menu_book">

            <h4 class="">菜单编辑</h4>

            <div style="margin-top: 26px;">
                <div class="t-al-r" style="">
                    <a @click="add"><i class="fa fa-plus" style="margin-right: 12px;"></i>新增</a>
                </div>
            </div>

            <div class="row paginate-list-row mt-32">
                <div class="col-md-3 col-lg-3">日期</div>
                <div class="col-md-3 col-lg-3">菜单</div>
                <div class="col-md-3 col-lg-3">类型</div>
                <div class="col-md-3 col-lg-3">操作</div>
            </div>



            <div class="row paginate-list-row" v-for="(item,index) in list">
                <div class="col-md-3 col-lg-3">@{{item.date}}</div>
                <div class="col-md-3 col-lg-3">@{{item.foods}}</div>
                <div class="col-md-3 col-lg-3">@{{item.type?'午餐':'晚餐'}}</div>
                <div class="col-md-3 col-lg-3">修改</div>
            </div>




            {{--<h3 class="">菜单编辑</h3>--}}

            {{--<div class="row">--}}
                {{--<div class="col-md-3 col-lg-3">日期</div>--}}
                {{--<div class="col-md-3 col-lg-3">菜单</div>--}}
                {{--<div class="col-md-2 col-lg-2">午餐/晚餐</div>--}}
                {{--<div class="col-md-3 col-lg-3">餐图</div>--}}
            {{--</div>--}}


            {{--@foreach($clWeekMenu as $item)--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-3 col-lg-3"><input class="form-control no-border-input bt-line-1" value="{{$item->date}}"></div>--}}
                    {{--<div class="col-md-3 col-lg-3"><input class="form-control no-border-input bt-line-1" value="{{$item->foods}}"></div>--}}
                    {{--<div class="col-md-2 col-lg-2">--}}
                        {{--{{($item->type==1)?'午餐':'晚餐'}}--}}
                    {{--</div>--}}
                    {{--<div class="col-md-3 col-lg-3">--}}
                        {{--<img src="{{$item->cover_img}}" style="width: 120px;height: 120px;"/>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--@endforeach--}}


            {{--<div class="row edit-row">--}}
                {{--<div class="col-md-3 col-lg-3">--}}
                    {{--<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">--}}
                        {{--<input class="form-control" size="16" type="text" value="{{\Illuminate\Support\Facades\Request::input('start_time')}}" name="date" >--}}
                        {{--<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>--}}
                    {{--</div>--}}
                    {{--<input class="form-control no-border-input bt-line-1" value="" name="date">--}}
                {{--</div>--}}
                {{--<div class="col-md-3 col-lg-3"><input class="form-control no-border-input bt-line-1" value="" name="foods"></div>--}}
                {{--<div class="col-md-2 col-lg-2">--}}
                    {{--<select name="type">--}}
                        {{--<option value="1">午餐</option>--}}
                        {{--<option value="2">晚餐</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
                {{--<div class="col-md-3 col-lg-3">--}}
                    {{--<img src=""  id="food_img" style="width: 120px;height: 120px;" onclick="uploadCover2()"/>--}}
                {{--</div>--}}
                {{--<div class="col-md-1 col-lg-1">--}}
                    {{--<div class="btn btn-dark" id="edit_food_menu">新增</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<input type="hidden" id="edit_food_menu"/>--}}
        </div>

    </div>
@stop
<script src="/js/vue.js"></script>
@section('script')

    <script>

        var pageConfig = {
            product_id:{{$product->id}},
            foodMenuTempData:{},
            uploadImgId:'',
        }

        new Vue(
            {
                el:'#menu_book',
                data:function()
                {
                    return {
                        list:[],
                    }
                },
                methods:
                {
                   pageInit()
                   {
                       var _self = this;
                       $.get('/admin/index/food-menu',{product_id:pageConfig.product_id},function(data){
                           if ( data.status )
                           {
                               _self.list = data.data;
                           }
                       },'json');
                   }
                },
                created:function()
                {
                    this.pageInit();
                }
            }
        );


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
                return {id:pageConfig.product_id,cover_image:$('.essay_img').find('img').attr('src'),food_desc:$("textarea[name='food_desc']").val(),fit_indi:$("textarea[name='fit_indi']").val(),cover_image2:$('.essay_img2').find('img').attr('src'),price:$('input[name="price"]').val()};
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