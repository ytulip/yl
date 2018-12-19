<?php

namespace  App\Http\Middleware;

use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuthCheck
{
    public function handle($request, \Closure $next)
    {

        //如果有openid用openid进行登录
        if( $openid = Request::input('openid') )
        {
            $user = User::where('openid',$openid)->first();
            if( $user instanceof  User)
            {
                Auth::loginUsingId($user->id);
            }
        }

//        dd(Request::segment(2));
        if(!Auth::check() && !in_array(Request::segment(2),['index','good-detail','essay','good-detail-xcx','report-bill'])){
            if ($request->ajax()) {
                return json_encode(['status'=>0,'desc'=>'请先登录']);
            } else {
                return redirect()->guest('/passport/login');
            }
        }


        return $next($request);
    }
}
