<?php

namespace App\Model;

use App\Log\Facades\Logger;
use App\Util\DealString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RandomPool extends Model
{
    public $table = 'random_pool';

    public static function make()
    {
        $count = 1;
        $flag = true;
        $invitedCode = '';
        while($flag)
        {

            $invitedCode = DealString::random(7, 'number_letter');
            $res = DB::table('random_pool')->where('code', $invitedCode)->first();
            if ($res) {
                Logger::info($res->id . '重复','random_pool');
                continue;
            }

            $randomPool = new RandomPool();
            $randomPool->code = $invitedCode;
            $randomPool->save();
            $flag = false;
        }

        return $invitedCode;
    }

    public static function random()
    {
        $randomGet = new RandomGet();
        $randomGet->save();

        $randomPool = RandomPool::find($randomGet->id);
        return $randomPool->code;
    }
}
