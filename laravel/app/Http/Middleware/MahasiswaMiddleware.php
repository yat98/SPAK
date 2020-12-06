<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\PimpinanOrmawa;
use App\NotifikasiMahasiswa;
use Illuminate\Support\Facades\Auth;

class MahasiswaMiddleware
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
        $notifikasi = NotifikasiMahasiswa::all()->where('nim',Auth::user()->nim)->where('status','belum dilihat');
        $pimpinanOrmawa = PimpinanOrmawa::where('nim',Auth::user()->nim)->where('status_aktif','aktif')->first();
        $countNotifikasi = $notifikasi->count();
        view()->share([
            'notifikasi'=>$notifikasi,
            'countNotifikasi'=>$countNotifikasi,
            'pimpinanOrmawa'=>$pimpinanOrmawa
        ]);
        return $next($request);
    }
}
