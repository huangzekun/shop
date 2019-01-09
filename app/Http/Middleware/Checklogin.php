<?php

namespace App\Http\Middleware;

use Closure;

class Checklogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->session()->get('u_token')){
            header('refresh:2,login');
            echo '请先登陆';
            exit;
        }
        return $next($request);
    }
}