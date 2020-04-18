<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\User;
use App\NotifikasiUser;
use Illuminate\Support\Facades\Hash;

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
        
        $notifikasi = NotifikasiUser::all()->where('nip',Session::get('nip'))->where('status','belum dilihat');
        $countNotifikasi = $notifikasi->count();
        view()->share([
            'notifikasi'=>$notifikasi,
            'countNotifikasi'=>$countNotifikasi
        ]);
        return $next($request);
    }
}
