@extends('admin.master',['headerTitle'=>'概况'])
@section('left_content')

    <div class="">
        <div class="info-vue">
            <div class="row">
                <div class="col-md-3 col-lg-3 t-al-c"><span class="fs-14-fc-212229 " v-bind:class="{ 'active-tab': (tabIndex == 1) }" v-on:click="setTab(1)">助餐订单</span></div>
                <div class="col-md-3 col-lg-3 t-al-c"><span class="fs-14-fc-212229" v-bind:class="{ 'active-tab': (tabIndex == 2) }" v-on:click="setTab(2)">保洁订单</span></div>
                <div class="col-md-3 col-lg-3 t-al-c"><span class="fs-14-fc-212229" v-bind:class="{ 'active-tab': (tabIndex == 3) }" v-on:click="setTab(3)">金融服务</span></div>
                <div class="col-md-3 col-lg-3 t-al-c"><span class="fs-14-fc-212229" v-bind:class="{ 'active-tab': (tabIndex == 3) }" v-on:click="setTab(4)">体检服务</span></div>
            </div>


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