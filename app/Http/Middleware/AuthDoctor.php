<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\UserRsync;
use App\Models\User;

class AuthDoctor
{
	public function handle($request, Closure $next, $guard = null) {
		$user = auth()->guard('wap')->user();
		if( $user->is_doctor ) {
			//如果是医生
			return $next( $request ) ;
		} else {
			return redirect()->to( route('wap.applydoctor')) ;
		}
	}
}