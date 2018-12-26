<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Period extends Model{
    public static function periodConfig()
    {
        return self::selectRaw('id as ITEMNO,period_name as ITEMNAME')->get();
    }

    public static function periodName($id)
    {
        return Period::find($id)->period_name;
    }
}