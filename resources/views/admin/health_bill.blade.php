@extends('admin.master',['headerTitle'=>'任务管理 <span class="title-gap">></span>体检服务' ])
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
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
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
                <div class="col-md-2 col-lg-2">手机</div>
                <div class="col-md-2 col-lg-2">预约时间</div>
                <div class="col-md-2 col-lg-2">接送地址</div>
                <div class="col-md-2 col-lg-2">接送时间</div>
                <div class="col-md-2 col-lg-2">处理</div>
            </div>

            <div class="row paginate-list-row" v-for="(item,index) in currentList">
                <div class="col-md-1 col-lg-1">@{{item['id']}}</div>
                <div class="col-md-1 col-lg-1">@{{item['real_name']}}</div>
                <div class="col-md-2 col-lg-2">@{{item['phone']}}</div>
                <div class="col-md-2 col-lg-2">@{{item['created_at']}}</div>
                <div class="col-md-2 col-lg-2">@{{item['address']}}</div>
                <div class="col-md-2 col-lg-2">@{{item['pick_time']}}</div>
                <div class="col-md-2 col-lg-2"><a class="deliver" @click="edit(item)">编辑</a>&nbsp;&nbsp;<a class="deliver" @click="doDeliver(item.id)">处理</a></div>
            </div>


            <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;" id="add_member_panel" v-if="layer_flag">
                <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 640px;">
                    <h4>编辑</h4>
                    <form id="data_form">



                        <div class="m-t-20">接送时间</div>

                        <el-date-picker
                                v-model="value2"
                                type="datetime"
                                placeholder="选择日期时间"
                                align="right"
                                :picker-options="pickerOptions1">
                        </el-date-picker>



                        <div class="m-t-20">接送地址</div>

                        <div class="row mb-12">
                            <div class="col-md-12 col-lg-12">
                                <input class="form-control" name="new_user_id_card" v-model="layer_address"/>
                            </div>
                        </div>

                    </form>
                    <div>
                        <button type="button" class="btn btn-success col-gray-btn mt-32" @click="addPerson">确认</button>
                        <button type="button" class="btn btn-success col-gray-btn mt-32" @click="closeLayer">取消</button>
                    </div>
                </div>
            </div>



            <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;" id="add_member_panel" v-if="layer_flag2">
                <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 640px;">
                    <h4>体检服务</h4>
                    <form id="data_form">


                        <div class="row mb-12">
                            <div class="col-md-12 col-lg-12">
                                <input class="form-control" name="new_user_id_card" v-model="layer_address"/>
                            </div>
                        </div>

                        <div class="row mb-12">
                            <div class="col-md-12 col-lg-12">
                                <select class="form-control" name="new_user_id_card" v-model="serveId">
                                    <option :value="item.id" v-for="item in serveUser">@{{ item.real_name }}</option>
                                </select>
                            </div>
                        </div>

                    </form>
                    <div>
                        <button type="button" class="btn btn-success col-gray-btn mt-32" @click="dealBill">确认</button>
                        <button type="button" class="btn btn-success col-gray-btn mt-32" @click="closeLayer">取消</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
@stop


@section('script')
    <script src="/js/vue.js"></script>
    {{--<script src="https://cdn.jsdelivr.net/npm/vue"></script>--}}
    <script src="/js/LodopFuncs.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script>


        Date.prototype.Format = function (fmt) { //author: meizz
            var o = {
                "M+": this.getMonth() + 1, //月份
                "d+": this.getDate(), //日
                "h+": this.getHours(), //小时
                "m+": this.getMinutes(), //分
                "s+": this.getSeconds(), //秒
                "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                "S": this.getMilliseconds() //毫秒
            };
            if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            for (var k in o)
                if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            return fmt;
        }


        var pageConfig =
            {
                serveUser:{!! $serveUser !!}
            }


        new Vue(
            {
                el:"#app",
                data:
                    {
                        tabIndex:1,
                        list:'',
                        layer_flag:'',
                        layer_flag2:'',
                        layer_address:'',
                        id:'',
                        serveUser:'',
                        serveId:'',
                        value2:'',
                        attrs:''
                    },
                created:function()
                {
                    this.serveUser = pageConfig.serveUser;
                    console.log(this.serveUser);
                    this.pageInit();
                },
                mounted:function()
                {
                },
                methods:
                    {
                        pickerOptions1()
                        {

                        },
                        edit(obj)
                        {
                            this.layer_address = obj.address?obj.address:'';
                            this.id = obj.id;
                            this.layer_flag = true;
                            this.value2 = obj.pick_time?(new Date(obj.pick_time)):'';
                        },
                        print(obj)
                        {
                            OpenPreview(obj);
                        },
                        setTab(ind)
                        {
                            this.tabIndex = ind;
                        },
                        doDeliver(id)
                        {
                            this.id = id;
                            this.layer_flag2 = true;
                            //var _self = this;
                            // $.get('/admin/index/do-health',{id:id},function(data)
                            // {
                            //     _self.pageInit();
                            // },'json');
                        },
                        editAddress(obj)
                        {
                            // console.log(obj.id);
                            // console.log(obj.address);
                        },
                        dealBill()
                        {

                            if( !this.serveId )
                            {
                                mAlert('请选择服务人员');
                                return;
                            }

                            //服务人员
                            var _self = this;
                            $.get('/admin/index/do-health',{id:this.id,serve_id:this.serve_id},function(data)
                            {
                                _self.closeLayer();
                                _self.pageInit();
                            },'json');
                        },
                        pageInit()
                        {
                            var _self = this;
                            $.post('/admin/index/health-bill',{},function(data){
                                if ( data.status )
                                {
                                    _self.list = data.data;
                                }
                            },'json');
                        },
                        closeLayer()
                        {
                            this.layer_flag = false;
                            this.layer_flag2 = false;
                        },
                        addPerson()
                        {

                            if( !this.value2 )
                            {
                                mAlert('接送时间不能为空');
                                return;
                            }


                            if(!this.layer_address)
                            {
                                mAlert('地址不能为空');
                                return;
                            }

                            var _self = this;
                            $.post('/admin/index/book-address',{id:this.id,address:this.layer_address,pick_time:this.value2.Format('yyyy-MM-dd hh:mm:ss')},function(data){
                                if ( data.status )
                                {
                                    _self.layer_flag = false;
                                    _self.pageInit();
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