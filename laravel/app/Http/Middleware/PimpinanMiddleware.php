<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\NotifikasiUser;
use Illuminate\Support\Facades\Auth;

class PimpinanMiddleware
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
        $notifikasi = NotifikasiUser::all()->where('nip',Auth::user()->nip)->where('status','belum dilihat');
        $countNotifikasi = $notifikasi->count();
        view()->share([
            'notifikasi'=>$notifikasi,
            'countNotifikasi'=>$countNotifikasi
        ]);

        return $next($request);
    }
}
