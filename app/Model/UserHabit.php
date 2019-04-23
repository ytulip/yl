<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserHabit extends Model
{
    public $table = 'user_habit';
    protected $guarded = [];

    /**
     * 忌口备注
     */
    public static function addHabit($userid,$remark)
    {
        if( !$remark )
        {
            return;
        }

        $remarkArr = explode(' ',$remark);
        foreach ($remarkArr as $item) {
            if (in_array($item, ['不吃辣', '不放辣', '不吃蒜', '不吃葱', '不吃香菜', '不放盐'])) {
                continue;
            }

            if(!$item)
            {
                continue;
            }

            UserHabit::firstOrCreate(['user_id' => $userid, 'habit' => $item]);
        }
    }
}