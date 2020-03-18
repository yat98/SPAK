<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login.login');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('dashboard', function(){
        return view('user.admin.dashboard');
    });
    Route::resource('jurusan', 'JurusanController')->except(['show']);
    Route::resource('program-studi', 'ProgramStudiController')->except(['show']);
    Route::resource('tahun-akademik', 'TahunAkademikController')->except(['show']);
    Route::resource('mahasiswa', 'MahasiswaController');
});
