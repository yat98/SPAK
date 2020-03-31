<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class NavServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $halaman = '';
        $segment = request()->segment(2);
        if(request()->segment(1) == 'admin'){    
            if($segment == ''){
                $halaman = 'dashboard-admin';
            }
            if($segment == 'jurusan'){
                $halaman = 'jurusan';
            }
            if($segment == 'program-studi'){
                $halaman = 'program-studi';
            }
            if($segment == 'tahun-akademik'){
                $halaman = 'tahun-akademik';
            }
            if($segment == 'mahasiswa'){
                $halaman = 'mahasiswa';
            }
        }
        view()->share('halaman',$halaman);
    }
}