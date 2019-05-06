@extends('admin.master',['headerTitle'=>'服务人员' ])
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
                <li role="presentation" v-bind:class="{active:(tabIndex == 1)}" @click="setTab(1)"><a href="#">社区经理</a></li>
                <li role="presentation" v-bind:class="{active:(tabIndex == 2)}" @click="setTab(2)"><a href="#">服务人员</a></li>
                <li role="presentation" v-bind:class="{active:(tabIndex == 3)}" @click="setTab(3)"><a href="#">金融讲师</a></li>
                <li role="presentation" v-bind:class="{active:(tabIndex == 4)}" @click="setTab(4)"><a href="#">销售人员</a></li>
            </ul>

            <div style="margin-top: 26px;">
                <div class="t-al-r" style="">
                    <a @click="add"><i class="fa fa-plus" style="margin-right: 12px;"></i>新增</a>
                </div>
            </div>

            <div class="row paginate-list-row mt-32">
                <div class="col-md-2 col-lg-2">姓名</div>
                <div class="col-md-2 col-lg-2">身份证</div>
                <div class="col-md-2 col-lg-2">手机号码</div>
                <div class="col-md-2 col-lg-2">工号</div>
                <div class="col-md-4 col-lg-4">编辑</div>
            </div>

            <div class="row paginate-list-row" v-for="(item,index) in currentList">
                <div class="col-md-2 col-lg-2">@{{item.real_name}}</div>
                <div class="col-md-2 col-lg-2">@{{item.id_card}}</div>
                <div class="col-md-2 col-lg-2">@{{item.mobile}}</div>
                <div class="col-md-2 col-lg-2">@{{item.work_no}}</div>
                <div class="col-md-4 col-lg-4">
                    <a class="deliver" @click="print(item)">删除</a>
                    <a class="deliver" @click="edit(item)" style="margin-left: 24px;">编辑</a>
                </div>
            </div>


            <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;" id="add_member_panel" v-if="layer_flag">
                <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
                    <h4>新增</h4>
                    <form id="data_form">
                        <div class="row mb-12">
                            <div class="col-md-3 col-lg-3 t-al-r">用户角色:</div>
                            <div class="col-md-9 col-lg-9">
                                <select class="form-control" v-model="roleId">
                                    <option value="1">社区经理</option>
                                    <option value="2">服务人员</option>
                                    <option value="3">金融讲师</option>
                                    <option value="4">销售人员</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-12">
                            <div class="col-md-3 col-lg-3 t-al-r">姓名:</div>
                            <div class="col-md-9 col-lg-9">
                                <input class="form-control" name="new_user_real_name" v-model="layer_real_name"/>
                            </div>
                        </div>
                        <div class="row mb-12">
                            <div class="col-md-3 col-lg-3 t-al-r">身份证:</div>
                            <div class="col-md-9 col-lg-9">
                                <input class="form-control" name="new_user_id_card" v-model="layer_id_card"/>
                            </div>
                        </div>

                        <div class="row mb-12">
                            <div class="col-md-3 col-lg-3 t-al-r">手机号码:</div>
                            <div class="col-md-9 col-lg-9">
                                <input class="form-control" name="new_user_id_card" v-model="layer_mobile"/>
                            </div>
                        </div>

                    </form>
                    <div>
                        <button type="button" class="btn btn-success col-gray-btn mt-32" @click="addPerson">确认</button>
                        <button type="button" class="btn btn-success col-gray-btn mt-32" @click="closeLayer">取消</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop


@section('script')
    <script src="/js/vue.js"></script>
    <script src="/js/LodopFuncs.js"></script>
    <script>


        new Vue(
            {
                el:"#app",
                data:
                    {
                        tabIndex:1,
                        list:'',
                        layer_role:'',
                        layer_real_name:'',
                        layer_id_card:'',
                        layer_mobile:'',
                        roleId:'',
                        layer_flag:false,
                        id:''
                    },
                created:function()
                {
                    this.pageInit();
                },
                methods:
                    {
                        add()
                        {
                            this.id = '';
                            this.roleId = this.tabIndex;
                            this.layer_flag = true;
                        },
                        edit(obj)
                        {
                            this.id = obj.id;
                            this.roleId =  obj.type;
                            this.layer_mobile =  obj.mobile;
                            this.layer_real_name =  obj.real_name;
                            this.layer_id_card =  obj.id_card;
                            this.layer_flag = true;
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
                            var _self = this;
                            $.get('/admin/index/do-health',{id:id},function(data)
                            {
                                _self.pageInit();
                            },'json');
                        },
                        pageInit()
                        {
                            var _self = this;
                            $.post('/admin/index/serve-member-list',{},function(data){
                                if ( data.status )
                                {
                                    _self.list = data.data;
                                }
                            },'json');
                        },
                        addPerson()
                        {
                            var _self = this;
                            $.post('/admin/index/add-or-modify-serve-member',{id:this.id,real_name:this.layer_real_name,id_card:this.layer_id_card,type:this.roleId,mobile:this.layer_mobile},function(data){
                                if ( data.status )
                                {
                                    _self.layer_flag = false;
                                    _self.pageInit();
                                }
                            },'json');
                        },
                        closeLayer()
                        {
                            this.layer_flag = false;
                        }
                    },
                computed:
                    {
                        currentList:function()
                        {
                            var tmpList1 = [];
                            var tmpList2 = [];
                            var tmpList3 = [];
                            var tmpList4 = [];


                            for( var i=0; i < this.list.length ; i++)
                            {
                                if( this.list[i].type == 1 )
                                {
                                    tmpList1.push(this.list[i]);
                                }else if( this.list[i].type == 2 )
                                {
                                    tmpList2.push(this.list[i]);
                                }else if( this.list[i].type == 3 )
                                {
                                    tmpList3.push(this.list[i]);
                                }else if(this.list[i].type == 4)
                                {
                                    tmpList4.push(this.list[i]);
                                }
                            }



                            return eval('tmpList' + this.tabIndex);
                        }
                    }
            }
        );
    </script>
@stop