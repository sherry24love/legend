<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserRsync;
use App\Models\User;

class AutoLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    	//检查登录情况
        if( !auth('wap')->check() ) {
        	//这里表示 这个账号还没有创建  可以先建立账号
        	$user = session('wechat.oauth_user'); // 拿到授权用户资料
        	//查找用户
        	$authUser = UserRsync::with('user')->where('type' , 'wechat' )->where('token' , $user->id )->first() ;
        	if( $authUser ) {
        		auth('wap')->login( $authUser->user , true );
        		return $next( $request );
        	} else {
        		//自动用微信信息注册
        		$count = User::count();
        		$username = str_pad( $count + 1 , 8 , '0' , STR_PAD_LEFT );
        		$newUser = new User();
        		$newUser->username = 'ly_' .$username  ;
        		$newUser->password = bcrypt( '888888' );
        		$newUser->real_name = $user->nickname ;
        		$newUser->avatar = $user->headimgurl ;
        		$newUser->is_auth = 0 ;
        		$newUser->ip = ip2long( request()->ip() );
        		$newUser->reg_from = 'wechat';
        		$newUser->last_login_ip = ip2long( request()->ip() );
        		$newUser->last_login_time = time();
        		$from = session('_from');
        		$newUser->marketed_by_doctor = data_get( $from , 'doctor_id' , 0 );
        		$newUser->marketed_by_salesman = data_get( $from , 'salesman_id' , 0 );
        		if( data_get( $from , 'doctor_id' , 0 ) ) {
        			//
        			$fromDoctor = User::where('id' , data_get( $from , 'doctor_id' , 0 ) )->first();
        			$newUser->marketed_by_salesman = data_get( $fromDoctor , 'marketed_by_salesman' , 0 );
        		}
        		$newUser->save();
        		$rsync = new UserRsync([
        				'type' => 'wechat' ,
        				'token' => $user->id 
        		]);
        		$newUser->rsync()->save( $rsync );
        		//直接登录
        		auth('wap')->login( $newUser , true );
        		//登录后去到完善资料的页面
        		return $next( $request );
        	}
        }
        return $next($request);
        
    }
}
