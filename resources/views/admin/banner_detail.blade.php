@extends('admin.master',['headerTitle'=>'会员管理'])
@section('style')
    <style>
        .mb-12{
            margin-bottom: 12px;
        }

        .t-al-r{text-align: right;}

        .user-list-item{font-size: 14px;padding: 6px 2px;}
        .user-list-item:hover{background-color: #f5f5f5}
    </style>
@stop
@section('left_content')
    <div class="mt-32 padding-col">
        <h4>会员信息</h4>
        <div class="row m-t-20">
            <div class="col-md-12 col-lg-12">

                <form class="form-inline" id="search_form">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="exampleInputAmount" name="keyword" placeholder="输入姓名、手机号搜索" value="{{\Illuminate\Support\Facades\Request::input('keyword')}}">
                            <div class="input-group-addon" onclick="search()"><i class="fa fa-search"></i></div>
                        </div>
                    </div>

                    <div class="form-group v-a-b">
                        <div class="input-group">
                            <a class="btn btn-info" href="javascript:commonDownload()">下载</a>
                        </div>
                    </div>
                </form>
            </div>
            {{--<div class="col-md-2 col-lg-2">--}}
            {{--<a class="btn btn-info" href="javascript:commonDownload()">下载</a>--}}
            {{--</div>--}}
        </div>
        <div class="block-card">
            <div class="row paginate-list-row">
                <div class="col-md-1 col-lg-1">ID</div>
                <div class="col-md-3 col-lg-3">封面图</div>
                <div class="col-md-2 col-lg-2">文字描述</div>
                <div class="col-md-1 col-lg-1">操作</div>
            </div>

                <div class="row paginate-list-row">
                    <div class="col-md-1 col-lg-1">{{$banner->id}}</div>
                    <div class="col-md-3 col-lg-3">
                        <img style="width: 240px;height: 140px;border: solid #c2c2c2 1px;" src="{{$banner->cover_image}}" onclick="uploadCover({{$banner->id}})" data-id="{{$banner->id}}" class="cover-img"/>
                    </div>
                    <div class="col-md-1 col-lg-1">{{$banner->title}}</div>
                    <div class="col-md-1 col-lg-1">
                        <a class="btn btn-primary save-edit" href="javascript:void(0)">保存</a>
                        @if(!$banner->status)
                        <a class="btn btn-primary save-edit" href="javascript:void(0)" onclick="publish()">发布</a>
                            @endif
                    </div>
                </div>

        </div>

    </div>

    <div style="position: fixed;right: 60px;bottom: 60px;">
        <div style="width: 56px;height:56px;border-radius:96px;background-color:#697b8c; "><a style="width: 100%;display: block;line-height: 56px;text-align: center;font-size: 40px;color:#ffffff;" href="javascript:openAddMember()"><i class="fa fa-plus"></i></a></div>
    </div>


    <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;display: none;" id="up_member_panel">
        <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
            <h4>升级天使会员</h4>
            <form id="edit_data_form">
                <input name="user_id" type="hidden"/>
                {{--<div class="row mb-12" id="up_user_phone_panel">--}}
                {{--<div class="col-md-3 col-lg-3 t-al-r">上级手机号:</div>--}}
                {{--<div class="col-md-9 col-lg-9" style="position: relative;">--}}
                {{--<input class="form-control" name="new_user_up_phone" readonly/>--}}

                {{--<div style="position: absolute;left:15px;right: 15px;top: 36px;padding: 12px;border: 1px solid rgb(211,211,211);height: 300px;background-color: #ffffff;z-index: 999;overflow: scroll;display: none;" id="user_list_scroll2">--}}
                {{--<input class="form-control" name="search_user"/>--}}
                {{--<div id="user-list2">--}}
                {{--<div class="user-list-item">18681224578-水水-510322198712324712</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">报单库存:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="get_good" value="0"/>
                    </div>
                </div>
            </form>
            <div>
                <button type="button" class="btn btn-success col-gray-btn mt-32" id="up_next_step">升级</button>
                <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="closeAddMember()">取消</button>
            </div>
        </div>
    </div>

    <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;display: none;" id="add_member_panel">
        <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
            <h4>新增banner图</h4>
            <form id="data_form">
                <input type="hidden" value="1" name="add_type"/>

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">文字描述:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="title"/>
                    </div>
                </div>

            </form>
            <div>
                <button type="button" class="btn btn-success col-gray-btn mt-32" id="next_step">确认</button>
                <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="closeAddMember()">取消</button>
            </div>
        </div>
    </div>

    <form style="display: none;" id="data-form">
        <input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>
    </form>
@stop

@section('script')
    <script>

        var pageConfig = {
            id:{{\Illuminate\Support\Facades\Request::input('id')}},
            currentImgId:0
        }


        function uploadCover(id){
            pageConfig.currentImgId = id;
            $('input[name="images[]"]').click();
        }


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
                            $('.cover-img[data-id="'+pageConfig.currentImgId+'"]').attr('src',data.data[0]);
                        } else {
                            alert(data.desc);
                        }
                    }
                });
            }
        });


        $(document).click(function(e){
            e = window.event || e; // 兼容IE7
            obj = $(e.srcElement || e.target);
            if ($(obj).is("#up_user_phone_panel,#up_user_phone_panel *,#up_member_panel,#up_member_panel *")) {
                // alert('内部区域');
                console.log('内部区域');
            } else {
                console.log('你的点击不在目标区域');
                if($('#user_list_scroll').css('display') == 'block') {
                    $('#user_list_scroll').hide();
                }

                if($('#user_list_scroll2').css('display') == 'block') {
                    $('#user_list_scroll2').hide();
                }
            }
        });

        $('body').on('click','.user-list-item',function(){
            $('input[name="new_user_up_phone"]').val( $(this).attr('data-phone') );
            $('#user_list_scroll,#user_list_scroll2').hide();
        });

        $('#add_member_panel input[name="new_user_up_phone"]').click(function(){
            // console.log(456);
            if($('#user_list_scroll').css('display') == 'block') {
                $('#user_list_scroll').hide();
            }else {
                $('#user_list_scroll').show();
            }
        });


        $('#up_member_panel input[name="new_user_up_phone"]').click(function(){
            // console.log(456);
            if($('#user_list_scroll2').css('display') == 'block') {
                $('#user_list_scroll2').hide();
            }else {
                $('#user_list_scroll2').show();
            }
        });



        $('.save-edit').click(function(){
            $.post('/admin/index/edit-data-manager',{id:pageConfig.id,cover_image:$('.cover-img').attr('src')},function(res){
                location.reload();
            });
        });
        
        function publish() {
            $.post('/admin/index/edit-data-manager',{id:pageConfig.id,cover_image:$('.cover-img').attr('src'),status:1},function(res){
                location.reload();
            });
        }



        function goDetail(id)
        {
            location.href = '/admin/index/member-detail?user_id=' + id;
        }

        // $('#add_member_panel').show();

        function closeAddMember()
        {
            $('#add_member_panel').hide();
            $('#up_member_panel').hide();
        }

        function openAddMember()
        {
            $('#add_member_panel').show();
        }

        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }

        new SubmitButton({
            selectorStr:'#next_step',
            url:'/admin/index/add-banner',
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
                return $('#data_form').serialize();
            }
        });


        new SubmitButton({
            selectorStr:'#up_next_step',
            url:'/admin/index/up-angel-user',
            prepositionJudge:function()
            {
                return true;
            },
            callback:function(el,data)
            {
                if(data.status){
                    mAlert('升级成功');
                    location.href="/admin/index/angle-members";
                } else {
                    mAlert(data.desc);
                }
            },
            data:function(){
                return $('#edit_data_form').serialize();
            }
        });

        function upUser(id)
        {
            event.stopPropagation();
            // alert(3);
            $('input[name="user_id"]').val(id);
            $('#up_member_panel').show();
        }



        function doMark()
        {
            event.stopPropagation();
        }

        function editMark(id) {
            event.stopPropagation();
            var mark = $('input[data-mark-id='+id+']').val();
            if(!mark)
            {
                mAlert('内容不能为空');
                return;
            }

            $.ajax({
                url:'/admin/index/edit-mark',
                dataType:'json',
                data:{user_id:id,mark:mark},
                success:function(data){
                    if(data.status){
                        mAlert('操作成功');
                        location.reload(true);
                    } else {
                        mAlert(data.desc);
                    }
                },
                error:function(){
                    mAlert('网络异常');
                }
            });
        }


    </script>
@stop