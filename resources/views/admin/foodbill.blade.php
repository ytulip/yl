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
                <div class="col-md-1 col-lg-1">@{{tabIndex == 1?'送达':'备注'}}</div>
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
                <div class="col-md-1 col-lg-1"><a class="deliver" @click="print(item)">出单</a></div>
                <div class="col-md-1 col-lg-1">
                    <a class="deliver" @click="doDeliver(item.sub_id)" v-if="tabIndex == 1">确认送达</a>
                    <a class="deliver" @click="doRemark(item.sub_id)" v-else>填写备注</a>
                </div>
            </div>

        </div>
    </div>
@stop


@section('script')
<script src="/js/vue.js"></script>
<script src="/js/LodopFuncs.js"></script>
    <script>


        var pageConfig =
            {
                food_menu:{!! json_encode($food_menu) !!},
                date:'{{date('Y-m-d')}}'
            }


        var LODOP; //声明为全局变量
        function OpenPreview(obj) {
            LODOP = getLodop();
            //打印时间
            //nowTime = getDate();
            //订单号
            //orderNo = $("#orderNo").text();
            //
            LODOP.NEWPAGEA();
            //LODOP.ADD_PRINT_RECT(10,18,324,392,0,1);
            var marinTop = 30;

            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"花甲生活服务");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",14);
            //

            marinTop += 20;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"------------------");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",14);
            // //

            // marinTop += 30;

            // LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"配送日期:" + pageConfig.date);
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",10);
            //
            // marinTop += 20;
            // LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"客服热线:028-61526472");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            marinTop += 20;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"客户姓名:" + obj.address_name);
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            marinTop += 20;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"电话:" + obj.address_phone);
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            marinTop += 20;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"送餐地址:" + obj.address);
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);
            // LODOP.SET_PRINT_STYLEA(-2,"fontsize",10);




            marinTop += 60;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"------------------");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",14);


            marinTop += 20;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54, obj.product_name + ((obj.type == 1)?"（午餐）":"（晚餐）"));
            LODOP.SET_PRINT_STYLEA(0,"fontsize",14);

            marinTop += 40;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"---------- 菜品 ----------");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            //品名 //数量
            marinTop += 20;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','27mm',54,"品名");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            LODOP.ADD_PRINT_TEXT(marinTop,'29mm','27mm',54,"数量");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);


            marinTop += 20;
            if( obj.product_id == 4 )
            {
                foods = pageConfig.food_menu[0].menu[obj.type - 1];
            } else if( obj.product_id == 5 )
            {
                foods = pageConfig.food_menu[1].menu[obj.type - 1];
            } else {
                foods = pageConfig.food_menu[2].menu[0];
            }


            for(var j=0; j < foods.length; j++ )
            {
                LODOP.ADD_PRINT_TEXT(marinTop,'2mm','27mm',54,foods[j]);
                LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

                LODOP.ADD_PRINT_TEXT(marinTop,'29mm','27mm',54,'x' + parseInt(obj.quantity));
                LODOP.SET_PRINT_STYLEA(0,"fontsize",10);
                marinTop += 20;
            }

            // LODOP.ADD_PRINT_TEXT(260,'2mm','27mm',54,"品名");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",10);
            //
            // LODOP.ADD_PRINT_TEXT(260,'29mm','27mm',54,"数量");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",10);


            // LODOP.ADD_PRINT_TEXT(280,'2mm','50mm',54,"------------------");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",14);



            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"------------------");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",14);


            marinTop += 20;

            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"订单备注:" + obj.remark);
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            marinTop += 60;

            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"------------------");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",14);

            marinTop += 20;

            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"配送日期:" + pageConfig.date);
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            marinTop += 20;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"客服热线:028-61526472");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);


            marinTop += 20;
            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"------------------");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",14);

            marinTop += 20;

            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"订单编号:" + obj.sub_id);
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            marinTop += 20;

            // LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"配送日期:" + pageConfig.date);
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",10);
            //
            // marinTop += 20;
            //
            // LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"客服热线:028-61526472");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",10);
            //
            // marinTop += 20;

            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,"客户签字:");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);

            marinTop += 60;

            LODOP.ADD_PRINT_TEXT(marinTop,'2mm','50mm',54,".");
            LODOP.SET_PRINT_STYLEA(0,"fontsize",10);
            // LODOP.SET_PRINT_STYLEA(-2,"fontsize",10);
            //
            // LODOP.ADD_PRINT_TEXT(120,40,258,54,"--已在线支付--");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",16);
            //
            // LODOP.ADD_PRINT_TEXT(150,15,258,54,"................................");
            // LODOP.ADD_PRINT_TEXT(180,15,258,54,"【下单时间】2018-12-01 04:02:00");
            // LODOP.ADD_PRINT_TEXT(210,15,258,54,"..............商品..............");
            // //商品列表
            // LODOP.ADD_PRINT_TEXT(230,15,100,54,"蒙牛奶特香草味牛奶243ml*12盒");
            // LODOP.ADD_PRINT_TEXT(230,130,40,54,"*1");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",14);
            // LODOP.ADD_PRINT_TEXT(230,170,40,54,"22");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",14);
            //
            // LODOP.ADD_PRINT_TEXT(270,15,100,54,"蒙牛未来星妙妙儿童成长牛奶原味125ml*20盒");
            // LODOP.ADD_PRINT_TEXT(270,130,40,54,"*1");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",14);
            // LODOP.ADD_PRINT_TEXT(270,170,40,54,"22");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",14);
            // //其他
            // LODOP.ADD_PRINT_TEXT(320,15,258,54,"..............其他..............");
            // LODOP.ADD_PRINT_TEXT(340,15,258,54,"配送费：0.00元");
            // LODOP.ADD_PRINT_TEXT(360,15,258,54,"优惠券：0.00元");
            // LODOP.ADD_PRINT_TEXT(380,15,258,54,"商品总金额：0.00元");
            // LODOP.ADD_PRINT_TEXT(400,15,258,54,"................................");
            // //总计
            // LODOP.ADD_PRINT_TEXT(420,15,258,54,"总计：");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",16);
            // LODOP.ADD_PRINT_TEXT(420,100,100,54,"￥136.06");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",16);
            // //地址
            // LODOP.ADD_PRINT_TEXT(460,15,200,54,"海上五月花5期天马路海上五月花5期7幢1001");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",16);
            // //收货人
            // LODOP.ADD_PRINT_TEXT(540,15,200,54,"依恋");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",16);
            // LODOP.ADD_PRINT_TEXT(570,15,200,54,"18859669092");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",16);
            // LODOP.ADD_PRINT_TEXT(600,10,258,54,"*******#1 完******");
            // LODOP.SET_PRINT_STYLEA(0,"fontsize",16);
            //设定打印页面大小
            LODOP.SET_PRINT_PAGESIZE(3,'54mm',45,"订单页面");
            LODOP.PREVIEW();
        };


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
                            $.get('/admin/index/do-deliver',{id:id},function(data)
                            {
                                _self.pageInit();
                            },'json');
                        },
                        pageInit()
                        {
                            var _self = this;
                            $.get('/admin/index/food-bill-by-day',{},function(data){
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