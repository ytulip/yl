<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodMenu extends Model
{


    protected $guarded = [];

    /**
     * @param $date
     * @param int $type 1表示午餐，2表示晚餐
     */
    public static function getMenuArr($productId,$date,$type = 1)
    {
        $foodMenu = FoodMenu::where('product_id',$productId)->where('type',$type)->where('date',$date)->first();

        if( isset($foodMenu->foods))
        {
            return explode(' ',$foodMenu->foods);
        }else
        {
            return [];
        }
    }


    public static function getMenu($productId,$date,$type = 1)
    {
        $foodMenu = FoodMenu::where('product_id',$productId)->where('type',$type)->where('date',$date)->first();

        if( isset($foodMenu->foods))
        {
            return $foodMenu->foods;
        }else
        {
            return '';
        }
    }

    /**
     * 数量
     */
    public static  function getQuantity($productId,$date)
    {
        return SubFoodOrders::where('product_id',$productId)->where('date',$date)->where('type',1)->whereNotIn('status',[100])->count();
    }
}