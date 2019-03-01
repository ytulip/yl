<?php

namespace App\Model;

use App\Util\FoodTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    public static function getProductAttrsConfig($productId)
    {
        $res = DB::table('product_attrs')->where('product_id',$productId)->where('is_valid',1)->selectRaw('id as ITEMNO,attr_des as ITEMNAME')->get();
        return $res?$res:[];
    }


    public static function getProductAttrsConfigJson($productId = 1)
    {
        $res = DB::table('product_attrs')->where('product_id',$productId)->where('is_valid',1)->selectRaw('id as value,attr_des as name,price')->get();
        return $res?json_encode($res,JSON_UNESCAPED_UNICODE):json_encode([]);
    }

    public static function getDefaultProduct()
    {
        return Product::find(1);
    }


    public function getAttrs($filters = [])
    {
        $query = ProductAttr::where('product_id',$this->id);
        if( $filters )
        {
            $query->where($filters);
        }

        return $query->get();
    }

    public function isCleanProduct()
    {
        return ($this->type == 1)?true:false;
    }

    public function clWeekMenu()
    {
        $foodTime = new FoodTime();
        $list = FoodMenu::where('product_id',$this->id)->whereIn('date',$foodTime->menuTimeList())->get();
        return $list;
    }


}