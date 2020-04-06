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
    // Login
    Route::get('login','LoginController@loginAdmin');
    Route::post('login','LoginController@checkLoginAdmin');
    Route::get('logout','AdminController@logout');
    // Dashbord
    Route::get('/', 'AdminController@index');
    // Jurusan
    Route::get('jurusan/search', 'JurusanController@search');
    Route::resource('jurusan', 'JurusanController')->except(['show']);
    // Program Studi
    Route::get('program-studi/search', 'ProgramStudiController@search');
    Route::resource('program-studi', 'ProgramStudiController')->except(['show']);
    // Tahun Akademik
    Route::get('tahun-akademik/search', 'TahunAkademikController@search');
    Route::resource('tahun-akademik', 'TahunAkademikController')->except(['show']);
    // Mahasiswa
    Route::get('mahasiswa/search', 'MahasiswaController@search');
    Route::post('mahasiswa/import-mahasiswa','MahasiswaController@storeImport');
    Route::get('mahasiswa/import-mahasiswa','MahasiswaController@createImport');
    Route::resource('mahasiswa', 'MahasiswaController');
    // User
    Route::get('user/search','UserController@search');
    Route::resource('user','UserController');
    // Status Mahasiswa
    Route::post('status-mahasiswa/import-status-mahasiswa','StatusMahasiswaController@storeImport');
    Route::get('status-mahasiswa/import-status-mahasiswa','StatusMahasiswaController@createImport');
    Route::get('status-mahasiswa/search','StatusMahasiswaController@search');
    Route::post('status-mahasiswa','StatusMahasiswaController@store');
    Route::get('status-mahasiswa','StatusMahasiswaController@index');
    Route::get('status-mahasiswa/create','StatusMahasiswaController@create');
    Route::patch('status-mahasiswa','StatusMahasiswaController@update');
    Route::delete('status-mahasiswa','StatusMahasiswaController@destroy');
    Route::get('status-mahasiswa/{id_tahun_akademik}/{nim}/edit','StatusMahasiswaController@edit');
    // Profil
    Route::get('profil','AdminController@profil');
    Route::post('profil','AdminController@update');
    Route::get('profil/password','AdminController@profilPassword');
    Route::post('profil/password','AdminController@updatePassword');
    Route::patch('profil/{admin}','AdminController@update');
});
