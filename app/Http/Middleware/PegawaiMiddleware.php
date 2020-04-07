<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class PegawaiMiddleware
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
        if(Session::get('status') != 'pegawai'){
            return redirect('/');
        }
        return $next($request);
    }
}
