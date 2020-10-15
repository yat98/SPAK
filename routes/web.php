<?php

use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

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

Auth::routes([
    'register' => false,
    'login' => false,
    'reset' => false,
    'verify' => false,
    'confirm' => false,
    'reset'=>false,
    'forget'=>false,
]);

// Login
Route::get('/', 'Auth\LoginController@getLogin')->name('login');
Route::group(['prefix'=>'login'],function(){
    Route::get('/', 'Auth\LoginController@getLogin');
    Route::post('/','Auth\LoginController@postLogin');
});

// Mahasiswa
Route::group(['prefix' => 'mahasiswa'],function(){
    // Logout
    Route::get('logout','Auth\LoginController@postLogout');

    Route::middleware(['auth:mahasiswa','mahasiswa'])->group(function(){
        // Dashboard
        Route::get('/','MahasiswaController@indexMahasiswa');
        Route::group(['prefix' => 'pengajuan'],function(){           
            // Surat Keterangan Lulus
            Route::get('surat-keterangan-lulus','PengajuanSuratKeteranganLulusController@indexMahasiswa');
            Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}/cetak','SuratKeteranganLulusController@cetak');
            Route::get('surat-keterangan-lulus/create','PengajuanSuratKeteranganLulusController@create');
            Route::get('surat-keterangan-lulus/{pengajuan_surat_keterangan_lulus}','PengajuanSuratKeteranganLulusController@show');
            Route::get('surat-keterangan-lulus/{pengajuan_surat_keterangan_lulus}/progress','PengajuanSuratKeteranganLulusController@progress');
            Route::patch('surat-keterangan-lulus/{pengajuan_surat_keterangan_lulus}','PengajuanSuratKeteranganLulusController@update');
            Route::get('surat-keterangan-lulus/{pengajuan_surat_keterangan_lulus}/edit','PengajuanSuratKeteranganLulusController@edit');
            Route::post('surat-keterangan-lulus','PengajuanSuratKeteranganLulusController@store');
            // Surat Permohonan Pengambilan Material
            Route::get('surat-permohonan-pengambilan-material','PengajuanSuratPermohonanPengambilanMaterialController@indexMahasiswa');
            Route::get('surat-permohonan-pengambilan-material/{surat_material}/cetak', 'SuratPermohonanPengambilanMaterialController@cetak');
            Route::get('surat-permohonan-pengambilan-material/create','PengajuanSuratPermohonanPengambilanMaterialController@create');
            Route::get('surat-permohonan-pengambilan-material/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@show');
            Route::get('surat-permohonan-pengambilan-material/{pengajuan_surat_material}/edit','PengajuanSuratPermohonanPengambilanMaterialController@edit');
            Route::post('surat-permohonan-pengambilan-material','PengajuanSuratPermohonanPengambilanMaterialController@store');
            Route::patch('surat-permohonan-pengambilan-material/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@update');
            Route::get('surat-permohonan-pengambilan-material/{pengajuan_surat_material}/progress','PengajuanSuratPermohonanPengambilanMaterialController@progress');
            // Surat Permohonan Survei
            Route::get('surat-permohonan-survei','PengajuanSuratPermohonanSurveiController@indexMahasiswa');
            Route::get('surat-permohonan-survei/{surat_survei}/cetak', 'SuratPermohonanSurveiController@cetak');
            Route::get('surat-permohonan-survei/create','PengajuanSuratPermohonanSurveiController@create');
            Route::get('surat-permohonan-survei/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@show');
            Route::get('surat-permohonan-survei/{pengajuan_surat_survei}/edit','PengajuanSuratPermohonanSurveiController@edit');
            Route::post('surat-permohonan-survei','PengajuanSuratPermohonanSurveiController@store');
            Route::patch('surat-permohonan-survei/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@update');
            Route::get('surat-permohonan-survei/{pengajuan_surat_survei}/progress','PengajuanSuratPermohonanSurveiController@progress');
            // Surat Rekomendasi Penelitian
            Route::get('surat-rekomendasi-penelitian','PengajuanSuratRekomendasiPenelitianController@indexMahasiswa');
            Route::get('surat-rekomendasi-penelitian/{surat_penelitian}/cetak', 'SuratRekomendasiPenelitianController@cetak');
            Route::get('surat-rekomendasi-penelitian/create','PengajuanSuratRekomendasiPenelitianController@create');
            Route::get('surat-rekomendasi-penelitian/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@show');
            Route::get('surat-rekomendasi-penelitian/{pengajuan_surat_penelitian}/edit','PengajuanSuratRekomendasiPenelitianController@edit');
            Route::post('surat-rekomendasi-penelitian','PengajuanSuratRekomendasiPenelitianController@store');
            Route::patch('surat-rekomendasi-penelitian/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@update');
            Route::get('surat-rekomendasi-penelitian/{pengajuan_surat_penelitian}/progress','PengajuanSuratRekomendasiPenelitianController@progress');
            // Surat Permohonan Pengambilan Data Awal
            Route::get('surat-permohonan-pengambilan-data-awal','PengajuanSuratPermohonanPengambilanDataAwalController@indexMahasiswa');
            Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}/cetak', 'SuratPermohonanPengambilanDataAwalController@cetak');
            Route::get('surat-permohonan-pengambilan-data-awal/create','PengajuanSuratPermohonanPengambilanDataAwalController@create');
            Route::get('surat-permohonan-pengambilan-data-awal/{pengajuan_surat_data_awal}','PengajuanSuratPermohonanPengambilanDataAwalController@show');
            Route::get('surat-permohonan-pengambilan-data-awal/{pengajuan_surat_data_awal}/edit','PengajuanSuratPermohonanPengambilanDataAwalController@edit');
            Route::post('surat-permohonan-pengambilan-data-awal','PengajuanSuratPermohonanPengambilanDataAwalController@store');
            Route::patch('surat-permohonan-pengambilan-data-awal/{pengajuan_surat_data_awal}','PengajuanSuratPermohonanPengambilanDataAwalController@update');
            Route::get('surat-permohonan-pengambilan-data-awal/{pengajuan_surat_data_awal}/progress','PengajuanSuratPermohonanPengambilanDataAwalController@progress');
            // Surat Keterangan Bebas Perpustakaan
            Route::get('surat-keterangan-bebas-perpustakaan/{surat_keterangan_perpustakaan}/cetak','SuratKeteranganBebasPerpustakaanController@cetak');
            Route::get('surat-keterangan-bebas-perpustakaan','PengajuanSuratKeteranganBebasPerpustakaanController@index');
            Route::get('surat-keterangan-bebas-perpustakaan/create','PengajuanSuratKeteranganBebasPerpustakaanController@create');
            Route::get('surat-keterangan-bebas-perpustakaan/{pengajuan_surat_perpustakaan}','PengajuanSuratKeteranganBebasPerpustakaanController@show');
            Route::get('surat-keterangan-bebas-perpustakaan/{pengajuan_surat_perpustakaan}/progress','PengajuanSuratKeteranganBebasPerpustakaanController@progress');
            Route::patch('surat-keterangan-bebas-perpustakaan/{pengajuan_surat_perpustakaan}','PengajuanSuratKeteranganBebasPerpustakaanController@update');
            Route::get('surat-keterangan-bebas-perpustakaan/{pengajuan_surat_perpustakaan}/edit','PengajuanSuratKeteranganBebasPerpustakaanController@edit');
            Route::post('surat-keterangan-bebas-perpustakaan','PengajuanSuratKeteranganBebasPerpustakaanController@store');
        });
        // Surat Keterangan Aktif Kuliah
        Route::get('surat-keterangan-aktif-kuliah','PengajuanSuratKeteranganController@indexKeteranganAktifMahasiswa');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@show');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganAktifKuliahController@cetak');
        Route::group(['prefix'=>'surat-keterangan-aktif-kuliah/pengajuan'],function(){
            Route::get('/all','PengajuanSuratKeteranganController@getAllPengajuanAktif');
            Route::get('/create','PengajuanSuratKeteranganController@createPengajuanKeteranganAktif');
            Route::get('/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@show');
            Route::get('/{pengajuan_surat_keterangan}/progress','SuratKeteranganController@progress');
            Route::post('/','PengajuanSuratKeteranganController@storePengajuanKeteranganAktif');
        });
        // Surat Keterangan Kelakuan Baik
        Route::get('surat-keterangan-kelakuan-baik','PengajuanSuratKeteranganController@indexKelakuanBaikMahasiswa');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@show');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganKelakuanBaikController@cetak');
        Route::group(['prefix'=>'surat-keterangan-kelakuan-baik/pengajuan'],function(){
            Route::get('/all', 'PengajuanSuratKeteranganController@getAllPengajuanKelakuanBaik');
            Route::get('/create', 'PengajuanSuratKeteranganController@createPengajuanKelakuanBaik');
            Route::get('/{pengajuan_surat_keterangan}', 'PengajuanSuratKeteranganController@show');
            Route::get('/{pengajuan_surat_keterangan}/progress', 'SuratKeteranganController@progress');
            Route::post('/', 'PengajuanSuratKeteranganController@storePengajuanKelakuanBaik');
        });
        // Surat Dispensasi
        Route::get('surat-dispensasi','SuratDispensasiController@indexMahasiswa');
        Route::get('surat-dispensasi/{surat_dispensasi}','SuratDispensasiController@show');
        Route::get('surat-dispensasi/{surat_dispensasi}/cetak','SuratDispensasiController@cetak');
        Route::group(['prefix'=>'surat-dispensasi/pengajuan'],function(){
            Route::get('/all','PengajuanSuratDispensasiController@getAllPengajuan');
            Route::get('/{pengajuan_surat_dispensasi}','PengajuanSuratDispensasiController@show');
            Route::get('/{pengajuan_surat_dispensasi}/progress','SuratDispensasiController@progress');
        });
        // Surat Rekomendasi
        Route::get('surat-rekomendasi','SuratRekomendasiController@indexMahasiswa');
        Route::get('surat-rekomendasi/{surat_rekomendasi}','SuratRekomendasiController@show');
        Route::get('surat-rekomendasi/{surat_rekomendasi}/cetak','SuratRekomendasiController@cetak');
        Route::group(['prefix'=>'surat-rekomendasi/pengajuan'],function(){
            Route::get('/all','PengajuanSuratRekomendasiController@getAllPengajuan');
            Route::get('/{pengajuan_surat_rekomendasi}','PengajuanSuratRekomendasiController@show');
            Route::get('/{pengajuan_surat_rekomendasi}/progress','SuratRekomendasiController@progress');
        });
        // Surat Tugas
        Route::get('surat-tugas','SuratTugasController@indexMahasiswa');
        Route::get('surat-tugas/all','PengajuanSuratTugasController@getAllPengajuan');
        Route::get('surat-tugas/{surat_tugas}','SuratTugasController@show');
        Route::get('surat-tugas/{surat_tugas}/cetak','SuratTugasController@cetak');
        Route::group(['prefix'=>'surat-tugas/pengajuan'],function(){
            Route::get('/all','PengajuanSuratTugasController@getAllPengajuan');
            Route::get('/{pengajuan_surat_tugas}','PengajuanSuratTugasController@show');
            Route::get('/{pengajuan_surat_tugas}/progress','SuratTugasController@progress');
        });
        // Surat Persetujuan Pindah
        Route::get('surat-persetujuan-pindah','PengajuanSuratPersetujuanPindahController@indexMahasiswa');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}','SuratPersetujuanPindahController@show');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}/cetak','SuratPersetujuanPindahController@cetak');
        Route::group(['prefix'=>'surat-persetujuan-pindah/pengajuan'],function(){
            Route::get('/all','PengajuanSuratPersetujuanPindahController@getAllPengajuan');
            Route::get('/create','PengajuanSuratPersetujuanPindahController@createPengajuan');
            Route::get('/{pengajuan_persetujuan_pindah}','PengajuanSuratPersetujuanPindahController@show');
            Route::get('/{pengajuan_persetujuan_pindah}/progress','SuratPersetujuanPindahController@progress');
            Route::post('/','PengajuanSuratPersetujuanPindahController@storePengajuan');
        });
        // Pendaftaran Cuti
        Route::get('pendaftaran-cuti','PendaftaranCutiController@indexMahasiswa');
        Route::get('pendaftaran-cuti/all','PendaftaranCutiController@getAllPengajuanPendaftaran');
        Route::resource('pendaftaran-cuti','PendaftaranCutiController')->except(['index','destroy']);
        Route::get('surat-kegiatan-mahasiswa','PengajuanSuratKegiatanMahasiswaController@indexMahasiswa');
        // Surat Kegiatan Mahasiswa
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}','SuratKegiatanMahasiswaController@show');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}/cetak','SuratKegiatanMahasiswaController@cetak');
        Route::group(['prefix'=>'surat-kegiatan-mahasiswa/pengajuan'],function(){
            Route::get('/all','PengajuanSuratKegiatanMahasiswaController@getAllPengajuan');
            Route::get('/create','PengajuanSuratKegiatanMahasiswaController@createPengajuan');
            Route::get('/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@show');
            Route::get('/{pengajuan_kegiatan_mahasiswa}/progress','SuratKegiatanMahasiswaController@progress');
            Route::post('/','PengajuanSuratKegiatanMahasiswaController@storePengajuan');
        });
        // Surat Keterangan Lulus
        Route::resource('surat-keterangan-lulus','SuratKeteranganLulusController')->only('show');
        // Surat Permohonan Pengambilan Material
        Route::get('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@show');
        // Surat Permohonan Survei
        Route::resource('surat-permohonan-survei','SuratPermohonanSurveiController')->only('show');
        // Surat Rekomendasi Penelitian
        Route::resource('surat-rekomendasi-penelitian','SuratRekomendasiPenelitianController')->only('show');
        // Surat Permohonan Pengambilan Data Awal
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@show');
        // Notifikasi
        Route::get('notifikasi','NotifikasiMahasiswaController@index');
        Route::get('notifikasi/all','NotifikasiMahasiswaController@getAllNotifikasi');
        Route::get('notifikasi/{notifikasi_mahasiswa}','NotifikasiMahasiswaController@show');
        Route::post('notifikasi/allread','NotifikasiMahasiswaController@allRead');
        Route::post('notifikasi/alldelete','NotifikasiMahasiswaController@allDelete');
        // Password
        Route::get('profil','MahasiswaController@profil');
        Route::patch('profil/{mahasiswa}','MahasiswaController@updateProfil');
        Route::get('profil/password','MahasiswaController@password');
        Route::post('profil/password','MahasiswaController@updatePassword');
    });
});

// Operator
Route::group(['prefix' => 'operator'],function(){
    // Logout
    Route::get('logout','Auth\LoginController@postLogout');

    Route::middleware(['auth:operator','operator'])->group(function(){
        // Dashboard
        Route::get('/','OperatorController@operatorDashboard');
        // Surat Masuk
        Route::get('surat-masuk','SuratMasukController@indexOperator');
        Route::get('surat-masuk/all','SuratMasukController@getAllSuratMasuk');
        Route::resource('surat-masuk','SuratMasukController')->except('index');
        // Surat Keterangan Aktif Kuliah
        Route::get('surat-keterangan-aktif-kuliah','SuratKeteranganAktifKuliahController@indexOperator');
        Route::get('surat-keterangan-aktif-kuliah/all','SuratKeteranganController@getAllSuratKeteranganAktif');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@show');
        Route::get('surat-keterangan-aktif-kuliah/create/{pengajuan_surat_keterangan}','SuratKeteranganAktifKuliahController@createSurat');
        Route::post('surat-keterangan-aktif-kuliah','SuratKeteranganAktifKuliahController@storeSurat');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganAktifKuliahController@cetak');
        Route::group(['prefix'=>'surat-keterangan-aktif-kuliah/pengajuan'],function(){
            Route::get('/all','PengajuanSuratKeteranganController@getAllPengajuanAktif');
            Route::get('/create','PengajuanSuratKeteranganController@createPengajuanKeteranganAktif');
            Route::patch('tolak-pengajuan/{pengajuan_surat_keterangan}','SuratKeteranganAktifKuliahController@tolakPengajuan');
            Route::get('/{pengajuan_surat_keterangan}/progress','SuratKeteranganController@progress');
            Route::get('/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@show');
            Route::get('/{pengajuan_surat_keterangan}/edit','PengajuanSuratKeteranganController@edit');
            Route::patch('/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@update');
            Route::post('/','PengajuanSuratKeteranganController@storePengajuanKeteranganAktif');
            Route::delete('/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@destroy');
        });
        // Surat Keterangan Kelakuan Baik
        Route::get('surat-keterangan-kelakuan-baik','SuratKeteranganKelakuanBaikController@indexOperator');
        Route::get('surat-keterangan-kelakuan-baik/all','SuratKeteranganController@getAllSuratKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@show');
        Route::get('surat-keterangan-kelakuan-baik/create/{pengajuan_surat_keterangan}','SuratKeteranganKelakuanBaikController@createSurat');
        Route::post('surat-keterangan-kelakuan-baik','SuratKeteranganKelakuanBaikController@storeSurat');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganKelakuanBaikController@cetak');
        Route::group(['prefix'=>'surat-keterangan-kelakuan-baik/pengajuan'],function(){
            Route::get('/create','PengajuanSuratKeteranganController@createPengajuanKelakuanBaik');
            Route::get('/all','PengajuanSuratKeteranganController@getAllPengajuanKelakuanBaik');
            Route::patch('/tolak-pengajuan/{pengajuan_surat_keterangan}','SuratKeteranganKelakuanBaikController@tolakPengajuan');
            Route::get('/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@show');
            Route::get('/{pengajuan_surat_keterangan}/progress','SuratKeteranganController@progress');
            Route::get('/{pengajuan_surat_keterangan}/edit','PengajuanSuratKeteranganController@edit');
            Route::patch('/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@update');
            Route::post('/','PengajuanSuratKeteranganController@storePengajuanKelakuanBaik');
            Route::delete('/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@destroy');
        });
        // Surat Dispensasi
        Route::get('surat-dispensasi','SuratDispensasiController@indexOperator');
        Route::get('surat-dispensasi/all','SuratDispensasiController@getAllSuratDispensasi');
        Route::get('surat-dispensasi/{surat_dispensasi}','SuratDispensasiController@show');
        Route::get('surat-dispensasi/create/{pengajuan_surat_dispensasi}','SuratDispensasiController@createSurat');
        Route::post('surat-dispensasi','SuratDispensasiController@storeSurat');
        Route::get('surat-dispensasi/{surat_dispensasi}/cetak','SuratDispensasiController@cetak');
        Route::group(['prefix'=>'surat-dispensasi/pengajuan'],function(){
            Route::get('/create','PengajuanSuratDispensasiController@create');
            Route::get('/all','PengajuanSuratDispensasiController@getAllPengajuan');
            Route::patch('/{pengajuan_surat_dispensasi}','PengajuanSuratDispensasiController@update');
            Route::get('/{pengajuan_surat_dispensasi}/edit','PengajuanSuratDispensasiController@edit');
            Route::get('/{pengajuan_surat_dispensasi}/progress','SuratDispensasiController@progress');
            Route::post('/','PengajuanSuratDispensasiController@store');
            Route::get('/{pengajuan_surat_dispensasi}','PengajuanSuratDispensasiController@show');
            Route::delete('/{pengajuan_surat_dispensasi}','PengajuanSuratDispensasiController@destroy');
        });
        // Surat Rekomendasi
        Route::get('surat-rekomendasi','SuratRekomendasiController@indexOperator');
        Route::get('surat-rekomendasi/all','SuratRekomendasiController@getAllSuratRekomendasi');
        Route::get('surat-rekomendasi/{surat_rekomendasi}','SuratRekomendasiController@show');
        Route::get('/surat-rekomendasi/create/{pengajuan_surat_rekomendasi}','SuratRekomendasiController@createSurat');
        Route::post('surat-rekomendasi','SuratRekomendasiController@storeSurat');
        Route::get('surat-rekomendasi/{surat_rekomendasi}/cetak','SuratRekomendasiController@cetak');
        Route::group(['prefix'=>'surat-rekomendasi/pengajuan'],function(){
             Route::get('/create','PengajuanSuratRekomendasiController@create');
             Route::get('/all','PengajuanSuratRekomendasiController@getAllPengajuan');
             Route::patch('/{pengajuan_surat_rekomendasi}','PengajuanSuratRekomendasiController@update');
             Route::get('/{pengajuan_surat_rekomendasi}/edit','PengajuanSuratRekomendasiController@edit');
             Route::get('/{pengajuan_surat_rekomendasi}/progress','SuratRekomendasiController@progress');
             Route::post('','PengajuanSuratRekomendasiController@store');
             Route::get('/{pengajuan_surat_rekomendasi}','PengajuanSuratRekomendasiController@show');
             Route::delete('/{pengajuan_surat_rekomendasi}','PengajuanSuratRekomendasiController@destroy');
        });
        // Surat Tugas
        Route::get('surat-tugas','SuratTugasController@indexOperator');
        Route::get('surat-tugas/all','SuratTugasController@getAllSuratTugas');
        Route::get('surat-tugas/{surat_tugas}','SuratTugasController@show');
        Route::get('surat-tugas/create/{pengajuan_surat_tugas}','SuratTugasController@createSurat');
        Route::post('surat-tugas','SuratTugasController@storeSurat');
        Route::get('surat-tugas/{surat_tugas}/cetak','SuratTugasController@cetak');
        Route::group(['prefix'=>'surat-tugas/pengajuan'],function(){
             Route::get('/create','PengajuanSuratTugasController@create');
             Route::get('/all','PengajuanSuratTugasController@getAllPengajuan');
             Route::patch('/{pengajuan_surat_tugas}','PengajuanSuratTugasController@update');
             Route::get('/{pengajuan_surat_tugas}/edit','PengajuanSuratTugasController@edit');
             Route::get('/{pengajuan_surat_tugas}/progress','SuratTugasController@progress');
             Route::post('/','PengajuanSuratTugasController@store');
             Route::get('/{pengajuan_surat_tugas}','PengajuanSuratTugasController@show');
             Route::delete('/{pengajuan_surat_tugas}','PengajuanSuratTugasController@destroy');
        });
        // Surat Persetujuan Pindah
        Route::get('surat-persetujuan-pindah','SuratPersetujuanPindahController@indexOperator');
        Route::get('surat-persetujuan-pindah/all','SuratPersetujuanPindahController@getAllSurat');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}','SuratPersetujuanPindahController@show');
        Route::get('surat-persetujuan-pindah/create/{pengajuan_persetujuan_pindah}','SuratPersetujuanPindahController@createSurat');
        Route::post('surat-persetujuan-pindah','SuratPersetujuanPindahController@storeSurat');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}/cetak','SuratPersetujuanPindahController@cetak');
        Route::group(['prefix'=>'surat-persetujuan-pindah/pengajuan'],function(){
            Route::get('/create','PengajuanSuratPersetujuanPindahController@createPengajuan');
            Route::get('/all','PengajuanSuratPersetujuanPindahController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_persetujuan_pindah}','SuratPersetujuanPindahController@tolakPengajuan');
            Route::get('/{pengajuan_persetujuan_pindah}/edit','PengajuanSuratPersetujuanPindahController@edit');
            Route::get('/{pengajuan_persetujuan_pindah}/progress','SuratPersetujuanPindahController@progress');
            Route::get('/{surat_persetujuan_pindah}/cetak','SuratTugasController@cetak');
            Route::get('/{pengajuan_persetujuan_pindah}','PengajuanSuratPersetujuanPindahController@show');
            Route::patch('/{pengajuan_persetujuan_pindah}','PengajuanSuratPersetujuanPindahController@update');
            Route::post('/','PengajuanSuratPersetujuanPindahController@storePengajuan');
            Route::delete('/{pengajuan_persetujuan_pindah}','PengajuanSuratPersetujuanPindahController@destroy');
        });
        // Surat Pengantar Cuti
        Route::get('surat-pengantar-cuti','SuratPengantarCutiController@indexOperator');
        Route::get('surat-pengantar-cuti/all','SuratPengantarCutiController@getAllSurat');
        Route::get('surat-pengantar-cuti/{surat_pengantar_cuti}/cetak', 'SuratPengantarCutiController@cetak');
        Route::resource('surat-pengantar-cuti','SuratPengantarCutiController')->except(['index']);
        // Waktu Cuti
        Route::get('waktu-cuti','WaktuCutiController@indexOperator');
        Route::get('waktu-cuti/all','WaktuCutiController@getAllWaktuCuti');
        Route::resource('waktu-cuti','WaktuCutiController')->except(['index']);
        // Pendaftaran Cuti
        Route::get('pendaftaran-cuti','PendaftaranCutiController@indexOperator');
        Route::get('pendaftaran-cuti/all','PendaftaranCutiController@getAllPendaftaran');
        Route::get('pendaftaran-cuti/pendaftaran/all','PendaftaranCutiController@getAllPengajuanPendaftaran');
        Route::patch('pendaftaran-cuti/verifikasi/{pendaftaran_cuti}', 'PendaftaranCutiController@verification');
        Route::patch('pendaftaran-cuti/tolak/{pendaftaran_cuti}', 'PendaftaranCutiController@tolakPendaftaran');
        Route::resource('pendaftaran-cuti','PendaftaranCutiController')->except(['index']);
        // Surat Pengantar Beasiswa
        Route::get('surat-pengantar-beasiswa','SuratPengantarBeasiswaController@indexOperator');
        Route::get('surat-pengantar-beasiswa/all','SuratPengantarBeasiswaController@getAllSurat');
        Route::get('surat-pengantar-beasiswa/{surat_pengantar_beasiswa}/cetak', 'SuratPengantarBeasiswaController@cetak');
        Route::resource('surat-pengantar-beasiswa','SuratPengantarBeasiswaController')->except(['index']);
        // Surat Kegiatan Mahasiswa
        Route::get('surat-kegiatan-mahasiswa','SuratKegiatanMahasiswaController@indexOperator');
        Route::get('surat-kegiatan-mahasiswa/all','SuratKegiatanMahasiswaController@getAllSurat');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}','SuratKegiatanMahasiswaController@show');
        Route::get('surat-kegiatan-mahasiswa/create/{pengajuan_kegiatan_mahasiswa}','SuratKegiatanMahasiswaController@createSurat');
        Route::get('surat-kegiatan-mahasiswa/disposisi/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@disposisiPengajuan');
        Route::post('surat-kegiatan-mahasiswa','SuratKegiatanMahasiswaController@storeSurat');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}/cetak','SuratKegiatanMahasiswaController@cetak');
        Route::group(['prefix'=>'surat-kegiatan-mahasiswa/pengajuan'],function(){
            Route::get('/create','PengajuanSuratKegiatanMahasiswaController@createPengajuan');
            Route::get('/all','PengajuanSuratKegiatanMahasiswaController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@tolakPengajuan');
            Route::get('/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@show');
            Route::get('/{pengajuan_kegiatan_mahasiswa}/progress','SuratKegiatanMahasiswaController@progress');
            Route::get('/{pengajuan_kegiatan_mahasiswa}/edit','PengajuanSuratKegiatanMahasiswaController@edit');
            Route::patch('/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@update');
            Route::post('/','PengajuanSuratKegiatanMahasiswaController@storePengajuan');
            Route::delete('/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@destroy');
        });
        // Detail Mahasiswa
        Route::get('detail/mahasiswa/{mahasiswa}','MahasiswaController@show');
        // Profil
        Route::get('profil','OperatorController@profil');
        Route::get('profil/password','OperatorController@profilPassword');
        Route::post('profil/password','OperatorController@updatePassword');
        Route::patch('profil/{operator}','OperatorController@updateProfil');
        // Notifikasi
        Route::get('notifikasi','NotifikasiOperatorController@index');
        Route::get('notifikasi/all','NotifikasiOperatorController@getAllNotifikasi');
        Route::get('notifikasi/{notifikasi_operator}','NotifikasiOperatorController@show');
        Route::post('notifikasi/allread','NotifikasiOperatorController@allRead');
        Route::post('notifikasi/alldelete','NotifikasiOperatorController@allDelete');
    });
});

// Pegawai
Route::group(['prefix' => 'pegawai'],function(){
    // Logout
    Route::get('logout','Auth\LoginController@postLogout');

    Route::middleware(['auth:user','pegawai'])->group(function(){
        // Dashboard
        Route::get('/','UserController@indexPegawai');
        // Surat Masuk
        Route::get('surat-masuk/all','SuratMasukController@getAllSuratMasuk');
        Route::resource('surat-masuk','SuratMasukController');
        // Surat Keterangan Aktif Kuliah
        Route::get('surat-keterangan-aktif-kuliah','SuratKeteranganAktifKuliahController@index');
        Route::get('surat-keterangan-aktif-kuliah/all','SuratKeteranganController@getAllSuratKeteranganAktif');
        Route::patch('surat-keterangan-aktif-kuliah/verifikasi','PengajuanSuratKeteranganController@verification');
        Route::get('surat-keterangan-aktif-kuliah/verifikasi/all','PengajuanSuratKeteranganController@getAllPengajuanAktif');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@show');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganAktifKuliahController@cetak');
        // Surat Keterangan Kelakuan Baik
        Route::get('surat-keterangan-kelakuan-baik','SuratKeteranganKelakuanBaikController@index');
        Route::get('surat-keterangan-kelakuan-baik/all','SuratKeteranganController@getAllSuratKelakuanBaik');
        Route::patch('surat-keterangan-kelakuan-baik/verifikasi','PengajuanSuratKeteranganController@verification');
        Route::get('surat-keterangan-kelakuan-baik/verifikasi/all','PengajuanSuratKeteranganController@getAllPengajuanKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@show');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganKelakuanBaikController@cetak');   
        // Surat Dispensasi
        Route::get('surat-dispensasi','SuratDispensasiController@index');
        Route::get('surat-dispensasi/all','SuratDispensasiController@getAllSuratDispensasi');
        Route::patch('surat-dispensasi/verifikasi','PengajuanSuratDispensasiController@verification');
        Route::get('surat-dispensasi/verifikasi/all','PengajuanSuratDispensasiController@getAllPengajuan');
        Route::get('surat-dispensasi/{surat_dispensasi}','SuratDispensasiController@show');
        Route::get('surat-dispensasi/{surat_dispensasi}/cetak','SuratDispensasiController@cetak');
        // Surat Rekomendasi
        Route::get('surat-rekomendasi','SuratRekomendasiController@index');
        Route::get('surat-rekomendasi/all','SuratRekomendasiController@getAllSuratRekomendasi');
        Route::patch('surat-rekomendasi/verifikasi','PengajuanSuratRekomendasiController@verification');
        Route::get('surat-rekomendasi/verifikasi/all','PengajuanSuratRekomendasiController@getAllPengajuan');
        Route::get('surat-rekomendasi/{surat_rekomendasi}','SuratRekomendasiController@show');
        Route::get('surat-rekomendasi/{surat_rekomendasi}/cetak', 'SuratRekomendasiController@cetak');
        // Surat Tugas
        Route::get('surat-tugas','SuratTugasController@index');
        Route::get('surat-tugas/all','SuratTugasController@getAllSuratTugas');
        Route::patch('surat-tugas/verifikasi','PengajuanSuratTugasController@verification');
        Route::get('surat-tugas/verifikasi/all','PengajuanSuratTugasController@getAllPengajuan');
        Route::get('surat-tugas/{surat_tugas}','SuratTugasController@show');
        Route::get('surat-tugas/{surat_tugas}/cetak', 'SuratTugasController@cetak');
        // Surat Persetujuan Pindah
        Route::get('surat-persetujuan-pindah','SuratPersetujuanPindahController@index');
        Route::get('surat-persetujuan-pindah/all','SuratPersetujuanPindahController@getAllSurat');
        Route::patch('surat-persetujuan-pindah/verifikasi','PengajuanSuratPersetujuanPindahController@verification');
        Route::get('surat-persetujuan-pindah/verifikasi/all','PengajuanSuratPersetujuanPindahController@getAllPengajuan');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}','SuratPersetujuanPindahController@show');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}/cetak','SuratPersetujuanPindahController@cetak');
        // Surat Pengantar Cuti
        Route::get('surat-pengantar-cuti/all','SuratPengantarCutiController@getAllSurat');
        Route::patch('surat-pengantar-cuti/verifikasi','SuratPengantarCutiController@verification');
        Route::get('surat-pengantar-cuti/verifikasi/all','SuratPengantarCutiController@getAllPengajuan');
        Route::get('surat-pengantar-cuti/{surat_pengantar_cuti}/cetak', 'SuratPengantarCutiController@cetak');
        Route::resource('surat-pengantar-cuti','SuratPengantarCutiController');
        // Pendaftaran Cuti
        Route::get('pendaftaran-cuti/all','PendaftaranCutiController@getAllPendaftaran');
        Route::get('pendaftaran-cuti/pendaftaran/all','PendaftaranCutiController@getAllPengajuanPendaftaran');
        Route::patch('pendaftaran-cuti/verifikasi/{pendaftaran_cuti}', 'PendaftaranCutiController@verification');
        Route::patch('pendaftaran-cuti/tolak/{pendaftaran_cuti}', 'PendaftaranCutiController@tolakPendaftaran');
        Route::resource('pendaftaran-cuti','PendaftaranCutiController');
        // Waktu Cuti
        Route::get('waktu-cuti/all','WaktuCutiController@getAllWaktuCuti');
        Route::resource('waktu-cuti','WaktuCutiController')->except(['show']);
        // Surat Pengantar Beasiswa
        Route::get('surat-pengantar-beasiswa/all','SuratPengantarBeasiswaController@getAllSurat');
        Route::patch('surat-pengantar-beasiswa/verifikasi','SuratPengantarBeasiswaController@verification');
        Route::get('surat-pengantar-beasiswa/verifikasi/all','SuratPengantarBeasiswaController@getAllPengajuan');
        Route::get('surat-pengantar-beasiswa/{surat_pengantar_beasiswa}/cetak', 'SuratPengantarBeasiswaController@cetak');
        Route::resource('surat-pengantar-beasiswa','SuratPengantarBeasiswaController');
        // Surat Kegiatan Mahasiswa
        Route::get('surat-kegiatan-mahasiswa','SuratKegiatanMahasiswaController@index');
        Route::get('surat-kegiatan-mahasiswa/all','SuratKegiatanMahasiswaController@getAllSurat');
        Route::patch('surat-kegiatan-mahasiswa/verifikasi','PengajuanSuratKegiatanMahasiswaController@verification');
        Route::get('surat-kegiatan-mahasiswa/verifikasi/all','PengajuanSuratKegiatanMahasiswaController@getAllPengajuan');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}','SuratKegiatanMahasiswaController@show');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}/cetak','SuratKegiatanMahasiswaController@cetak');
        // Detail Mahasiswa
        Route::get('detail/mahasiswa/{mahasiswa}','MahasiswaController@show');
        // Surat Keterangan Lulus
        Route::get('surat-keterangan-lulus/search', 'SuratKeteranganLulusController@search');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}/cetak', 'SuratKeteranganLulusController@cetak');
        Route::group(['prefix'=>'surat-keterangan-lulus/pengajuan'],function(){
            Route::get('/{pengajuan_surat_keterangan_lulus}','PengajuanSuratKeteranganLulusController@show');
            Route::post('/','SuratKeteranganLulusController@storeSurat');
            Route::get('create/{pengajuan_surat_keterangan_lulus}','SuratKeteranganLulusController@createSurat');
            Route::patch('tolak-pengajuan/{pengajuan_surat_keterangan_lulus}','SuratKeteranganLulusController@tolakPengajuan');
        });
        Route::resource('surat-keterangan-lulus','SuratKeteranganLulusController');
        // Surat Permohonan Pengambilan Material
        Route::get('surat-permohonan-pengambilan-material','SuratPermohonanPengambilanMaterialController@index');
        Route::post('surat-permohonan-pengambilan-material','SuratPermohonanPengambilanMaterialController@store');
        Route::get('surat-permohonan-pengambilan-material/create','SuratPermohonanPengambilanMaterialController@create');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}/edit','SuratPermohonanPengambilanMaterialController@edit');
        Route::get('surat-permohonan-pengambilan-material/search', 'SuratPermohonanPengambilanMaterialController@search');
        Route::patch('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@update');
        Route::delete('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@destroy');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@show');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}/cetak', 'SuratPermohonanPengambilanMaterialController@cetak');
        Route::group(['prefix'=>'surat-permohonan-pengambilan-material/pengajuan'],function(){
            Route::get('/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@show');
            Route::post('/','SuratPermohonanPengambilanMaterialController@storeSurat');
            Route::get('create/{pengajuan_surat_material}','SuratPermohonanPengambilanMaterialController@createSurat');
            Route::patch('tolak-pengajuan/{pengajuan_surat_material}','SuratPermohonanPengambilanMaterialController@tolakPengajuan');
        });
        // Surat Permohonan Survei
        Route::get('surat-permohonan-survei/search', 'SuratPermohonanSurveiController@search');
        Route::get('surat-permohonan-survei/{surat_survei}/cetak', 'SuratPermohonanSurveiController@cetak');
        Route::group(['prefix'=>'surat-permohonan-survei/pengajuan'],function(){
            Route::post('/','SuratPermohonanSurveiController@storeSurat');
            Route::get('/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@show');
            Route::get('create/{pengajuan_surat_survei}','SuratPermohonanSurveiController@createSurat');
            Route::patch('tolak-pengajuan/{pengajuan_surat_survei}','SuratPermohonanSurveiController@tolakPengajuan');
        });
        Route::resource('surat-permohonan-survei','SuratPermohonanSurveiController');
        // Surat Rekomedasi penelitian
        Route::get('surat-rekomendasi-penelitian/search', 'SuratRekomendasiPenelitianController@search');
        Route::get('surat-rekomendasi-penelitian/{surat_penelitian}/cetak', 'SuratRekomendasiPenelitianController@cetak');
        Route::group(['prefix'=>'surat-rekomendasi-penelitian/pengajuan'],function(){
            Route::get('/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@show');
            Route::post('/','SuratRekomendasiPenelitianController@storeSurat');
            Route::get('create/{pengajuan_surat_penelitian}','SuratRekomendasiPenelitianController@createSurat');
            Route::patch('tolak-pengajuan/{pengajuan_surat_penelitian}','SuratRekomendasiPenelitianController@tolakPengajuan');
        });
        Route::resource('surat-rekomendasi-penelitian','SuratRekomendasiPenelitianController');
        // Surat Permohonan Pengambilan Data Awal
        Route::get('surat-permohonan-pengambilan-data-awal','SuratPermohonanPengambilanDataAwalController@index');
        Route::delete('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@destroy');
        Route::post('surat-permohonan-pengambilan-data-awal','SuratPermohonanPengambilanDataAwalController@store');
        Route::get('surat-permohonan-pengambilan-data-awal/create','SuratPermohonanPengambilanDataAwalController@create');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}/edit','SuratPermohonanPengambilanDataAwalController@edit');
        Route::get('surat-permohonan-pengambilan-data-awal/search', 'SuratPermohonanPengambilanDataAwalController@search');
        Route::patch('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@update');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@show');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}/cetak', 'SuratPermohonanPengambilanDataAwalController@cetak');
        Route::group(['prefix'=>'surat-permohonan-pengambilan-data-awal/pengajuan'],function(){
            Route::get('/{pengajuan_surat_data_awal}','PengajuanSuratPermohonanPengambilanDataAwalController@show');
            Route::post('/','SuratPermohonanPengambilanDataAwalController@storeSurat');
            Route::get('create/{pengajuan_surat_data_awal}','SuratPermohonanPengambilanDataAwalController@createSurat');
            Route::patch('tolak-pengajuan/{pengajuan_surat_data_awal}','SuratPermohonanPengambilanDataAwalController@tolakPengajuan');
        });
        // Surat Keterangan Bebas Perpustakaan
        Route::get('surat-keterangan-bebas-perpustakaan','SuratKeteranganBebasPerpustakaanController@index');
        // Laporan
        Route::get('laporan','LaporanController@index');
        Route::post('laporan','LaporanController@post');
        // Notifikasi
        Route::get('notifikasi','NotifikasiUserController@index');
        Route::get('notifikasi/all','NotifikasiUserController@getAllNotifikasi');
        Route::get('notifikasi/{notifikasi_user}','NotifikasiUserController@show');
        Route::post('notifikasi/allread','NotifikasiUserController@allRead');
        Route::post('notifikasi/alldelete','NotifikasiUserController@allDelete');
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
    Route::get('login','Auth\AdminAuthController@getLogin');
    Route::post('login','Auth\AdminAuthController@postLogin');
    Route::get('logout','Auth\AdminAuthController@postLogout');

    Route::middleware(['auth:admin'])->group(function(){   
        // Dashbord
        Route::get('/', 'AdminController@index');
        // Jurusan
        Route::get('jurusan/all','JurusanController@getAllJurusan');
        Route::get('jurusan/limit','JurusanController@getLimitJurusan');
        Route::resource('jurusan', 'JurusanController');
        // Program Studi
        Route::get('program-studi/all','ProgramStudiController@getAllProdi');
        Route::get('program-studi/limit','ProgramStudiController@getLimitProdi');
        Route::resource('program-studi', 'ProgramStudiController');
        // Tahun Akademik
        Route::get('tahun-akademik/all','TahunAkademikController@getAllTahunAkademik');
        Route::get('tahun-akademik/limit','TahunAkademikController@getLimitTahunAkademik');
        Route::resource('tahun-akademik', 'TahunAkademikController');
        // Operator
        Route::get('operator/all','OperatorController@getAllOperator');
        Route::get('operator/limit','OperatorController@getLimitOperator');
        Route::resource('operator','OperatorController');
        // Mahasiswa
        Route::get('mahasiswa/all','MahasiswaController@getAllMahasiswa');
        Route::get('mahasiswa/limit','MahasiswaController@getLimitMahasiswa');
        Route::post('mahasiswa/import-mahasiswa','MahasiswaController@storeImport');
        Route::get('mahasiswa/import-mahasiswa','MahasiswaController@createImport');
        Route::resource('mahasiswa', 'MahasiswaController');
        // User
        Route::get('user/all','UserController@getAllUser');
        Route::get('user/limit','UserController@getLimitUser');
        Route::resource('user','UserController');
        // Status Mahasiswa
        Route::post('status-mahasiswa/import-status-mahasiswa','StatusMahasiswaController@storeImport');
        Route::get('status-mahasiswa/import-status-mahasiswa','StatusMahasiswaController@createImport');
        Route::get('status-mahasiswa/all','StatusMahasiswaController@getAllStatusMahasiswa');
        Route::get('status-mahasiswa/limit','StatusMahasiswaController@getLimitStatusMahasiswa');
        Route::get('status-mahasiswa/{id_tahun_akademik}/{nim}/','StatusMahasiswaController@show');
        Route::post('status-mahasiswa','StatusMahasiswaController@store');
        Route::get('status-mahasiswa','StatusMahasiswaController@index');
        Route::get('status-mahasiswa/create','StatusMahasiswaController@create');
        Route::patch('status-mahasiswa','StatusMahasiswaController@update');
        Route::delete('status-mahasiswa','StatusMahasiswaController@destroy');
        Route::get('status-mahasiswa/{id_tahun_akademik}/{nim}/edit','StatusMahasiswaController@edit');
        // Ormawa
        Route::get('ormawa/all','OrmawaController@getAllOrmawa');
        Route::get('ormawa/limit','OrmawaController@getLimitOrmawa');
        Route::resource('ormawa','OrmawaController');
        // Pimpinan Ormawa
        Route::get('pimpinan-ormawa/all','PimpinanOrmawaController@getAllPimpinanOrmawa');
        Route::get('pimpinan-ormawa/limit','PimpinanOrmawaController@getLimitPimpinanOrmawa');
        Route::resource('pimpinan-ormawa','PimpinanOrmawaController');
        // Profil
        Route::get('profil','AdminController@profil');
        Route::get('profil/password','AdminController@profilPassword');
        Route::post('profil/password','AdminController@updatePassword');
        Route::patch('profil/{admin}','AdminController@update');
    });
});

// Pimpinan
Route::group(['prefix' => 'pimpinan'], function () {
    // Logout
    Route::get('logout','Auth\LoginController@postLogout');
    
    Route::middleware(['auth:user','pimpinan'])->group(function(){
        // Dashboard
        Route::get('/','UserController@pimpinanDashboard');
        Route::get('search','UserController@chartPimpinanDashboard');
        // Kode Surat
        Route::get('kode-surat/all','KodeSuratController@getAllKodeSurat');
        Route::get('kode-surat/limit','KodeSuratController@getLimitKodeSurat');
        Route::resource('kode-surat','KodeSuratController');
        // Surat Masuk
        Route::get('surat-masuk','SuratMasukController@indexPimpinan');
        Route::get('surat-masuk/all','SuratMasukController@getAllSuratMasuk');
        Route::resource('surat-masuk','SuratMasukController')->except('index');
        // Surat Keterangan Aktif Kuliah
        Route::get('surat-keterangan-aktif-kuliah','SuratKeteranganAktifKuliahController@indexPimpinan');
        Route::get('surat-keterangan-aktif-kuliah/all','SuratKeteranganController@getAllSuratKeteranganAktif');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganAktifKuliahController@cetak');
        Route::get('surat-keterangan-aktif-kuliah/verifikasi/all','PengajuanSuratKeteranganController@getAllPengajuanAktif');
        Route::get('surat-keterangan-aktif-kuliah/tanda-tangan/all','SuratKeteranganController@getAllTandaTanganKeteranganAktif');
        Route::post('surat-keterangan-aktif-kuliah/tanda-tangan','SuratKeteranganAktifKuliahController@tandaTangan');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@show');
        Route::patch('surat-keterangan-aktif-kuliah/verifikasi','PengajuanSuratKeteranganController@verification'); 
        // Surat Keterangan Kelakuan Baik
        Route::get('surat-keterangan-kelakuan-baik','SuratKeteranganKelakuanBaikController@indexPimpinan');
        Route::get('surat-keterangan-kelakuan-baik/all','SuratKeteranganController@getAllSuratKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganKelakuanBaikController@cetak');
        Route::get('surat-keterangan-kelakuan-baik/verifikasi/all','PengajuanSuratKeteranganController@getAllPengajuanKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/tanda-tangan/all','SuratKeteranganController@getAllTandaTanganKelakuanBaik');
        Route::post('surat-keterangan-kelakuan-baik/tanda-tangan','SuratKeteranganKelakuanBaikController@tandaTangan');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@show');
        Route::patch('surat-keterangan-kelakuan-baik/verifikasi','PengajuanSuratKeteranganController@verification'); 
        // Surat Dispensasi
        Route::get('surat-dispensasi', 'SuratDispensasiController@indexPimpinan');
        Route::get('surat-dispensasi/all','SuratDispensasiController@getAllSuratDispensasi');
        Route::get('surat-dispensasi/{surat_dispensasi}/cetak','SuratDispensasiController@cetak');
        Route::get('surat-dispensasi/verifikasi/all','PengajuanSuratDispensasiController@getAllPengajuan');
        Route::get('surat-dispensasi/tanda-tangan/all','SuratDispensasiController@getAllTandaTangan');
        Route::post('surat-dispensasi/tanda-tangan','SuratDispensasiController@tandaTangan');
        Route::get('surat-dispensasi/{surat_dispensasi}','SuratDispensasiController@show');
        Route::patch('surat-dispensasi/verifikasi','PengajuanSuratDispensasiController@verification'); 
        // Surat Rekomendasi
        Route::get('surat-rekomendasi', 'SuratRekomendasiController@indexPimpinan');
        Route::get('surat-rekomendasi/all','SuratRekomendasiController@getAllSuratRekomendasi');
        Route::get('surat-rekomendasi/{surat_rekomendasi}/cetak','SuratRekomendasiController@cetak');
        Route::get('surat-rekomendasi/verifikasi/all','PengajuanSuratRekomendasiController@getAllPengajuan');
        Route::get('surat-rekomendasi/tanda-tangan/all','SuratRekomendasiController@getAllTandaTangan');
        Route::patch('surat-rekomendasi/verifikasi','PengajuanSuratRekomendasiController@verification'); 
        Route::get('surat-rekomendasi/{surat_rekomendasi}','SuratRekomendasiController@show');
        Route::post('surat-rekomendasi/tanda-tangan','SuratRekomendasiController@tandaTangan');
        // Surat Tugas
        Route::get('surat-tugas', 'SuratTugasController@indexPimpinan');
        Route::get('surat-tugas/all','SuratTugasController@getAllSuratTugas');
        Route::get('surat-tugas/{surat_tugas}/cetak','SuratTugasController@cetak');
        Route::get('surat-tugas/verifikasi/all','PengajuanSuratTugasController@getAllPengajuan');
        Route::get('surat-tugas/tanda-tangan/all','SuratTugasController@getAllTandaTangan');
        Route::patch('surat-tugas/verifikasi','PengajuanSuratTugasController@verification'); 
        Route::get('surat-tugas/{surat_tugas}','SuratTugasController@show');
        Route::post('surat-tugas/tanda-tangan','SuratTugasController@tandaTangan');
        // Surat Persetujuan Pindah
        Route::get('surat-persetujuan-pindah', 'SuratPersetujuanPindahController@indexPimpinan');
        Route::get('surat-persetujuan-pindah/all','SuratPersetujuanPindahController@getAllSurat');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}/cetak','SuratPersetujuanPindahController@cetak');
        Route::get('surat-persetujuan-pindah/verifikasi/all','PengajuanSuratPersetujuanPindahController@getAllPengajuan');
        Route::get('surat-persetujuan-pindah/tanda-tangan/all','SuratPersetujuanPindahController@getAllTandaTangan');
        Route::post('surat-persetujuan-pindah/tanda-tangan','SuratPersetujuanPindahController@tandaTangan');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}','SuratPersetujuanPindahController@show');
        Route::patch('surat-persetujuan-pindah/verifikasi','PengajuanSuratPersetujuanPindahController@verification');
        // Surat Pengantar Cuti
        Route::get('surat-pengantar-cuti', 'SuratPengantarCutiController@indexPimpinan');
        Route::get('surat-pengantar-cuti/all','SuratPengantarCutiController@getAllSurat');
        Route::get('surat-pengantar-cuti/{surat_pengantar_cuti}/cetak','SuratPengantarCutiController@cetak');
        Route::get('surat-pengantar-cuti/verifikasi/all','SuratPengantarCutiController@getAllPengajuan');
        Route::get('surat-pengantar-cuti/tanda-tangan/all','SuratPengantarCutiController@getAllTandaTangan');
        Route::post('surat-pengantar-cuti/tanda-tangan','SuratPengantarCutiController@tandaTangan');
        Route::patch('surat-pengantar-cuti/verifikasi','SuratPengantarCutiController@verification');
        Route::resource('surat-pengantar-cuti','SuratPengantarCutiController')->except('index');
        // Waktu Cuti
        Route::get('waktu-cuti','WaktuCutiController@indexPimpinan');
        Route::get('waktu-cuti/all','WaktuCutiController@getAllWaktuCuti');
        Route::resource('waktu-cuti','WaktuCutiController')->except(['show']);
        // Pendaftaran Cuti
        Route::get('pendaftaran-cuti','PendaftaranCutiController@indexPimpinan');
        Route::get('pendaftaran-cuti/all','PendaftaranCutiController@getAllPendaftaran');
        Route::resource('pendaftaran-cuti','PendaftaranCutiController')->only('show');
        //  Surat Pengantar Beasiswa
        Route::get('surat-pengantar-beasiswa', 'SuratPengantarBeasiswaController@indexPimpinan');
        Route::get('surat-pengantar-beasiswa/all','SuratPengantarBeasiswaController@getAllSurat');
        Route::get('surat-pengantar-beasiswa/{surat_pengantar_beasiswa}/cetak','SuratPengantarBeasiswaController@cetak');
        Route::get('surat-pengantar-beasiswa/verifikasi/all','SuratPengantarBeasiswaController@getAllPengajuan');
        Route::get('surat-pengantar-beasiswa/tanda-tangan/all','SuratPengantarBeasiswaController@getAllTandaTangan');
        Route::post('surat-pengantar-beasiswa/tanda-tangan','SuratPengantarBeasiswaController@tandaTangan');
        Route::patch('surat-pengantar-beasiswa/verifikasi','SuratPengantarBeasiswaController@verification');
        Route::resource('surat-pengantar-beasiswa','SuratPengantarBeasiswaController')->except('index');
        //  Surat Kegiatan Mahasiswa
        Route::get('surat-kegiatan-mahasiswa','SuratKegiatanMahasiswaController@indexPimpinan');
        Route::get('surat-kegiatan-mahasiswa/all','SuratKegiatanMahasiswaController@getAllSurat');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}/cetak','SuratKegiatanMahasiswaController@cetak');
        Route::get('surat-kegiatan-mahasiswa/verifikasi/all','PengajuanSuratKegiatanMahasiswaController@getAllPengajuan');
        Route::get('surat-kegiatan-mahasiswa/disposisi/all','PengajuanSuratKegiatanMahasiswaController@getAllDisposisiPimpinan');
        Route::get('surat-kegiatan-mahasiswa/disposisi/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@createDisposisi');
        Route::get('surat-kegiatan-mahasiswa/pengajuan/disposisi/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@showDisposisi');
        Route::post('surat-kegiatan-mahasiswa/disposisi','PengajuanSuratKegiatanMahasiswaController@storeDisposisi');
        Route::get('surat-kegiatan-mahasiswa/tanda-tangan/all','PengajuanSuratKegiatanMahasiswaController@getAllTandaTangan');
        Route::post('surat-kegiatan-mahasiswa/tanda-tangan','SuratKegiatanMahasiswaController@tandaTangan');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}','SuratKegiatanMahasiswaController@show');
        Route::get('surat-kegiatan-mahasiswa/pengajuan/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@show');
        Route::patch('surat-kegiatan-mahasiswa/verifikasi','PengajuanSuratKegiatanMahasiswaController@verification');
        // Mahasiswa
        Route::get('mahasiswa/search', 'MahasiswaController@search');
        Route::get('mahasiswa', 'MahasiswaController@indexPimpinan');
        Route::get('detail/mahasiswa/{mahasiswa}','MahasiswaController@show');
        Route::get('mahasiswa/{mahasiswa}','MahasiswaController@showPimpinan');
        // Surat Keterangan Lulus
        Route::get('surat-keterangan-lulus/search', 'SuratKeteranganLulusController@searchPimpinan');
        Route::get('surat-keterangan-lulus', 'SuratKeteranganLulusController@indexPimpinan');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}','SuratKeteranganLulusController@show');
        Route::post('surat-keterangan-lulus/pengajuan/tanda-tangan','SuratKeteranganLulusController@tandaTanganLulus');
        // Surat Permohonan Pengambilan Material
        Route::get('surat-permohonan-pengambilan-material','SuratPermohonanPengambilanMaterialController@indexPimpinan');
        Route::get('surat-permohonan-pengambilan-material/search', 'SuratPermohonanPengambilanMaterialController@searchPimpinan');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@show');
        Route::post('surat-permohonan-pengambilan-material/pengajuan/tanda-tangan','SuratPermohonanPengambilanMaterialController@tandaTanganMaterial');
        // Surat Permohonan Survei
        Route::get('surat-permohonan-survei','SuratPermohonanSurveiController@indexPimpinan');
        Route::get('surat-permohonan-survei/search', 'SuratPermohonanSurveiController@searchPimpinan');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}','SuratPermohonanSurveiController@show');
        Route::post('surat-permohonan-survei/pengajuan/tanda-tangan','SuratPermohonanSurveiController@tandaTanganSurvei');
        // Surat Rekomendasi Penelitian
        Route::get('surat-rekomendasi-penelitian','SuratRekomendasiPenelitianController@indexPimpinan');
        Route::get('surat-rekomendasi-penelitian/search', 'SuratRekomendasiPenelitianController@searchPimpinan');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}','SuratRekomendasiPenelitianController@show');
        Route::post('surat-rekomendasi-penelitian/pengajuan/tanda-tangan','SuratRekomendasiPenelitianController@tandaTanganPenelitian');
        // Surat Permohonan Pengambilan Data Awal
        Route::get('surat-permohonan-pengambilan-data-awal','SuratPermohonanPengambilanDataAwalController@indexPimpinan');
        Route::get('surat-permohonan-pengambilan-data-awal/search', 'SuratPermohonanPengambilanDataAwalController@searchPimpinan');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@show');
        Route::post('surat-permohonan-pengambilan-data-awal/pengajuan/tanda-tangan','SuratPermohonanPengambilanDataAwalController@tandaTanganDataAwal');
         // Notifikasi
        Route::get('notifikasi','NotifikasiUserController@index');
        Route::get('notifikasi/all','NotifikasiUserController@getAllNotifikasi');
        Route::get('notifikasi/{notifikasi_user}','NotifikasiUserController@show');
        Route::post('notifikasi/allread','NotifikasiUserController@allRead');
        Route::post('notifikasi/alldelete','NotifikasiUserController@allDelete');
        // Tanda Tangan
        Route::get('tanda-tangan','UserController@indexTandaTangan');
        Route::post('tanda-tangan','UserController@updateTandaTangan');
        // Profil
        Route::get('profil','UserController@profil');
        Route::get('profil/password','UserController@profilPassword');
        Route::post('profil/password','UserController@updatePassword');
        Route::patch('profil/{user}','UserController@updateProfil');
    });
});
