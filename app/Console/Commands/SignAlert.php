<?php

namespace App\Console\Commands;

use App\Log\Facades\Logger;
use App\Model\CashStream;
use App\Model\Message;
use App\Model\Order;
use App\Model\SignRecord;
use App\Model\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class SignAlert extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'alert:sign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '超过三天未打卡的提醒';


    public function fire()
    {
        //每天凌晨分配当天的配餐订单

    }
}
