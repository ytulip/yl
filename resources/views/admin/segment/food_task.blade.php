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
            <div style="width: 220px;display: inline-block;vertical-align: middle;">
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding-bottom: 0;">
                <input class="form-control" size="16" type="text" value="{{$date}}" name="date" >
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>    </div> <a style="display: inline-block;vertical-align: middle;margin-left: 20px;" href="javascript:search()"><i class="fa fa-search"></i>搜索</a> <a style="display: inline-block;vertical-align: middle;margin-left: 20px;"><i class="fa fa-print"></i>打印</a>
        </div>

        <div class="cus-row item-row cus-row-v-m" style="padding: 12px;">
            <div class="cus-row-col-2 fs-14-fc-212229">A餐</div>
            <div class="cus-row-col-2 fs-14-fc-212229">订餐数:{{\App\Model\FoodMenu::getQuantity(4,$date)}}份</div>
            <div class="cus-row-col-8 fs-14-fc-212229">
                午餐菜品:{{\App\Model\FoodMenu::getMenu(4,$date)}}
                <br/><br/>
                晚餐菜品{{\App\Model\FoodMenu::getMenu(4,$date,2)}}</div>
        </div>

        <div class="cus-row item-row cus-row-v-m" style="padding: 12px;">
            <div class="cus-row-col-2 fs-14-fc-212229">B餐</div>
            <div class="cus-row-col-2 fs-14-fc-212229">订餐数:{{\App\Model\FoodMenu::getQuantity(5,$date)}}份</div>
            <div class="cus-row-col-8 fs-14-fc-212229">
                午餐菜品:{{\App\Model\FoodMenu::getMenu(5,$date)}}<br/><br/>
                晚餐菜品:{{\App\Model\FoodMenu::getMenu(5,$date,2)}}</div>
        </div>

        <div class="cus-row item-row cus-row-v-m" style="padding: 12px;">
            <div class="cus-row-col-2 fs-14-fc-212229">C餐</div>
            <div class="cus-row-col-2 fs-14-fc-212229">订餐数:{{\App\Model\FoodMenu::getQuantity(6,$date)}}份</div>
            <div class="cus-row-col-8 fs-14-fc-212229">
                午餐菜品:{{\App\Model\FoodMenu::getMenu(6,$date)}}
            </div>
        </div>

        <div style="margin-bottom: 100px;">

        </div>
    </div>
@stop

@section('script')
    <script>
        function  search() {
            location.href = '/admin/index/food-task?date=' + $("input[name='date']").val();
        }
    </script>
    @stop