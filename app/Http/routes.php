<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
//    return R('welcome');
    return \Illuminate\Support\Facades\Redirect::to('/user/index');
});
Route::get('hello',function(){
//	return 'hello';
    $vipOrder = \App\Model\VipOrder::find('7');
    $vipOrder->doCoupon();
});

Route::controller('/finace','FinaceController');
Route::controller('/passport','PassportController');
Route::controller('/index','IndexController');
Route::controller('/activity','ActivityController');
Route::controller('/jump','JumpController');
Route::controller('/finance','FinanceClass\IndexController');

Route::group(['middleware' => ['auth.check']], function()
{
    Route::controller('/user','UserController');
    Route::controller('/order','OrderController');
});

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['admin.check']], function()
{
    Route::controller('index','IndexController');
});