@extends('admin.master_segment')
@section('style')
    <style>
        .item-row{
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }

        .v-a-m{vertical-align: middle;}

        .fs-14-fc-212229{font-size: 16px !important;}
    </style>
    @stop
@section('segment_content')
    <div style="background-color: #ffffff;">

        <div class="t-al-r" style="padding: 12px;">
               <a><i class="fa fa-print"></i>打印</a>
        </div>

        <div class="cus-row item-row cus-row-v-m" style="padding: 12px;">
            <div class="cus-row-col-2 fs-14-fc-212229">A餐</div>
            <div class="cus-row-col-2 fs-14-fc-212229">订餐数:{{\App\Model\FoodMenu::getQuantity(4,date('Y-m-d'))}}份</div>
            <div class="cus-row-col-8 fs-14-fc-212229">
                午餐菜品:{{\App\Model\FoodMenu::getMenu(4,date('Y-m-d'))}}
                <br/><br/>
                晚餐菜品{{\App\Model\FoodMenu::getMenu(4,date('Y-m-d'),2)}}</div>
        </div>

        <div class="cus-row item-row cus-row-v-m" style="padding: 12px;">
            <div class="cus-row-col-2 fs-14-fc-212229">B餐</div>
            <div class="cus-row-col-2 fs-14-fc-212229">订餐数:{{\App\Model\FoodMenu::getQuantity(5,date('Y-m-d'))}}份</div>
            <div class="cus-row-col-8 fs-14-fc-212229">
                午餐菜品:{{\App\Model\FoodMenu::getMenu(5,date('Y-m-d'))}}<br/><br/>
                晚餐菜品:{{\App\Model\FoodMenu::getMenu(5,date('Y-m-d'),2)}}</div>
        </div>

        <div class="cus-row item-row cus-row-v-m" style="padding: 12px;">
            <div class="cus-row-col-2 fs-14-fc-212229">C餐</div>
            <div class="cus-row-col-2 fs-14-fc-212229">订餐数:{{\App\Model\FoodMenu::getQuantity(6,date('Y-m-d'))}}份</div>
            <div class="cus-row-col-8 fs-14-fc-212229">
                午餐菜品:{{\App\Model\FoodMenu::getMenu(6,date('Y-m-d'))}}
            </div>
        </div>
    </div>
@stop