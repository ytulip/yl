<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VipOrder extends Model
{
    public $table = 'vip_order';


    public static function typeGetPrice($type)
    {
        if( $type == 1){
            return 3212;
        }else if( $type == 2)
        {
            return 6424;
        }else if( $type == 3)
        {
            return 3768;
        }else if( $type == 4 )
        {
            return 7536;
        }
    }



    public function vipName()
    {
        if ( $this->buy_type == 1 || $this->buy_type == 2 )
        {
            return 'A会员';
        }else
        {
            return 'B会员';
        }
    }

    /**
     * 发放优惠券动作
     */
    public function doCoupon()
    {
        //1会员A3个月，2会员A6个月

        //3会员B3个月，4会员B6个月
        if ( $this->buy_type == 1 )
        {
            $foodCount = 90;
            $foodTypeText = 'A餐';
            $normalClean = 6;
            $deepClean = 3;
            $healthCount = 1;
            $financeCount = 4;
        } else if ( $this->buy_type == 2)
        {
            $foodCount = 180;
            $foodTypeText = 'A餐';
            $normalClean = 12;
            $deepClean = 6;
            $healthCount = 1;
            $financeCount = 4;
        } else if ( $this->buy_type == 3)
        {
            $foodCount = 90;
            $foodTypeText = 'B餐';
            $normalClean = 6;
            $deepClean = 3;
            $healthCount = '';
            $financeCount = 1;
        } else {
            $foodCount = 180;
            $foodTypeText = 'B餐';
            $normalClean = 12;
            $deepClean = 3;
            $healthCount = 1;
            $financeCount = 4;
        }


        $baseDay = in_array($this->buy_type,[1,3])?90:180;


        for ($i = 0; $i < $foodCount; $i++ )
        {
            $coupon = new Coupon();
            $coupon->coupon_type = ( $this->buy_type > 2 )?5:4;
            $coupon->expire_at = Carbon::now()->addDays($baseDay)->format('Y-m-d');
            $coupon->type_text = $foodTypeText;
            $coupon->user_id = $this->user_id;
            $coupon->refer_id = $this->id;
            $coupon->status = 1;
            $coupon->save();
        }


        //每一个保洁服务送3张代金券
        for ($i = 0; $i < $normalClean; $i++ )
        {
            $coupon = new Coupon();
            $coupon->coupon_type = 1;
            $coupon->expire_at = Carbon::now()->addDays($baseDay)->format('Y-m-d');
            $coupon->type_text = '日常保洁';
            $coupon->user_id = $this->user_id;
            $coupon->refer_id = $this->id;
            $coupon->status = 1;
            $coupon->save();
        }

//        for ($i = 0; $i < $deepClean; $i++ )
//        {
//            $coupon = new Coupon();
//            $coupon->coupon_type = 2;
//            $coupon->expire_at = Carbon::now()->addDays($baseDay)->format('Y-m-d');
//            $coupon->type_text = '深度保洁';
//            $coupon->user_id = $this->user_id;
//            $coupon->refer_id = $this->id;
//            $coupon->status = 1;
//            $coupon->save();
//        }

        return true;
    }
}