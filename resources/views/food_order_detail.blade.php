@extends('admin.master',['headerTitle'=>'会员管理 > 会员详情'])
@section('style')
<style>
    .user-header{
        width: 48px;
        height: 48px;
        border-radius: 48px;
        overflow: hidden;
        position: absolute;
        bottom: 0;
        left:15px;
    }

    .user-header img{
        width: 48px;
        height: 48px;
        border-radius: 48px;
    }

    .statical-record{
        line-height: 40px;
        font-size: 14px;
        padding: 8px;
        margin-bottom: 10px;
    }

    .statical-record span{
        line-height: 40px;
        display: inline-block;
        float: right;
        font-size: 12px;
    }

    .has-bg{background-color: #f0f0f0;}

</style>
@stop
@section('left_content')
<div class="mt-32 padding-col">
    <h4>基础信息</h4>
    <div class="block-card">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">姓名</p>
                <p class="text-desc-decoration"></p>
            </div>
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">用户ID</p>
                <p class="text-desc-decoration"></p>
            </div>
            <div class="col-md-4 col-lg-4">
                <p class="sm-tag-text">身份等级</p>
                <p class="text-desc-decoration"></p>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
</script>
@stop