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
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan_lulus}','SuratKeteranganController@show');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan_lulus}/cetak','SuratKeteranganKelakuanBaikController@cetak');
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
        Route::get('surat-keterangan-lulus','PengajuanSuratKeteranganLulusController@indexMahasiswa');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}','SuratKeteranganLulusController@show');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}/cetak','SuratKeteranganLulusController@cetak');
        Route::group(['prefix'=>'surat-keterangan-lulus/pengajuan'],function(){
            Route::get('/all', 'PengajuanSuratKeteranganLulusController@getAllPengajuan');
            Route::get('/create', 'PengajuanSuratKeteranganLulusController@createPengajuan');
            Route::get('/{pengajuan_surat_lulus}', 'PengajuanSuratKeteranganLulusController@show');
            Route::get('/{pengajuan_surat_lulus}/progress', 'SuratKeteranganLulusController@progress');
            Route::post('/', 'PengajuanSuratKeteranganLulusController@storePengajuan');
        });
        // Surat Permohonan Pengambilan Material
        Route::get('surat-permohonan-pengambilan-material','PengajuanSuratPermohonanPengambilanMaterialController@indexMahasiswa');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@show');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}/cetak','SuratPermohonanPengambilanMaterialController@cetak');
        Route::group(['prefix'=>'surat-permohonan-pengambilan-material/pengajuan'],function(){
            Route::get('/all', 'PengajuanSuratPermohonanPengambilanMaterialController@getAllPengajuan');
            Route::get('/create', 'PengajuanSuratPermohonanPengambilanMaterialController@createPengajuan');
            Route::get('/{pengajuan_surat_material}', 'PengajuanSuratPermohonanPengambilanMaterialController@show');
            Route::get('/{pengajuan_surat_material}/progress', 'SuratPermohonanPengambilanMaterialController@progress');
            Route::post('/', 'PengajuanSuratPermohonanPengambilanMaterialController@storePengajuan');
        });
        // Surat Permohonan Survei
        Route::get('surat-permohonan-survei','PengajuanSuratPermohonanSurveiController@indexMahasiswa');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}','SuratPermohonanSurveiController@show');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}/cetak','SuratPermohonanSurveiController@cetak');
        Route::group(['prefix'=>'surat-permohonan-survei/pengajuan'],function(){
            Route::get('/all', 'PengajuanSuratPermohonanSurveiController@getAllPengajuan');
            Route::get('/create', 'PengajuanSuratPermohonanSurveiController@createPengajuan');
            Route::get('/{pengajuan_surat_survei}', 'PengajuanSuratPermohonanSurveiController@show');
            Route::get('/{pengajuan_surat_survei}/progress', 'SuratPermohonanSurveiController@progress');
            Route::post('/', 'PengajuanSuratPermohonanSurveiController@storePengajuan');
        });
        // Surat Rekomendasi Penelitian
        Route::get('surat-rekomendasi-penelitian','PengajuanSuratRekomendasiPenelitianController@indexMahasiswa');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}','SuratRekomendasiPenelitianController@show');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}/cetak','SuratRekomendasiPenelitianController@cetak');
        Route::group(['prefix'=>'surat-rekomendasi-penelitian/pengajuan'],function(){
            Route::get('/all', 'PengajuanSuratRekomendasiPenelitianController@getAllPengajuan');
            Route::get('/create', 'PengajuanSuratRekomendasiPenelitianController@createPengajuan');
            Route::get('/{pengajuan_surat_penelitian}', 'PengajuanSuratRekomendasiPenelitianController@show');
            Route::get('/{pengajuan_surat_penelitian}/progress', 'SuratRekomendasiPenelitianController@progress');
            Route::post('/', 'PengajuanSuratRekomendasiPenelitianController@storePengajuan');
        });
        // Surat Permohonoan Pengambilan Data Awal
        Route::get('surat-permohonan-pengambilan-data-awal','PengajuanSuratPermohonanPengambilanDataAwalController@indexMahasiswa');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@show');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}/cetak','SuratPermohonanPengambilanDataAwalController@cetak');
        Route::group(['prefix'=>'surat-permohonan-pengambilan-data-awal/pengajuan'],function(){
            Route::get('/all', 'PengajuanSuratPermohonanPengambilanDataAwalController@getAllPengajuan');
            Route::get('/create', 'PengajuanSuratPermohonanPengambilanDataAwalController@createPengajuan');
            Route::get('/{pengajuan_surat_data_awal}', 'PengajuanSuratPermohonanPengambilanDataAwalController@show');
            Route::get('/{pengajuan_surat_data_awal}/progress', 'SuratPermohonanPengambilanDataAwalController@progress');
            Route::post('/', 'PengajuanSuratPermohonanPengambilanDataAwalController@storePengajuan');
        });
        // Surat Keterangan Bebas Perlengkapan
        Route::get('surat-keterangan-bebas-perlengkapan','PengajuanSuratKeteranganBebasPerlengkapanController@indexMahasiswa');
        Route::get('surat-keterangan-bebas-perlengkapan/{surat_perlengkapan}','SuratKeteranganBebasPerlengkapanController@show');
        Route::get('surat-keterangan-bebas-perlengkapan/{surat_perlengkapan}/cetak','SuratKeteranganBebasPerlengkapanController@cetak');
        Route::group(['prefix'=>'surat-keterangan-bebas-perlengkapan/pengajuan'],function(){
            Route::get('/all','PengajuanSuratKeteranganBebasPerlengkapanController@getAllPengajuan');
            Route::get('/create','PengajuanSuratKeteranganBebasPerlengkapanController@createPengajuan');
            Route::get('/{pengajuan_surat_perlengkapan}','PengajuanSuratKeteranganBebasPerlengkapanController@show');
            Route::get('/{pengajuan_surat_perlengkapan}/progress','SuratKeteranganBebasPerlengkapanController@progress');
            Route::post('/','PengajuanSuratKeteranganBebasPerlengkapanController@storePengajuan');
        });
        // Surat Keterangan Bebas Perpustakaan
        Route::get('surat-keterangan-bebas-perpustakaan','PengajuanSuratKeteranganBebasPerpustakaanController@indexMahasiswa');
        Route::get('surat-keterangan-bebas-perpustakaan/{surat_perpustakaan}','SuratKeteranganBebasPerpustakaanController@show');
        Route::get('surat-keterangan-bebas-perpustakaan/{surat_perpustakaan}/cetak','SuratKeteranganBebasPerpustakaanController@cetak');
        Route::group(['prefix'=>'surat-keterangan-bebas-perpustakaan/pengajuan'],function(){
            Route::get('/all','PengajuanSuratKeteranganBebasPerpustakaanController@getAllPengajuan');
            Route::get('/create','PengajuanSuratKeteranganBebasPerpustakaanController@createPengajuan');
            Route::get('/{pengajuan_surat_perpustakaan}','PengajuanSuratKeteranganBebasPerpustakaanController@show');
            Route::get('/{pengajuan_surat_perpustakaan}/progress','SuratKeteranganBebasPerpustakaanController@progress');
            Route::post('/','PengajuanSuratKeteranganBebasPerpustaka
            anController@storePengajuan');
        });
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
        Route::get('/','OperatorController@indexOperator');
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
            Route::get('/{pengajuan_persetujuan_pindah}/progress','SuratKeteranganLulusController@progress');
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
            Route::get('/disposisi/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@showDisposisi');
            Route::get('/create','PengajuanSuratKegiatanMahasiswaController@createPengajuan');
            Route::get('/all','PengajuanSuratKegiatanMahasiswaController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_kegiatan_mahasiswa}','SuratKegiatanMahasiswaController@tolakPengajuan');
            Route::get('/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@show');
            Route::get('/{pengajuan_kegiatan_mahasiswa}/progress','SuratKegiatanMahasiswaController@progress');
            Route::get('/{pengajuan_kegiatan_mahasiswa}/edit','PengajuanSuratKegiatanMahasiswaController@edit');
            Route::patch('/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@update');
            Route::post('/','PengajuanSuratKegiatanMahasiswaController@storePengajuan');
            Route::delete('/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@destroy');
        });
        // Surat Keterangan Lulus
        Route::get('surat-keterangan-lulus','SuratKeteranganLulusController@indexOperator');
        Route::get('surat-keterangan-lulus/all','SuratKeteranganLulusController@getAllSurat');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}','SuratKeteranganLulusController@show');
        Route::get('surat-keterangan-lulus/create/{pengajuan_surat_lulus}','SuratKeteranganLulusController@createSurat');
        Route::post('surat-keterangan-lulus','SuratKeteranganLulusController@storeSurat');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}/cetak','SuratKeteranganLulusController@cetak');
        Route::group(['prefix'=>'surat-keterangan-lulus/pengajuan'],function(){
            Route::get('/create','PengajuanSuratKeteranganLulusController@createPengajuan');
            Route::get('/all','PengajuanSuratKeteranganLulusController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_surat_lulus}','SuratKeteranganLulusController@tolakPengajuan');
            Route::get('/{pengajuan_surat_lulus}/edit','PengajuanSuratKeteranganLulusController@edit');
            Route::get('/{pengajuan_surat_lulus}/progress','SuratKeteranganLulusController@progress');
            Route::get('/{pengajuan_surat_lulus}','PengajuanSuratKeteranganLulusController@show');
            Route::patch('/{pengajuan_surat_lulus}','PengajuanSuratKeteranganLulusController@update');
            Route::post('/','PengajuanSuratKeteranganLulusController@storePengajuan');
            Route::delete('/{pengajuan_surat_lulus}','PengajuanSuratKeteranganLulusController@destroy');
        });
        // Surat Permohonan Pengambilan Material
        Route::get('surat-permohonan-pengambilan-material','SuratPermohonanPengambilanMaterialController@indexOperator');
        Route::get('surat-permohonan-pengambilan-material/all','SuratPermohonanPengambilanMaterialController@getAllSurat');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@show');
        Route::get('surat-permohonan-pengambilan-material/create/{pengajuan_surat_material}','SuratPermohonanPengambilanMaterialController@createSurat');
        Route::post('surat-permohonan-pengambilan-material','SuratPermohonanPengambilanMaterialController@storeSurat');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}/cetak','SuratPermohonanPengambilanMaterialController@cetak');
        Route::group(['prefix'=>'surat-permohonan-pengambilan-material/pengajuan'],function(){
            Route::get('/create','PengajuanSuratPermohonanPengambilanMaterialController@createPengajuan');
            Route::get('/all','PengajuanSuratPermohonanPengambilanMaterialController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_surat_material}','SuratPermohonanPengambilanMaterialController@tolakPengajuan');
            Route::get('/{pengajuan_surat_material}/edit','PengajuanSuratPermohonanPengambilanMaterialController@edit');
            Route::get('/{pengajuan_surat_material}/progress','SuratPermohonanPengambilanMaterialController@progress');
            Route::get('/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@show');
            Route::patch('/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@update');
            Route::post('/','PengajuanSuratPermohonanPengambilanMaterialController@storePengajuan');
            Route::delete('/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@destroy');
        });
        // Surat Permohonan Survei
        Route::get('surat-permohonan-survei','SuratPermohonanSurveiController@indexOperator');
        Route::get('surat-permohonan-survei/all','SuratPermohonanSurveiController@getAllSurat');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}','SuratPermohonanSurveiController@show');
        Route::get('surat-permohonan-survei/create/{pengajuan_surat_survei}','SuratPermohonanSurveiController@createSurat');
        Route::post('surat-permohonan-survei','SuratPermohonanSurveiController@storeSurat');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}/cetak','SuratPermohonanSurveiController@cetak');
        Route::group(['prefix'=>'surat-permohonan-survei/pengajuan'],function(){
            Route::get('/create','PengajuanSuratPermohonanSurveiController@createPengajuan');
            Route::get('/all','PengajuanSuratPermohonanSurveiController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_surat_survei}','SuratPermohonanSurveiController@tolakPengajuan');
            Route::get('/{pengajuan_surat_survei}/edit','PengajuanSuratPermohonanSurveiController@edit');
            Route::get('/{pengajuan_surat_survei}/progress','SuratPermohonanSurveiController@progress');
            Route::get('/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@show');
            Route::patch('/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@update');
            Route::post('/','PengajuanSuratPermohonanSurveiController@storePengajuan');
            Route::delete('/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@destroy');
        });
        // Surat Rekomendasi Penelitian
        Route::get('surat-rekomendasi-penelitian','SuratRekomendasiPenelitianController@indexOperator');
        Route::get('surat-rekomendasi-penelitian/all','SuratRekomendasiPenelitianController@getAllSurat');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}','SuratRekomendasiPenelitianController@show');
        Route::get('surat-rekomendasi-penelitian/create/{pengajuan_surat_penelitian}','SuratRekomendasiPenelitianController@createSurat');
        Route::post('surat-rekomendasi-penelitian','SuratRekomendasiPenelitianController@storeSurat');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}/cetak','SuratRekomendasiPenelitianController@cetak');
        Route::group(['prefix'=>'surat-rekomendasi-penelitian/pengajuan'],function(){
            Route::get('/create','PengajuanSuratRekomendasiPenelitianController@createPengajuan');
            Route::get('/all','PengajuanSuratRekomendasiPenelitianController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_surat_penelitian}','SuratRekomendasiPenelitianController@tolakPengajuan');
            Route::get('/{pengajuan_surat_penelitian}/edit','PengajuanSuratRekomendasiPenelitianController@edit');
            Route::get('/{pengajuan_surat_penelitian}/progress','SuratRekomendasiPenelitianController@progress');
            Route::get('/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@show');
            Route::patch('/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@update');
            Route::post('/','PengajuanSuratRekomendasiPenelitianController@storePengajuan');
            Route::delete('/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@destroy');
        });
        // Surat Permohonan Pengambilan Data Awal
        Route::get('surat-permohonan-pengambilan-data-awal','SuratPermohonanPengambilanDataAwalController@indexOperator');
        Route::get('surat-permohonan-pengambilan-data-awal/all','SuratPermohonanPengambilanDataAwalController@getAllSurat');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@show');
        Route::get('surat-permohonan-pengambilan-data-awal/create/{pengajuan_surat_data_awal}','SuratPermohonanPengambilanDataAwalController@createSurat');
        Route::post('surat-permohonan-pengambilan-data-awal','SuratPermohonanPengambilanDataAwalController@storeSurat');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}/cetak','SuratPermohonanPengambilanDataAwalController@cetak');
        Route::group(['prefix'=>'surat-permohonan-pengambilan-data-awal/pengajuan'],function(){
            Route::get('/create','PengajuanSuratPermohonanPengambilanDataAwalController@createPengajuan');
            Route::get('/all','PengajuanSuratPermohonanPengambilanDataAwalController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_surat_data_awal}','SuratPermohonanPengambilanDataAwalController@tolakPengajuan');
            Route::get('/{pengajuan_surat_data_awal}/edit','PengajuanSuratPermohonanPengambilanDataAwalController@edit');
            Route::get('/{pengajuan_surat_data_awal}/progress','SuratPermohonanPengambilanDataAwalController@progress');
            Route::get('/{pengajuan_surat_data_awal}','PengajuanSuratPermohonanPengambilanDataAwalController@show');
            Route::patch('/{pengajuan_surat_data_awal}','PengajuanSuratPermohonanPengambilanDataAwalController@update');
            Route::post('/','PengajuanSuratPermohonanPengambilanDataAwalController@storePengajuan');
            Route::delete('/{pengajuan_surat_data_awal}','PengajuanSuratPermohonanPengambilanDataAwalController@destroy');
        });
        // Surat Keterangan Bebas Perlengkapan
        Route::get('surat-keterangan-bebas-perlengkapan','SuratKeteranganBebasPerlengkapanController@indexOperator');
        Route::get('surat-keterangan-bebas-perlengkapan/all','SuratKeteranganBebasPerlengkapanController@getAllSurat');
        Route::get('surat-keterangan-bebas-perlengkapan/{surat_perlengkapan}','SuratKeteranganBebasPerlengkapanController@show');
        Route::get('surat-keterangan-bebas-perlengkapan/create/{pengajuan_surat_perlengkapan}','SuratKeteranganBebasPerlengkapanController@createSurat');
        Route::post('surat-keterangan-bebas-perlengkapan','SuratKeteranganBebasPerlengkapanController@storeSurat');
        Route::get('surat-keterangan-bebas-perlengkapan/{surat_perlengkapan}/cetak','SuratKeteranganBebasPerlengkapanController@cetak');
        Route::group(['prefix'=>'surat-keterangan-bebas-perlengkapan/pengajuan'],function(){
            Route::get('/create','PengajuanSuratKeteranganBebasPerlengkapanController@createPengajuan');
            Route::get('/all','PengajuanSuratKeteranganBebasPerlengkapanController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_surat_perlengkapan}','SuratKeteranganBebasPerlengkapanController@tolakPengajuan');
            Route::get('/{pengajuan_surat_perlengkapan}/edit','PengajuanSuratKeteranganBebasPerlengkapanController@edit');
            Route::get('/{pengajuan_surat_perlengkapan}/progress','SuratKeteranganBebasPerlengkapanController@progress');
            Route::get('/{pengajuan_surat_perlengkapan}','PengajuanSuratKeteranganBebasPerlengkapanController@show');
            Route::patch('/{pengajuan_surat_perlengkapan}','PengajuanSuratKeteranganBebasPerlengkapanController@update');
            Route::post('/','PengajuanSuratKeteranganBebasPerlengkapanController@storePengajuan');
            Route::delete('/{pengajuan_surat_perlengkapan}','PengajuanSuratKeteranganBebasPerlengkapanController@destroy');
        });
        // Surat Keterangan Bebas Perpustakaan
        Route::get('surat-keterangan-bebas-perpustakaan','SuratKeteranganBebasPerpustakaanController@indexOperator');
        Route::get('surat-keterangan-bebas-perpustakaan/all','SuratKeteranganBebasPerpustakaanController@getAllSurat');
        Route::get('surat-keterangan-bebas-perpustakaan/{surat_perpustakaan}','SuratKeteranganBebasPerpustakaanController@show');
        Route::get('surat-keterangan-bebas-perpustakaan/create/{pengajuan_surat_perpustakaan}','SuratKeteranganBebasPerpustakaanController@createSurat');
        Route::post('surat-keterangan-bebas-perpustakaan','SuratKeteranganBebasPerpustakaanController@storeSurat');
        Route::get('surat-keterangan-bebas-perpustakaan/{surat_perpustakaan}/cetak','SuratKeteranganBebasPerpustakaanController@cetak');
        Route::group(['prefix'=>'surat-keterangan-bebas-perpustakaan/pengajuan'],function(){
            Route::get('/create','PengajuanSuratKeteranganBebasPerpustakaanController@createPengajuan');
            Route::get('/all','PengajuanSuratKeteranganBebasPerpustakaanController@getAllPengajuan');
            Route::patch('/tolak-pengajuan/{pengajuan_surat_perpustakaan}','SuratKeteranganBebasPerpustakaanController@tolakPengajuan');
            Route::get('/{pengajuan_surat_perpustakaan}/edit','PengajuanSuratKeteranganBebasPerpustakaanController@edit');
            Route::get('/{pengajuan_surat_perpustakaan}/progress','SuratKeteranganBebasPerpustakaanController@progress');
            Route::get('/{pengajuan_surat_perpustakaan}','PengajuanSuratKeteranganBebasPerpustakaanController@show');
            Route::patch('/{pengajuan_surat_perpustakaan}','PengajuanSuratKeteranganBebasPerpustakaanController@update');
            Route::post('/','PengajuanSuratKeteranganBebasPerpustakaanController@storePengajuan');
            Route::delete('/{pengajuan_surat_perpustakaan}','PengajuanSuratKeteranganBebasPerpustakaanController@destroy');
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
        // Tanda Tangan
        Route::get('tanda-tangan','UserController@indexTandaTangan');
        Route::post('tanda-tangan','UserController@updateTandaTangan');
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
        Route::get('surat-kegiatan-mahasiswa/pengajuan/disposisi/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@showDisposisi');
        Route::patch('surat-kegiatan-mahasiswa/verifikasi','PengajuanSuratKegiatanMahasiswaController@verification');
        Route::get('surat-kegiatan-mahasiswa/verifikasi/all','PengajuanSuratKegiatanMahasiswaController@getAllPengajuan');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}','SuratKegiatanMahasiswaController@show');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}/cetak','SuratKegiatanMahasiswaController@cetak');
        // Surat Keterangan Lulus
        Route::get('surat-keterangan-lulus','SuratKeteranganLulusController@index');
        Route::get('surat-keterangan-lulus/all','SuratKeteranganLulusController@getAllSurat');
        Route::patch('surat-keterangan-lulus/verifikasi','PengajuanSuratKeteranganLulusController@verification');
        Route::get('surat-keterangan-lulus/verifikasi/all','PengajuanSuratKeteranganLulusController@getAllPengajuan');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}','SuratKeteranganLulusController@show');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}/cetak','SuratKeteranganLulusController@cetak');
        // Surat Permohonan Pengambilan Material
        Route::get('surat-permohonan-pengambilan-material','SuratPermohonanPengambilanMaterialController@index');
        Route::get('surat-permohonan-pengambilan-material/all','SuratPermohonanPengambilanMaterialController@getAllSurat');
        Route::patch('surat-permohonan-pengambilan-material/verifikasi','PengajuanSuratPermohonanPengambilanMaterialController@verification');
        Route::get('surat-permohonan-pengambilan-material/verifikasi/all','PengajuanSuratPermohonanPengambilanMaterialController@getAllPengajuan');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@show');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}/cetak','SuratPermohonanPengambilanMaterialController@cetak');
        // Surat Permohonan Survei
        Route::get('surat-permohonan-survei','SuratPermohonanSurveiController@index');
        Route::get('surat-permohonan-survei/all','SuratPermohonanSurveiController@getAllSurat');
        Route::patch('surat-permohonan-survei/verifikasi','PengajuanSuratPermohonanSurveiController@verification');
        Route::get('surat-permohonan-survei/verifikasi/all','PengajuanSuratPermohonanSurveiController@getAllPengajuan');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}','SuratPermohonanSurveiController@show');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}/cetak','SuratPermohonanSurveiController@cetak');
        // Surat Rekomendasi Penelitian
        Route::get('surat-rekomendasi-penelitian','SuratRekomendasiPenelitianController@index');
        Route::get('surat-rekomendasi-penelitian/all','SuratRekomendasiPenelitianController@getAllSurat');
        Route::patch('surat-rekomendasi-penelitian/verifikasi','PengajuanSuratRekomendasiPenelitianController@verification');
        Route::get('surat-rekomendasi-penelitian/verifikasi/all','PengajuanSuratRekomendasiPenelitianController@getAllPengajuan');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}','SuratRekomendasiPenelitianController@show');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}/cetak','SuratRekomendasiPenelitianController@cetak');
        // Surat Permohonan Pengambilan Data Awal
        Route::get('surat-permohonan-pengambilan-data-awal','SuratPermohonanPengambilanDataAwalController@index');
        Route::get('surat-permohonan-pengambilan-data-awal/all','SuratPermohonanPengambilanDataAwalController@getAllSurat');
        Route::patch('surat-permohonan-pengambilan-data-awal/verifikasi','PengajuanSuratPermohonanPengambilanDataAwalController@verification');
        Route::get('surat-permohonan-pengambilan-data-awal/verifikasi/all','PengajuanSuratPermohonanPengambilanDataAwalController@getAllPengajuan');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@show');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}/cetak','SuratPermohonanPengambilanDataAwalController@cetak');
        // Surat Keterangan Bebas Perlengkapan
        Route::get('surat-keterangan-bebas-perlengkapan','SuratKeteranganBebasPerlengkapanController@index');
        Route::get('surat-keterangan-bebas-perlengkapan/all','SuratKeteranganBebasPerlengkapanController@getAllSurat');
        Route::patch('surat-keterangan-bebas-perlengkapan/verifikasi','PengajuanSuratKeteranganBebasPerlengkapanController@verification');
        Route::get('surat-keterangan-bebas-perlengkapan/tanda-tangan/all','SuratKeteranganBebasPerlengkapanController@getAllTandaTangan');
        Route::get('surat-keterangan-bebas-perlengkapan/verifikasi/all','PengajuanSuratKeteranganBebasPerlengkapanController@getAllPengajuan');
        Route::get('surat-keterangan-bebas-perlengkapan/{surat_perlengkapan}','SuratKeteranganBebasPerlengkapanController@show');
        Route::get('surat-keterangan-bebas-perlengkapan/{surat_perlengkapan}/cetak','SuratKeteranganBebasPerlengkapanController@cetak');
        Route::post('surat-keterangan-bebas-perlengkapan/tanda-tangan','SuratKeteranganBebasPerlengkapanController@tandaTangan');
        // Detail Mahasiswa
        Route::get('detail/mahasiswa/{mahasiswa}','MahasiswaController@show');
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
        Route::get('/','UserController@indexPimpinan');
        Route::get('search/kemahasiswaan','UserController@searchChartKemahasiswaan');
        Route::get('search/pendidikan-pengajaran','UserController@searchChartPendidikanPengajaran');
        Route::get('search/umum-bmn','UserController@searchChartUmumBmn');
        Route::get('search/perpustakaan','UserController@searchChartUmumBmn');
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
        Route::get('surat-keterangan-aktif-kuliah/pengajuan/mahasiswa/{nim}','PengajuanSuratKeteranganController@getAllPengajuanAktifByNim');
        Route::get('surat-keterangan-aktif-kuliah/pengajuan/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@show');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganAktifKuliahController@cetak');
        Route::get('surat-keterangan-aktif-kuliah/verifikasi/all','PengajuanSuratKeteranganController@getAllPengajuanAktif');
        Route::get('surat-keterangan-aktif-kuliah/tanda-tangan/all','SuratKeteranganController@getAllTandaTanganKeteranganAktif');
        Route::post('surat-keterangan-aktif-kuliah/tanda-tangan','SuratKeteranganAktifKuliahController@tandaTangan');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@show');
        Route::patch('surat-keterangan-aktif-kuliah/verifikasi','PengajuanSuratKeteranganController@verification'); 
        // Surat Keterangan Kelakuan Baik
        Route::get('surat-keterangan-kelakuan-baik','SuratKeteranganKelakuanBaikController@indexPimpinan');
        Route::get('surat-keterangan-kelakuan-baik/all','SuratKeteranganController@getAllSuratKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/pengajuan/mahasiswa/{nim}','PengajuanSuratKeteranganController@getAllPengajuanKelakuanBaikByNim');
        Route::get('surat-keterangan-kelakuan-baik/pengajuan/{pengajuan_surat_keterangan}','PengajuanSuratKeteranganController@show');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganKelakuanBaikController@cetak');
        Route::get('surat-keterangan-kelakuan-baik/verifikasi/all','PengajuanSuratKeteranganController@getAllPengajuanKelakuanBaik');
        Route::get('surat-keterangan-kelakuan-baik/tanda-tangan/all','SuratKeteranganController@getAllTandaTanganKelakuanBaik');
        Route::post('surat-keterangan-kelakuan-baik/tanda-tangan','SuratKeteranganKelakuanBaikController@tandaTangan');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@show');
        Route::patch('surat-keterangan-kelakuan-baik/verifikasi','PengajuanSuratKeteranganController@verification'); 
        // Surat Dispensasi
        Route::get('surat-dispensasi', 'SuratDispensasiController@indexPimpinan');
        Route::get('surat-dispensasi/all','SuratDispensasiController@getAllSuratDispensasi');
        Route::get('surat-dispensasi/pengajuan/mahasiswa/{nim}','PengajuanSuratDispensasiController@getAllPengajuanByNim');
        Route::get('surat-dispensasi/pengajuan/{pengajuan_surat_dispensasi}','PengajuanSuratDispensasiController@show');
        Route::get('surat-dispensasi/{surat_dispensasi}/cetak','SuratDispensasiController@cetak');
        Route::get('surat-dispensasi/verifikasi/all','PengajuanSuratDispensasiController@getAllPengajuan');
        Route::get('surat-dispensasi/tanda-tangan/all','SuratDispensasiController@getAllTandaTangan');
        Route::post('surat-dispensasi/tanda-tangan','SuratDispensasiController@tandaTangan');
        Route::get('surat-dispensasi/{surat_dispensasi}','SuratDispensasiController@show');
        Route::patch('surat-dispensasi/verifikasi','PengajuanSuratDispensasiController@verification'); 
        // Surat Rekomendasi
        Route::get('surat-rekomendasi', 'SuratRekomendasiController@indexPimpinan');
        Route::get('surat-rekomendasi/all','SuratRekomendasiController@getAllSuratRekomendasi');
        Route::get('surat-rekomendasi/pengajuan/mahasiswa/{nim}','PengajuanSuratRekomendasiController@getAllPengajuanByNim');
        Route::get('surat-rekomendasi/pengajuan/{pengajuan_surat_rekomendasi}','PengajuanSuratRekomendasiController@show');
        Route::get('surat-rekomendasi/{surat_rekomendasi}/cetak','SuratRekomendasiController@cetak');
        Route::get('surat-rekomendasi/verifikasi/all','PengajuanSuratRekomendasiController@getAllPengajuan');
        Route::get('surat-rekomendasi/tanda-tangan/all','SuratRekomendasiController@getAllTandaTangan');
        Route::patch('surat-rekomendasi/verifikasi','PengajuanSuratRekomendasiController@verification'); 
        Route::get('surat-rekomendasi/{surat_rekomendasi}','SuratRekomendasiController@show');
        Route::post('surat-rekomendasi/tanda-tangan','SuratRekomendasiController@tandaTangan');
        // Surat Tugas
        Route::get('surat-tugas', 'SuratTugasController@indexPimpinan');
        Route::get('surat-tugas/all','SuratTugasController@getAllSuratTugas');
        Route::get('surat-tugas/pengajuan/mahasiswa/{nim}','PengajuanSuratTugasController@getAllPengajuanByNim');
        Route::get('surat-tugas/pengajuan/{pengajuan_surat_tugas}','PengajuanSuratTugasController@show');
        Route::get('surat-tugas/{surat_tugas}/cetak','SuratTugasController@cetak');
        Route::get('surat-tugas/verifikasi/all','PengajuanSuratTugasController@getAllPengajuan');
        Route::get('surat-tugas/tanda-tangan/all','SuratTugasController@getAllTandaTangan');
        Route::patch('surat-tugas/verifikasi','PengajuanSuratTugasController@verification'); 
        Route::get('surat-tugas/{surat_tugas}','SuratTugasController@show');
        Route::post('surat-tugas/tanda-tangan','SuratTugasController@tandaTangan');
        // Surat Persetujuan Pindah
        Route::get('surat-persetujuan-pindah', 'SuratPersetujuanPindahController@indexPimpinan');
        Route::get('surat-persetujuan-pindah/all','SuratPersetujuanPindahController@getAllSurat');
        Route::get('surat-persetujuan-pindah/pengajuan/mahasiswa/{nim}','PengajuanSuratPersetujuanPindahController@getAllPengajuanByNim');
        Route::get('surat-persetujuan-pindah/pengajuan/{pengajuan_surat_pindah}','PengajuanSuratPersetujuanPindahController@show');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}/cetak','SuratPersetujuanPindahController@cetak');
        Route::get('surat-persetujuan-pindah/verifikasi/all','PengajuanSuratPersetujuanPindahController@getAllPengajuan');
        Route::get('surat-persetujuan-pindah/tanda-tangan/all','SuratPersetujuanPindahController@getAllTandaTangan');
        Route::post('surat-persetujuan-pindah/tanda-tangan','SuratPersetujuanPindahController@tandaTangan');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}','SuratPersetujuanPindahController@show');
        Route::patch('surat-persetujuan-pindah/verifikasi','PengajuanSuratPersetujuanPindahController@verification');
        // Surat Pengantar Cuti
        Route::get('surat-pengantar-cuti', 'SuratPengantarCutiController@indexPimpinan');
        Route::get('surat-pengantar-cuti/all','SuratPengantarCutiController@getAllSurat');
        Route::get('surat-pengantar-cuti/pengajuan/mahasiswa/{nim}','SuratPengantarCutiController@getAllPengajuanByNim');
        Route::get('surat-pengantar-cuti/pengajuan/{surat_pengantar_cuti}','SuratPengantarCutiController@show');
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
        Route::get('surat-pengantar-beasiswa/pengajuan/mahasiswa/{nim}','SuratPengantarBeasiswaController@getAllPengajuanByNim');
        Route::get('surat-pengantar-beasiswa/pengajuan/{surat_pengantar_cuti}','SuratPengantarBeasiswaController@show');
        Route::get('surat-pengantar-beasiswa/{surat_pengantar_beasiswa}/cetak','SuratPengantarBeasiswaController@cetak');
        Route::get('surat-pengantar-beasiswa/verifikasi/all','SuratPengantarBeasiswaController@getAllPengajuan');
        Route::get('surat-pengantar-beasiswa/tanda-tangan/all','SuratPengantarBeasiswaController@getAllTandaTangan');
        Route::post('surat-pengantar-beasiswa/tanda-tangan','SuratPengantarBeasiswaController@tandaTangan');
        Route::patch('surat-pengantar-beasiswa/verifikasi','SuratPengantarBeasiswaController@verification');
        Route::resource('surat-pengantar-beasiswa','SuratPengantarBeasiswaController')->except('index');
        //  Surat Kegiatan Mahasiswa
        Route::get('surat-kegiatan-mahasiswa','SuratKegiatanMahasiswaController@indexPimpinan');
        Route::get('surat-kegiatan-mahasiswa/all','SuratKegiatanMahasiswaController@getAllSurat');
        Route::get('surat-kegiatan-mahasiswa/pengajuan/mahasiswa/{nim}','PengajuanSuratKegiatanMahasiswaController@getAllPengajuanByNim');
        Route::get('surat-kegiatan-mahasiswa/pengajuan/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@show');
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
        // Surat Keterangan Lulus
        Route::get('surat-keterangan-lulus','SuratKeteranganLulusController@indexPimpinan');
        Route::get('surat-keterangan-lulus/all','SuratKeteranganLulusController@getAllSurat');
        Route::get('surat-keterangan-lulus/pengajuan/mahasiswa/{nim}','PengajuanSuratKeteranganLulusController@getAllPengajuanByNim');
        Route::get('surat-keterangan-lulus/pengajuan/{pengajuan_surat_lulus}','PengajuanSuratKeteranganLulusController@show');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}/cetak','SuratKeteranganLulusController@cetak');
        Route::get('surat-keterangan-lulus/verifikasi/all','PengajuanSuratKeteranganLulusController@getAllPengajuan');
        Route::get('surat-keterangan-lulus/tanda-tangan/all','SuratKeteranganLulusController@getAllTandaTangan');
        Route::post('surat-keterangan-lulus/tanda-tangan','SuratKeteranganLulusController@tandaTangan');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}','SuratKeteranganLulusController@show');
        Route::patch('surat-keterangan-lulus/verifikasi','PengajuanSuratKeteranganLulusController@verification'); 
        // Surat Permohonan Pengambilan Material
        Route::get('surat-permohonan-pengambilan-material','SuratPermohonanPengambilanMaterialController@indexPimpinan');
        Route::get('surat-permohonan-pengambilan-material/all','SuratPermohonanPengambilanMaterialController@getAllSurat');
        Route::get('surat-permohonan-pengambilan-material/pengajuan/mahasiswa/{nim}','PengajuanSuratPermohonanPengambilanMaterialController@getAllPengajuanByNim');
        Route::get('surat-permohonan-pengambilan-material/pengajuan/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@show');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}/cetak','SuratPermohonanPengambilanMaterialController@cetak');
        Route::get('surat-permohonan-pengambilan-material/verifikasi/all','PengajuanSuratPermohonanPengambilanMaterialController@getAllPengajuan');
        Route::get('surat-permohonan-pengambilan-material/tanda-tangan/all','SuratPermohonanPengambilanMaterialController@getAllTandaTangan');
        Route::post('surat-permohonan-pengambilan-material/tanda-tangan','SuratPermohonanPengambilanMaterialController@tandaTangan');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@show');
        Route::patch('surat-permohonan-pengambilan-material/verifikasi','PengajuanSuratPermohonanPengambilanMaterialController@verification'); 
        // Surat Permohonan Survei
        Route::get('surat-permohonan-survei','SuratPermohonanSurveiController@indexPimpinan');
        Route::get('surat-permohonan-survei/all','SuratPermohonanSurveiController@getAllSurat');
        Route::get('surat-permohonan-survei/pengajuan/mahasiswa/{nim}','PengajuanSuratPermohonanSurveiController@getAllPengajuanByNim');
        Route::get('surat-permohonan-survei/pengajuan/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@show');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}/cetak','SuratPermohonanSurveiController@cetak');
        Route::get('surat-permohonan-survei/verifikasi/all','PengajuanSuratPermohonanSurveiController@getAllPengajuan');
        Route::get('surat-permohonan-survei/tanda-tangan/all','SuratPermohonanSurveiController@getAllTandaTangan');
        Route::post('surat-permohonan-survei/tanda-tangan','SuratPermohonanSurveiController@tandaTangan');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}','SuratPermohonanSurveiController@show');
        Route::patch('surat-permohonan-survei/verifikasi','PengajuanSuratPermohonanSurveiController@verification'); 
        // Surat Rekomendasi Penelitian
        Route::get('surat-rekomendasi-penelitian','SuratRekomendasiPenelitianController@indexPimpinan');
        Route::get('surat-rekomendasi-penelitian/all','SuratRekomendasiPenelitianController@getAllSurat');
        Route::get('surat-rekomendasi-penelitian/pengajuan/mahasiswa/{nim}','PengajuanSuratRekomendasiPenelitianController@getAllPengajuanByNim');
        Route::get('surat-rekomendasi-penelitian/pengajuan/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@show');
        Route::get('surat-rekomendasi-penelitian/pengajuan/mahasiswa/{nim}','PengajuanSuratRekomendasiPenelitianController@getAllPengajuanByNim');
        Route::get('surat-rekomendasi-penelitian/pengajuan/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@show');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}/cetak','SuratRekomendasiPenelitianController@cetak');
        Route::get('surat-rekomendasi-penelitian/verifikasi/all','PengajuanSuratRekomendasiPenelitianController@getAllPengajuan');
        Route::get('surat-rekomendasi-penelitian/tanda-tangan/all','SuratRekomendasiPenelitianController@getAllTandaTangan');
        Route::post('surat-rekomendasi-penelitian/tanda-tangan','SuratRekomendasiPenelitianController@tandaTangan');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}','SuratRekomendasiPenelitianController@show');
        Route::patch('surat-rekomendasi-penelitian/verifikasi','PengajuanSuratRekomendasiPenelitianController@verification'); 
        // Surat Permohonan Pengambilan Data Awal
        Route::get('surat-permohonan-pengambilan-data-awal','SuratPermohonanPengambilanDataAwalController@indexPimpinan');
        Route::get('surat-permohonan-pengambilan-data-awal/all','SuratPermohonanPengambilanDataAwalController@getAllSurat');
        Route::get('surat-permohonan-pengambilan-data-awal/pengajuan/mahasiswa/{nim}','PengajuanSuratPermohonanPengambilanDataAwalController@getAllPengajuanByNim');
        Route::get('surat-permohonan-pengambilan-data-awal/pengajuan/{pengajuan_surat_data_awal}','PengajuanSuratPermohonanPengambilanDataAwalController@show');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}/cetak','SuratPermohonanPengambilanDataAwalController@cetak');
        Route::get('surat-permohonan-pengambilan-data-awal/verifikasi/all','PengajuanSuratPermohonanPengambilanDataAwalController@getAllPengajuan');
        Route::get('surat-permohonan-pengambilan-data-awal/tanda-tangan/all','SuratPermohonanPengambilanDataAwalController@getAllTandaTangan');
        Route::post('surat-permohonan-pengambilan-data-awal/tanda-tangan','SuratPermohonanPengambilanDataAwalController@tandaTangan');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@show');
        Route::patch('surat-permohonan-pengambilan-data-awal/verifikasi','PengajuanSuratPermohonanPengambilanDataAwalController@verification'); 
        // Surat Keterangan Bebas Perlengkapan
        Route::get('surat-keterangan-bebas-perlengkapan','SuratKeteranganBebasPerlengkapanController@indexPimpinan');
        Route::get('surat-keterangan-bebas-perlengkapan/all','SuratKeteranganBebasPerlengkapanController@getAllSurat');
        Route::get('surat-keterangan-bebas-perlengkapan/pengajuan/mahasiswa/{nim}','PengajuanSuratKeteranganBebasPerlengkapanController@getAllPengajuanByNim');
        Route::get('surat-keterangan-bebas-perlengkapan/pengajuan/{pengajuan_surat_perlengkapan}','PengajuanSuratKeteranganBebasPerlengkapanController@show');
        Route::get('surat-keterangan-bebas-perlengkapan/{surat_perlengkapan}/cetak','SuratKeteranganBebasPerlengkapanController@cetak');
        Route::get('surat-keterangan-bebas-perlengkapan/verifikasi/all','PengajuanSuratKeteranganBebasPerlengkapanController@getAllPengajuan');
        Route::get('surat-keterangan-bebas-perlengkapan/tanda-tangan/all','SuratKeteranganBebasPerlengkapanController@getAllTandaTangan');
        Route::post('surat-keterangan-bebas-perlengkapan/tanda-tangan','SuratKeteranganBebasPerlengkapanController@tandaTangan');
        Route::get('surat-keterangan-bebas-perlengkapan/{surat_perlengkapan}','SuratKeteranganBebasPerlengkapanController@show');
        Route::patch('surat-keterangan-bebas-perlengkapan/verifikasi','PengajuanSuratKeteranganBebasPerlengkapanController@verification'); 
        // Surat Keterangan Bebas Perpustakaan
        Route::get('surat-keterangan-bebas-perpustakaan','SuratKeteranganBebasPerpustakaanController@indexPimpinan');
        Route::get('surat-keterangan-bebas-perpustakaan/all','SuratKeteranganBebasPerpustakaanController@getAllSurat');
        Route::get('surat-keterangan-bebas-perpustakaan/pengajuan/mahasiswa/{nim}','PengajuanSuratKeteranganBebasPerpustakaanController@getAllPengajuanByNim');
        Route::get('surat-keterangan-bebas-perpustakaan/pengajuan/{pengajuan_surat_perpustakaan}','PengajuanSuratKeteranganBebasPerpustakaanController@show');
        Route::get('surat-keterangan-bebas-perpustakaan/{surat_perpustakaan}/cetak','SuratKeteranganBebasPerpustakaanController@cetak');
        Route::get('surat-keterangan-bebas-perpustakaan/tanda-tangan/all','SuratKeteranganBebasPerpustakaanController@getAllTandaTangan');
        Route::post('surat-keterangan-bebas-perpustakaan/tanda-tangan','SuratKeteranganBebasPerpustakaanController@tandaTangan');
        Route::get('surat-keterangan-bebas-perpustakaan/{surat_perpustakaan}','SuratKeteranganBebasPerpustakaanController@show');
        // Mahasiswa
        Route::get('mahasiswa', 'MahasiswaController@indexPimpinan');
        Route::get('mahasiswa/all','MahasiswaController@getAllMahasiswa');
        Route::get('detail/mahasiswa/{mahasiswa}','MahasiswaController@show');
        Route::get('mahasiswa/{mahasiswa}','MahasiswaController@showPimpinan');
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
