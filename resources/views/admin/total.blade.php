@extends('admin.master',['headerTitle'=>'概况'])
@section('left_content')

    <div class="block-card mt-32">
        <div class="info-vue">

            <ul class="nav nav-tabs">
                <li role="presentation" v-bind:class="{active:(tabIndex == 1)}" @click="setTab(1)"><a href="#">助餐订单</a></li>
                <li role="presentation" v-bind:class="{active:(tabIndex == 2)}" @click="setTab(2)"><a href="#">保洁订单</a></li>
                <li role="presentation" v-bind:class="{active:(tabIndex == 3)}" @click="setTab(3)"><a href="#">金融服务</a></li>
                <li role="presentation" v-bind:class="{active:(tabIndex == 4)}" @click="setTab(4)"><a href="#">体检服务</a></li>
            </ul>


            <div>
                <iframe src="/admin/index/food-task" frameborder="0" scrolling="no" id="test" onload="this.height=100" style="width: 100%;margin-bottom: 50px;display: none;" v-bind:class="{ 'active-iframe': (tabIndex == 1) }"></iframe>
                <iframe src="/admin/index/clean-task" frameborder="0" scrolling="no" id="test1" onload="this.height=100" style="width: 100%;margin-bottom: 50px;display: none;" v-bind:class="{ 'active-iframe': (tabIndex == 2) }"></iframe>
                <iframe src="/admin/index/finance-task" frameborder="0" scrolling="no" id="test2" onload="this.height=100" style="width: 100%;margin-bottom: 50px;display: none;" v-bind:class="{ 'active-iframe': (tabIndex == 3) }"></iframe>
                <iframe src="/admin/index/health-task" frameborder="0" scrolling="no" id="test3" onload="this.height=100" style="width: 100%;margin-bottom: 50px;display: none;" v-bind:class="{ 'active-iframe': (tabIndex == 4) }"></iframe>
            </div>
    </div>
    @stop
@section('script')
<script src="/js/vue.js"></script>
<script>

    function reinitIframe(){
        var iframe = document.getElementById("test");
        try{
            iframe.height = iframe.contentWindow.document.body.clientHeight;
        }catch (ex){}

        var iframe = document.getElementById("test1");
        try{
            iframe.height = iframe.contentWindow.document.body.clientHeight;
        }catch (ex){}


        var iframe = document.getElementById("test2");
        try{
            iframe.height = iframe.contentWindow.document.body.clientHeight;
        }catch (ex){}


        var iframe = document.getElementById("test3");
        try{
            iframe.height = iframe.contentWindow.document.body.clientHeight;
        }catch (ex){}
    }
    window.setInterval("reinitIframe()", 200);




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