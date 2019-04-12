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
    public $lastWeekDates = [];

    public function __construct()
    {
        $this->currentTime =   Carbon::now();
        $mon = Carbon::now()->startOfWeek();
        $this->weekDates = [$mon,Carbon::parse($mon->toDateTimeString())->addDay(1),Carbon::parse($mon->toDateTimeString())->addDay(2),Carbon::parse($mon->toDateTimeString())->addDay(3),Carbon::parse($mon->toDateTimeString())->addDay(4),Carbon::parse($mon->toDateTimeString())->addDay(5),Carbon::parse($mon->toDateTimeString())->addDay(6)];
        $this->lastWeekDates = [Carbon::parse($mon->toDateTimeString())->addDay(7),Carbon::parse($mon->toDateTimeString())->addDay(8),Carbon::parse($mon->toDateTimeString())->addDay(9),Carbon::parse($mon->toDateTimeString())->addDay(10),Carbon::parse($mon->toDateTimeString())->addDay(11),Carbon::parse($mon->toDateTimeString())->addDay(12),Carbon::parse($mon->toDateTimeString())->addDay(13)];
    }

    public function nextDay()
    {
        return Carbon::parse($this->currentTime->toDateTimeString())->addDay(1)->format('Y-m-d');
    }

    public function nextWeekServiceEndTime()
    {
        return $this->lastWeekDates[4];
    }


    public function menuTimeList($type = 3)
    {
        $cWeek = [];
        $lWeek = [];

        if($type == 3 || $type == 1) {
            foreach ($this->weekDates as $key => $item) {

                $cWeek[] = $item->format('Y-m-d');
            }
        }

        if($type == 3 || $type == 2) {
            foreach ($this->lastWeekDates as $key => $item) {
                $lWeek[] = $item->format('Y-m-d');
            }
        }

        return array_merge($cWeek,$lWeek);
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



    public function thisWeekList()
    {
        $list = [];
        foreach ($this->weekDates as $key=>$item)
        {
                $list[] =  $item->format('Y-m-d');
        }

        return $list;
    }

    public function lastWeekList()
    {
        $list = [];
        foreach ($this->lastWeekDates as $key=>$item)
        {
            $list[] =  $item->format('Y-m-d');
        }

        return $list;
    }

    public static function hoursList()
    {
        return [9,10,11,12,13,14];
    }

    public static function minList()
    {
        return ['00','30'];
    }
}