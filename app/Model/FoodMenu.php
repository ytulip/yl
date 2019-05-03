<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodMenu extends Model
{

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
}