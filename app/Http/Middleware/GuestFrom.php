<?php

namespace App\Http\Middleware;

use Closure;

class GuestFrom
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
        if( $request->input('_from') ) {
        	$from = $request->input('_from') ;
        	$from = json_decode( $from , true );
        	session(['_from' => $from ]);
        }
        return $next($request);
    }
}
