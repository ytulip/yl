@extends('_layout.master')
@section('title')
    <title>下级会员</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 10px;}
        .income-list{font-size: 12px;}
        .mui-table-view{background-color: inherit;}
        .mui-table-view:before{display: none;}
        .mui-table-view:after{display: none;}

        .active-iframe{
            display: block !important;
        }
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'开发记录'])--}}
    <div class="info-vue">
        <ul class="mui-table-view income-list">
                    <li style="padding: 22px;margin-bottom: 14px;background-color: #ffffff;border-top: 1px solid #ebeaea;border-bottom:1px solid #ebeaea;" data-id="">
                        <div class="cus-row cus-row-v-m">
                            <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;">助餐服务</span></div>
                        </div>
                        <div class="cus-row cus-row-v-m">
                            <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;">套餐名称:</span></div>
                        </div>

                        <div class="cus-row cus-row-v-m">
                            <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;">服务时间:{{$order->service_start_time}} - {{$order->service_end_time}}</span></div>
                        </div>
                    </li>
        </ul>
        <div class="cus-row">
            <div class="cus-row-col-3 t-al-c"><span class="fs-14-fc-212229 " v-bind:class="{ 'active-tab': (tabIndex == 1) }" v-on:click="setTab(1)">本周菜单</span></div>
            <div class="cus-row-col-3 t-al-c"><span class="fs-14-fc-212229" v-bind:class="{ 'active-tab': (tabIndex == 2) }" v-on:click="setTab(2)">下周菜单</span></div>
        </div>

        <div style="display: none;" v-bind:class="{ 'active-iframe': (tabIndex == 1) }">

            @foreach($cWeek as $item)
            <div>
                <div class="cus-row cus-row-v-m">
                    <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;">{{$item->date}}</span></div>
                </div>
            </div>
                @endforeach
        </div>

        <div  style="display: none;" v-bind:class="{ 'active-iframe': (tabIndex == 2) }">
            @foreach($lWeek as $item)
                <div>
                    <div class="cus-row cus-row-v-m">
                        <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;">{{$item->date}}</span></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop

@section('script')
<script src="/js/vue.js"></script>
<script>
    new Vue({
        el:".info-vue",
        data:{tabIndex:1},
        methods:{
            setTab:function(index){
                this.tabIndex = index;
            }
        }
    });
</script>
@stop