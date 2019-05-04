@extends('admin.master_segment')
@section('segment_content')
    <div class="row" style="padding: 12px;">
        <div class="col-lg-3 col-md-3">A餐</div>
        <div class="col-lg-3 col-md-3">订餐数:{{\App\Model\FoodMenu::getQuantity(4,date('Y-m-d'))}}份</div>
        <div class="col-lg-6 col-md-3">
            午餐菜品:{{\App\Model\FoodMenu::getMenu(4,date('Y-m-d'))}}
            <br/>
            晚餐菜品{{\App\Model\FoodMenu::getMenu(4,date('Y-m-d'),2)}}</div>
    </div>

    <div class="row" style="padding: 12px;">
        <div class="col-lg-3 col-md-3">B餐</div>
        <div class="col-lg-3 col-md-3">订餐数:{{\App\Model\FoodMenu::getQuantity(5,date('Y-m-d'))}}份</div>
        <div class="col-lg-6 col-md-3">
            午餐菜品:{{\App\Model\FoodMenu::getMenu(5,date('Y-m-d'))}}<br/>
            晚餐菜品:{{\App\Model\FoodMenu::getMenu(5,date('Y-m-d'),2)}}</div>
    </div>

    <div class="row" style="padding: 12px;">
        <div class="col-lg-3 col-md-3">C餐</div>
        <div class="col-lg-3 col-md-3">订餐数:{{\App\Model\FoodMenu::getQuantity(6,date('Y-m-d'))}}份</div>
        <div class="col-lg-6 col-md-3">
            午餐菜品:{{\App\Model\FoodMenu::getMenu(6,date('Y-m-d'))}}<br/>
        </div>
    </div>
@stop