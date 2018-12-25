<?php

namespace App\Model;

use App\Log\Facades\Logger;
use App\Util\Kit;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public static function getBannerList($status = [0,1],$type = 1)
    {
        $list = self::where('type',$type)->whereIn('status',$status)->orderBy('sort','desc')->orderBy('id','desc')->get();
        return $list;
    }
}