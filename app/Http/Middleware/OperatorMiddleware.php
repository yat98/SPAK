<?php

namespace App\Http\Middleware;

use Closure;
use App\NotifikasiOperator;
use Illuminate\Support\Facades\Auth;

class OperatorMiddleware
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
        $notifikasi = NotifikasiOperator::all()->where('id_operator',Auth::user()->id)->where('status','belum dilihat');
        $countNotifikasi = $notifikasi->count();
        view()->share([
            'notifikasi'=>$notifikasi,
            'countNotifikasi'=>$countNotifikasi,
        ]);
        return $next($request);
    }
}
