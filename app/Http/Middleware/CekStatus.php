<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CekStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$levels)
    {
        if(in_array(Auth::guard('pusat')->user()->level,$levels));
        {
            return $next($request);
        }
        return redirect('/login');
    }
}
