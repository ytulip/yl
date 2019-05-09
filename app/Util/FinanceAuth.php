<?php
namespace App\Util;

use App\Log\src\Logger;
use App\Model\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class FinanceAuth
{
    public static function attempt($phone){
        Session::push('finance', $phone);
        return true;
    }

    public static function check()
    {
        if(Session::has('finance')){
            return true;
        }else{
            return false;
        }
    }

    public static function phone()
    {
        return Session::get('admin');
    }

    public static function user(){
        return Session::get('admin')[0];
    }

    public static function logOut(){
        Session::flush();
    }

    public static function hasPower($powerId)
    {
        $power = Session::get('admin')[0]->power;
        if($power)
        {
            $power = json_decode($power,true);
        } else {
            $power = [];
        }

        return in_array($powerId,$power);
    }
}