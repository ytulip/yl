@extends('admin.master',['headerTitle'=>'会员订单'])
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


            <div style="margin-top: 26px;">
                <div class="t-al-r" style="">
                    <a href="javascript:openLayer()"><i class="fa fa-plus" style="margin-right: 12px;"></i>新增</a>
                </div>
            </div>


            <div class="row paginate-list-row m-t-16">
                <div class="col-md-2 col-lg-2">订单编号</div>
                <div class="col-md-2 col-lg-2">会员类型</div>
                <div class="col-md-2 col-lg-2">用户ID</div>
                <div class="col-md-3 col-lg-3">支付金额</div>
                <div class="col-md-3 col-lg-3">支付时间</div>
            </div>

            @foreach($paginate as $key=>$val)
                <div class="row paginate-list-row">
                    <div class="col-md-2 col-lg-2">{{$val->id}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->vipName()}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->user_id}}</div>
                    <div class="col-md-3 col-lg-3">{{$val->price}}</div>
                    <div class="col-md-3 col-lg-3">{{$val->updated_at}}</div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->render(); ?></div>



        <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;" class="dpn" id="add_member_panel">
            <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
                <h4>新增会员订单</h4>
                <form id="data_form">
                    <div class="row mb-12">
                        <div class="col-md-3 col-lg-3 t-al-r">用户角色:</div>
                        <div class="col-md-9 col-lg-9">
                            <select class="form-control" name="buy_type" value="">
                                <option value="1">A会员(3个月)</option>
                                <option value="2">A会员(6个月)</option>
                                <option value="3">B会员(3个月)</option>
                                <option value="4">B会员(6个月)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-12">
                        <div class="col-md-3 col-lg-3 t-al-r">手机号码:</div>
                        <div class="col-md-9 col-lg-9">
                            <input class="form-control" name="phone"/>
                        </div>
                    </div>

                </form>
                <div>
                    <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="addPerson()">确认</button>
                    <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="closeLayer()">取消</button>
                </div>
            </div>
        </div>
    </form>

@stop

@section('script')
    <script>

        function openLayer() {
            $('#add_member_panel').removeClass('dpn');
        }


        function addPerson()
        {
            var phone = $('input[name="phone"]').val();
            var buy_type = $('select[name="buy_type"]').val();

            if(!(/^1[3|4|5|8|7][0-9]\d{8}$/.test(phone))) {
                mAlert('请输入正确的手机号');
                return false;
            }

            if( !buy_type )
            {
                mAlert('请输入购买类型');
                return false;
            }


            $('#add_member_panel').addClass('dpn');
            $.get('/admin/index/buy-vip',{buy_type:buy_type,phone:phone},function(data){
                if( data.status )
                {
                    mAlert('购买成功');
                    location.reload();
                } else
                {
                    mAlert(data.desc);
                }
            },'json')
        }


        function closeLayer() {
            $('#add_member_panel').addClass('dpn');
        }
    </script>
@stop