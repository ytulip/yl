@extends('admin.master',['headerTitle'=>'任务管理 <span class="title-gap">></span>助餐服务' ])
@section('style')
    <style>
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
    <div id="app">
        <div class="block-card mt-32">
            <ul class="nav nav-tabs">
                <li role="presentation" v-bind:class="{active:(tabIndex == 1)}" @click="setTab(1)"><a href="#">待处理</a></li>
                <li role="presentation" v-bind:class="{active:(tabIndex == 2)}" @click="setTab(2)"><a href="#">已处理</a></li>
            </ul>



            <div class="row paginate-list-row mt-32">
                <div class="col-md-1 col-lg-1">订单编号</div>
                <div class="col-md-1 col-lg-1">姓名</div>
                <div class="col-md-1 col-lg-1">手机</div>
                <div class="col-md-2 col-lg-2">订餐内容</div>
                <div class="col-md-1 col-lg-1">类型</div>
                <div class="col-md-1 col-lg-1">备注</div>
                <div class="col-md-1 col-lg-2">配送地址</div>
                <div class="col-md-1 col-lg-1">详情</div>
                <div class="col-md-1 col-lg-1">出单</div>
                <div class="col-md-1 col-lg-1">送达</div>
            </div>

            <div class="row paginate-list-row" v-for="(item,index) in currentList">
                <div class="col-md-1 col-lg-1">@{{item['sub_id']}}</div>
                <div class="col-md-1 col-lg-1">@{{item['address_name']}}</div>
                <div class="col-md-1 col-lg-1">@{{item['address_phone']}}</div>
                <div class="col-md-2 col-lg-2">@{{item['product_name']}} * @{{parseInt(item['quantity'])}}</div>
                <div class="col-md-1 col-lg-1">@{{(item['type'] == 1)?'午餐':'晚餐'}}</div>
                <div class="col-md-1 col-lg-1">@{{item['remark']}}</div>
                <div class="col-md-1 col-lg-2">@{{item['address']}}</div>
                <div class="col-md-1 col-lg-1"></div>
                <div class="col-md-1 col-lg-1"></div>
                <div class="col-md-1 col-lg-1"><a class="deliver" @click="doDeliver(item.sub_id)">确认送达</a></div>
            </div>

        </div>
    </div>
@stop


@section('script')
    <script src="/js/vue.js"></script>
    <script>
        new Vue(
            {
                el:"#app",
                data:
                    {
                        tabIndex:1,
                        list:''
                    },
                created:function()
                {
                    this.pageInit();
                },
                methods:
                    {
                        setTab(ind)
                        {
                            this.tabIndex = ind;
                        },
                        doDeliver(id)
                        {
                            var _self = this;
                            $.get('/admin/index/do-deliver',{id:id},function(data)
                            {
                                _self.pageInit();
                            },'json');
                        },
                        pageInit()
                        {
                            var _self = this;
                            $.post('/admin/index/clean-bill-by-day',{},function(data){
                                if ( data.status )
                                {
                                    _self.list = data.data;
                                }
                            },'json');
                        }
                    },
                computed:
                    {
                        currentList:function()
                        {
                            var tmpList = [];


                            if( this.tabIndex == 1)
                            {
                                for( var i=0; i < this.list.length ; i++)
                                {
                                    if( this.list[i].status != 2)
                                    {
                                        tmpList.push(this.list[i]);
                                    }
                                }
                            } else
                            {
                                for( var i=0; i < this.list.length ; i++)
                                {
                                    if( this.list[i].status == 2 )
                                    {
                                        tmpList.push(this.list[i]);
                                    }
                                }
                            }


                            console.log(tmpList);

                            return tmpList;
                        }
                    }
            }
        );
    </script>
@stop