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
// Login
Route::get('/', 'LoginController@login');
Route::post('/login','LoginController@checkLogin');

// Mahasiswa
Route::group(['prefix' => 'mahasiswa'],function(){
    // Logout
    Route::get('logout','MahasiswaController@logout');

    Route::middleware(['mahasiswa'])->group(function(){
        // Dashboard
        Route::get('/','MahasiswaController@dashboard');
        // Notifikasi
        Route::get('notifikasi/{notifikasi_mahasiswa}','NotifikasiMahasiswaController@show');
        Route::get('notifikasi','NotifikasiMahasiswaController@index');
        // Pengajuan Surat
        Route::group(['prefix' => 'pengajuan'],function(){
            // Surat Keterangan Aktif Kuliah
            Route::get('surat-keterangan-aktif-kuliah','MahasiswaController@pengajuanSuratKeteranganAktif');
            Route::get('surat-keterangan-aktif-kuliah/{pengajuan_surat_keterangan}/progress','MahasiswaController@progressPengajuanSuratKeterangan');
            Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganController@cetakSuratKeteranganAktifKuliah');
            Route::get('surat-keterangan-aktif-kuliah/create','MahasiswaController@createPengajuanSuratKeteranganAktif');
            Route::post('surat-keterangan-aktif-kuliah','MahasiswaController@storePengajuanSuratKeteranganAktif');
             // Surat Keterangan Kelakuan Baik
             Route::get('surat-keterangan-kelakuan-baik','MahasiswaController@pengajuanSuratKeteranganKelakuanBaik');
             Route::get('surat-keterangan-kelakuan-baik/{pengajuan_surat_keterangan}/progress','MahasiswaController@progressPengajuanSuratKeterangan');
             Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganController@cetakSuratKeteranganKelakuanBaik');
             Route::get('surat-keterangan-kelakuan-baik/create','MahasiswaController@createPengajuanSuratKeteranganKelakuanBaik');
             Route::post('surat-keterangan-kelakuan-baik','MahasiswaController@storePengajuanSuratKeteranganKelakuanBaik');
        });
    });
});

// Pegawai
Route::group(['prefix' => 'pegawai'],function(){
    // Logout
    Route::get('logout','UserController@logout');

    Route::middleware(['pegawai'])->group(function(){
        // Dashboard
        Route::get('/','UserController@pegawaiDashboard');
        // Kode Surat
        Route::get('kode-surat/search','KodeSuratController@search');
        Route::resource('kode-surat','KodeSuratController')->except(['show']);
        // Tanda Tangan
        Route::get('tanda-tangan','UserController@indexTandaTangan');
        Route::post('tanda-tangan','UserController@updateTandaTangan');
        // Surat Keterangan Aktif Kuliah
        Route::get('surat-keterangan-aktif-kuliah/search/','SuratKeteranganController@searchSuratKeteranganAktifKuliah');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganController@cetakSuratKeteranganAktifKuliah');
        Route::get('surat-keterangan-aktif-kuliah','SuratKeteranganController@indexSuratKeteranganAktifKuliah');
        Route::post('surat-keterangan-aktif-kuliah','SuratKeteranganController@storeSuratKeteranganAktifKuliah');
        Route::get('surat-keterangan-aktif-kuliah/create','SuratKeteranganController@createSuratKeteranganAktifKuliah');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/edit','SuratKeteranganController@editSuratKeteranganAktifKuliah');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@showSuratKeterangan');
        Route::patch('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@updateSuratKeteranganAktifKuliah');
        Route::delete('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@destroySuratKeteranganAktifKuliah');
        Route::group(['prefix'=>'surat-keterangan-aktif-kuliah/pengajuan'],function(){
            Route::post('tanda-tangan','SuratKeteranganController@tandaTangan');
            Route::patch('tolak-pengajuan/{pengajuan_surat_keterangan}','SuratKeteranganController@tolakPengajuan');
        });
        // Surat Keterangan Kelakuan Baik
        Route::get('surat-keterangan-kelakuan-baik/search/','SuratKeteranganController@searchSuratKeteranganKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganController@cetakSuratKeteranganKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik','SuratKeteranganController@indexSuratKeteranganKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/create','SuratKeteranganController@createSuratKeteranganKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/edit','SuratKeteranganController@editSuratKeteranganKelakuanBaik');
        Route::post('surat-keterangan-kelakuan-baik','SuratKeteranganController@storeSuratKeteranganKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@showSuratKeterangan');
        Route::patch('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@updateSuratKeteranganKelakuanBaik');
        Route::delete('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@destroySuratKeteranganKelakuanBaik');
        Route::group(['prefix'=>'surat-keterangan-kelakuan-baik/pengajuan'],function(){
            Route::post('tanda-tangan','SuratKeteranganController@tandaTanganKelakuanBaik');
            Route::patch('tolak-pengajuan/{pengajuan_surat_keterangan}','SuratKeteranganController@tolakPengajuanKelakuanBaik');
        });
        // Surat Masuk
        Route::get('surat-masuk/search', 'SuratMasukController@search');
        Route::resource('surat-masuk','SuratMasukController');
        // Mahasiswa
        Route::get('detail/mahasiswa/{mahasiswa}','MahasiswaController@show');
        // Notifikasi
        Route::get('notifikasi/{notifikasi_user}','NotifikasiUserController@show');
        Route::get('notifikasi','NotifikasiUserController@index');
        // Profil
        Route::get('profil','UserController@profil');
        Route::get('profil/password','UserController@profilPassword');
        Route::post('profil/password','UserController@updatePassword');
        Route::patch('profil/{user}','UserController@updateProfil');
    });
});

// Admin
Route::group(['prefix' => 'admin'], function () {
    // Login
    Route::get('login','LoginController@loginAdmin');
    Route::post('login','LoginController@checkLoginAdmin');
    Route::get('logout','AdminController@logout');

    Route::middleware(['admin'])->group(function(){   
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
        Route::resource('user','UserController')->except(['show']);
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
        // Ormawa
        Route::get('ormawa/search','OrmawaController@search');
        Route::resource('ormawa','OrmawaController')->except(['show']);
        // Pimpinan Ormawa
        Route::get('pimpinan-ormawa/search','PimpinanOrmawaController@search');
        Route::resource('pimpinan-ormawa','PimpinanOrmawaController')->except(['show']);
        // Profil
        Route::get('profil','AdminController@profil');
        Route::get('profil/password','AdminController@profilPassword');
        Route::post('profil/password','AdminController@updatePassword');
        Route::patch('profil/{admin}','AdminController@update');
    });
});
