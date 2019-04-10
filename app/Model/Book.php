<?php

namespace App\Model;

use App\Log\Facades\Logger;
use App\Util\Kit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{

    public $guarded = [];

    public static function isBooked($userId,$productId)
    {
        return Book::where('user_id',$userId)->where('product_id',$productId)->first();
    }

}