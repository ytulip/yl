<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserHabit2 extends Model
{
    public $table = 'user_habit2';
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
            if (in_array($item, ['重点是厨房', '重点是卧室', '重点是卫生间', '重点是阳台', '小区有门禁', '电话提前联系我','家里有狗'])) {
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