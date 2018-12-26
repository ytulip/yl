<?php
namespace  App\Util;
use Carbon\Carbon;

/**
 * 短信验证码
 * Class Sms
 * @package Util
 */
class FoodTime
{
    public $currentTime;
    public $nextMonday;
    public $weekDates = [];

    public function __construct()
    {
        $this->currentTime =   Carbon::now();
        $mon = Carbon::now()->startOfWeek();
        $this->weekDates = [$mon,Carbon::parse($mon->toDateTimeString())->addDay(1),Carbon::parse($mon->toDateTimeString())->addDay(2),Carbon::parse($mon->toDateTimeString())->addDay(3),Carbon::parse($mon->toDateTimeString())->addDay(4),Carbon::parse($mon->toDateTimeString())->addDay(5),Carbon::parse($mon->toDateTimeString())->addDay(6)];
        $this->lastWeekDates = [Carbon::parse($mon->toDateTimeString())->addDay(7),Carbon::parse($mon->toDateTimeString())->addDay(8),Carbon::parse($mon->toDateTimeString())->addDay(9),Carbon::parse($mon->toDateTimeString())->addDay(10),Carbon::parse($mon->toDateTimeString())->addDay(11),Carbon::parse($mon->toDateTimeString())->addDay(12),Carbon::parse($mon->toDateTimeString())->addDay(13)];
    }

    public function nextWeekServiceEndTime()
    {
        return $this->lastWeekDates[4];
    }


    public function startTimeList()
    {
        $list = [];
        $start = 0;
        foreach ($this->weekDates as $key=>$item)
        {
            if( $this->currentTime->toDateTimeString() < $item->toDateTimeString())
            {
                $list[] =  $item->format('Y-m-d');
            }

        }


        foreach ($this->lastWeekDates as $key=>$item)
        {
            $list[] =  $item->format('Y-m-d');
        }

        return $list;

    }
}