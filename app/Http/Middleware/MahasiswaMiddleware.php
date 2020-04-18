<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\NotifikasiMahasiswa;

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
        if(Session::get('status') != 'mahasiswa'){
            return redirect('/');
        }

        $notifikasi = NotifikasiMahasiswa::all()->where('nim',Session::get('nim'))->where('status','belum dilihat');
        $countNotifikasi = $notifikasi->count();
        view()->share([
            'notifikasi'=>$notifikasi,
            'countNotifikasi'=>$countNotifikasi
        ]);
        return $next($request);
    }
}
