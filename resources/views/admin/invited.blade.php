@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 提现管理'])
@section('left_content')
    <div class="mt-32 padding-col">
        <h4>邀请码</h4>
        <div class="row m-t-20">
            <div class="col-md-12 col-lg-12">
                <form class="form-inline" id="search_form">


                    <div class="form-group v-a-b">
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">选择套餐</label>
                        <div class="input-group">
                            <select type="text" class="form-control" name="product_id" id="exampleInputAmount">
                                <option></option>
                                <option value="4">A餐</option>
                                <option value="5">B餐</option>
                                <option value="6">C餐</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">使用次数</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="exampleInputAmount" name="quantity" value="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">邀请码个数</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="exampleInputAmount" name="loops" value="1">
                        </div>
                    </div>


                    <div class="form-group v-a-b">
                        <div class="input-group">
                            <a class="btn btn-info" href="javascript:commonDownload()">生成</a>
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
                <div class="col-md-2 col-lg-2">兑换码</div>
                <div class="col-md-2 col-lg-2">生产时间</div>
                <div class="col-md-2 col-lg-2">类型</div>
                <div class="col-md-2 col-lg-2">数量</div>
                <div class="col-md-2 col-lg-2">是否已兑换</div>
                <div class="col-md-1 col-lg-1">兑换时间</div>
                <div class="col-md-1 col-lg-1">兑换用户</div>
            </div>
            @foreach($paginate as $item)
                <div class="row paginate-list-row">
                    <div class="col-md-2 col-lg-2">{{$item->code}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->created_at}}</div>
                    <div class="col-md-2 col-lg-2">{{\App\Model\Product::find($item->product_id)->product_name}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->quantity}}</div>
                    <div class="col-md-2 col-lg-2">{{($item->status == 1)?'否':'是'}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->user_id?\App\Util\Kit::dateFormatDay($item->updated_at):''}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->user_id?\App\Model\User::find($item->user_id)->real_name:''}}</div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->render(); ?></div>

    </div>
@stop

@section('script')
    <script>
        function goDetail(id)
        {
            location.href = '/admin/index/withdraw-detail?withdraw_id=' + id;
        }

        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }

        function commonDownload() {
            $.post('/admin/index/make-invited',$('#search_form').serialize(),function(data){
                if( data.status )
                {
                    location.reload();
                }
            },'json');
        }
    </script>
@stop