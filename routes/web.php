<?php

use App\User;
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

Route::get('test','UserController@test');

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
        Route::get('/','MahasiswaController@dashboard');
        // Pengajuan Surat
        Route::group(['prefix' => 'pengajuan'],function(){
            // Surat Keterangan Aktif Kuliah
            Route::get('surat-keterangan-aktif-kuliah','SuratKeteranganAktifKuliahController@indexMahasiswa');
            Route::get('surat-keterangan-aktif-kuliah/{pengajuan_surat_keterangan}/progress','SuratKeteranganController@progress');
            Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganAktifKuliahController@cetak');
            Route::get('surat-keterangan-aktif-kuliah/create','PengajuanSuratKeteranganController@createPengajuanKeteranganAktif');
            Route::post('surat-keterangan-aktif-kuliah','PengajuanSuratKeteranganController@storePengajuanKeteranganAktif');
            // Surat Keterangan Kelakuan Baik
            Route::get('surat-keterangan-kelakuan-baik','SuratKeteranganKelakuanBaikController@indexMahasiswa');
            Route::get('surat-keterangan-kelakuan-baik/{pengajuan_surat_keterangan}/progress','SuratKeteranganController@progress');
            Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganKelakuanBaikController@cetak');
            Route::get('surat-keterangan-kelakuan-baik/create','PengajuanSuratKeteranganController@createPengajuanKelakuanBaik');
            Route::post('surat-keterangan-kelakuan-baik','PengajuanSuratKeteranganController@storePengajuanKelakuanBaik');
            // Surat Persetujuan Pindah
            Route::get('surat-persetujuan-pindah','PengajuanSuratPersetujuanPindahController@persetujuanPindahMahasiswa');
            Route::get('surat-persetujuan-pindah/{pengajuan_persetujuan_pindah}/progress','PengajuanSuratPersetujuanPindahController@progress');
            Route::get('surat-persetujuan-pindah/create','PengajuanSuratPersetujuanPindahController@create');
            Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}/cetak', 'SuratPersetujuanPindahController@cetakSuratPindah');
            Route::patch('surat-persetujuan-pindah/{pengajuan_persetujuan_pindah}','PengajuanSuratPersetujuanPindahController@update');
            Route::get('surat-persetujuan-pindah/{pengajuan_persetujuan_pindah}/edit','PengajuanSuratPersetujuanPindahController@edit');
            Route::get('surat-persetujuan-pindah/{pengajuan_persetujuan_pindah}','PengajuanSuratPersetujuanPindahController@show');
            Route::post('surat-persetujuan-pindah','PengajuanSuratPersetujuanPindahController@store');
            // Surat Kegiatan Mahasiswa
            Route::get('surat-kegiatan-mahasiswa','PengajuanSuratKegiatanMahasiswaController@suratKegiatanMahasiswa');
            Route::get('surat-kegiatan-mahasiswa/create','PengajuanSuratKegiatanMahasiswaController@create');
            Route::get('surat-kegiatan-mahasiswa/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@show');
            Route::get('surat-kegiatan-mahasiswa/{pengajuan_kegiatan_mahasiswa}/progress','PengajuanSuratKegiatanMahasiswaController@progress');
            Route::patch('surat-kegiatan-mahasiswa/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@update');
            Route::get('surat-kegiatan-mahasiswa/{pengajuan_kegiatan_mahasiswa}/edit','PengajuanSuratKegiatanMahasiswaController@edit');
            Route::post('surat-kegiatan-mahasiswa','PengajuanSuratKegiatanMahasiswaController@store');
            // Surat Keterangan Lulus
            Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}/cetak','SuratKeteranganLulusController@cetak');
            Route::get('surat-keterangan-lulus','PengajuanSuratKeteranganLulusController@index');
            Route::get('surat-keterangan-lulus/create','PengajuanSuratKeteranganLulusController@create');
            Route::get('surat-keterangan-lulus/{pengajuan_surat_keterangan_lulus}','PengajuanSuratKeteranganLulusController@show');
            Route::get('surat-keterangan-lulus/{pengajuan_surat_keterangan_lulus}/progress','PengajuanSuratKeteranganLulusController@progress');
            Route::patch('surat-keterangan-lulus/{pengajuan_surat_keterangan_lulus}','PengajuanSuratKeteranganLulusController@update');
            Route::get('surat-keterangan-lulus/{pengajuan_surat_keterangan_lulus}/edit','PengajuanSuratKeteranganLulusController@edit');
            Route::post('surat-keterangan-lulus','PengajuanSuratKeteranganLulusController@store');
            // Surat Permohonan Pengambilan Material
            Route::get('surat-permohonan-pengambilan-material/{surat_material}/cetak', 'SuratPermohonanPengambilanMaterialController@cetak');
            Route::get('surat-permohonan-pengambilan-material/create','PengajuanSuratPermohonanPengambilanMaterialController@create');
            Route::get('surat-permohonan-pengambilan-material/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@show');
            Route::get('surat-permohonan-pengambilan-material/{pengajuan_surat_material}/edit','PengajuanSuratPermohonanPengambilanMaterialController@edit');
            Route::get('surat-permohonan-pengambilan-material','PengajuanSuratPermohonanPengambilanMaterialController@index');
            Route::post('surat-permohonan-pengambilan-material','PengajuanSuratPermohonanPengambilanMaterialController@store');
            Route::patch('surat-permohonan-pengambilan-material/{pengajuan_surat_material}','PengajuanSuratPermohonanPengambilanMaterialController@update');
            Route::get('surat-permohonan-pengambilan-material/{pengajuan_surat_material}/progress','PengajuanSuratPermohonanPengambilanMaterialController@progress');
            // Surat Permohonan Survei
            Route::get('surat-permohonan-survei/{surat_survei}/cetak', 'SuratPermohonanSurveiController@cetak');
            Route::get('surat-permohonan-survei/create','PengajuanSuratPermohonanSurveiController@create');
            Route::get('surat-permohonan-survei/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@show');
            Route::get('surat-permohonan-survei/{pengajuan_surat_survei}/edit','PengajuanSuratPermohonanSurveiController@edit');
            Route::get('surat-permohonan-survei','PengajuanSuratPermohonanSurveiController@index');
            Route::post('surat-permohonan-survei','PengajuanSuratPermohonanSurveiController@store');
            Route::patch('surat-permohonan-survei/{pengajuan_surat_survei}','PengajuanSuratPermohonanSurveiController@update');
            Route::get('surat-permohonan-survei/{pengajuan_surat_survei}/progress','PengajuanSuratPermohonanSurveiController@progress');
            // Surat Rekomendasi Penelitian
            Route::get('surat-rekomendasi-penelitian/{surat_penelitian}/cetak', 'SuratRekomendasiPenelitianController@cetak');
            Route::get('surat-rekomendasi-penelitian/create','PengajuanSuratRekomendasiPenelitianController@create');
            Route::get('surat-rekomendasi-penelitian/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@show');
            Route::get('surat-rekomendasi-penelitian/{pengajuan_surat_penelitian}/edit','PengajuanSuratRekomendasiPenelitianController@edit');
            Route::get('surat-rekomendasi-penelitian','PengajuanSuratRekomendasiPenelitianController@index');
            Route::post('surat-rekomendasi-penelitian','PengajuanSuratRekomendasiPenelitianController@store');
            Route::patch('surat-rekomendasi-penelitian/{pengajuan_surat_penelitian}','PengajuanSuratRekomendasiPenelitianController@update');
            Route::get('surat-rekomendasi-penelitian/{pengajuan_surat_penelitian}/progress','PengajuanSuratRekomendasiPenelitianController@progress');
            // Surat Permohonan Pengambilan Data Awal
            Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}/cetak', 'SuratPermohonanPengambilanDataAwalController@cetak');
            Route::get('surat-permohonan-pengambilan-data-awal/create','PengajuanSuratPermohonanPengambilanDataAwalController@create');
            Route::get('surat-permohonan-pengambilan-data-awal/{pengajuan_surat_data_awal}','PengajuanSuratPermohonanPengambilanDataAwalController@show');
            Route::get('surat-permohonan-pengambilan-data-awal/{pengajuan_surat_data_awal}/edit','PengajuanSuratPermohonanPengambilanDataAwalController@edit');
            Route::get('surat-permohonan-pengambilan-data-awal','PengajuanSuratPermohonanPengambilanDataAwalController@index');
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
        //  Surat Dispensasi
        Route::get('surat-dispensasi/{surat_dispensasi}/cetak', 'SuratDispensasiController@cetakSuratDispensasi');
        Route::get('surat-dispensasi/{surat_dispensasi}/progress','SuratDispensasiController@progressPengajuanSuratDispensasi');
        Route::resource('surat-dispensasi','SuratDispensasiController')->only(['show']);
        Route::get('surat-dispensasi','SuratDispensasiController@suratDispensasiMahasiswa');
        // Surat Rekomendasi
        Route::get('surat-rekomendasi/{surat_rekomendasi}/cetak', 'SuratRekomendasiController@cetakSuratRekomendasi');
        Route::get('surat-rekomendasi/{surat_rekomendasi}/progress','SuratRekomendasiController@progressPengajuanSuratRekomendasi');
        Route::resource('surat-rekomendasi','SuratRekomendasiController')->only(['show']);
        Route::get('surat-rekomendasi','SuratRekomendasiController@suratRekomendasiMahasiswa');
        // Surat Tugas
        Route::get('surat-tugas/{surat_tugas}/cetak', 'SuratTugasController@cetakSuratTugas');
        Route::get('surat-tugas/{surat_tugas}/progress','SuratTugasController@progressPengajuanSuratTugas');
        Route::resource('surat-tugas','SuratTugasController')->only(['show']);
        Route::get('surat-tugas','SuratTugasController@suratTugasMahasiswa');
        // Surat Persetujuan Pindah
        Route::resource('surat-persetujuan-pindah','SuratPersetujuanPindahController')->only('show');
        // Surat Kegiatan Mahasiswa
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}/cetak', 'SuratKegiatanMahasiswaController@cetakSuratKegiatan');
        Route::resource('surat-kegiatan-mahasiswa','SuratKegiatanMahasiswaController')->only('show');
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
        // Pendaftaran Cuti
        Route::get('pendaftaran-cuti','PendaftaranCutiController@pendaftaranCutiMahasiswa');
        Route::resource('pendaftaran-cuti','PendaftaranCutiController')->except('index');
        // Notifikasi
        Route::get('notifikasi/{notifikasi_mahasiswa}','NotifikasiMahasiswaController@show');
        Route::get('notifikasi','NotifikasiMahasiswaController@index');
        Route::post('notifikasi/allread','NotifikasiMahasiswaController@allRead');
        Route::post('notifikasi/alldelete','NotifikasiMahasiswaController@allDelete');
        // Password
        Route::get('profil','MahasiswaController@profil');
        Route::patch('profil/{mahasiswa}','MahasiswaController@updateProfil');
        Route::get('profil/password','MahasiswaController@password');
        Route::post('profil/password','MahasiswaController@updatePassword');
    });
});

// Pegawai
Route::group(['prefix' => 'pegawai'],function(){
    // Logout
    Route::get('logout','Auth\LoginController@postLogout');

    Route::middleware(['auth:user','pegawai'])->group(function(){
        // Dashboard
        Route::get('/','UserController@pegawaiDashboard');
        // Kode Surat
        Route::get('kode-surat/search','KodeSuratController@search');
        Route::resource('kode-surat','KodeSuratController')->except(['show']);
        // Tanda Tangan
        Route::get('tanda-tangan','UserController@indexTandaTangan');
        Route::post('tanda-tangan','UserController@updateTandaTangan');
        // Surat Keterangan Aktif Kuliah
        Route::get('surat-keterangan-aktif-kuliah/search/','SuratKeteranganAktifKuliahController@search');
        Route::resource('surat-keterangan-aktif-kuliah','SuratKeteranganAktifKuliahController')->except('show');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@show');
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}/cetak','SuratKeteranganAktifKuliahController@cetak');
        Route::group(['prefix'=>'surat-keterangan-aktif-kuliah/pengajuan'],function(){
            Route::post('/','SuratKeteranganAktifKuliahController@storeSurat');
            Route::get('create/{pengajuan_surat_keterangan}','SuratKeteranganAktifKuliahController@createSurat');
            Route::patch('tolak-pengajuan/{pengajuan_surat_keterangan}','SuratKeteranganAktifKuliahController@tolakPengajuan');
        });
        // Surat Keterangan Kelakuan Baik
        Route::get('surat-keterangan-kelakuan-baik/search/','SuratKeteranganKelakuanBaikController@search');
        Route::resource('surat-keterangan-kelakuan-baik','SuratKeteranganKelakuanBaikController')->except('show');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@show');
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}/cetak','SuratKeteranganKelakuanBaikController@cetak');
        Route::group(['prefix'=>'surat-keterangan-kelakuan-baik/pengajuan'],function(){
            Route::post('/','SuratKeteranganKelakuanBaikController@storeSurat');
            Route::get('create/{pengajuan_surat_keterangan}','SuratKeteranganKelakuanBaikController@createSurat');
            Route::patch('tolak-pengajuan/{pengajuan_surat_keterangan}','SuratKeteranganKelakuanBaikController@tolakPengajuan');
        });
        // Surat Masuk
        Route::get('surat-masuk/search', 'SuratMasukController@search');
        Route::resource('surat-masuk','SuratMasukController');
        // Surat Dispensasi
        Route::get('surat-dispensasi/search', 'SuratDispensasiController@search');
        Route::get('surat-dispensasi/{surat_dispensasi}/cetak', 'SuratDispensasiController@cetakSuratDispensasi');
        Route::resource('surat-dispensasi','SuratDispensasiController');
        // Surat Rekomendasi
        Route::get('surat-rekomendasi/search', 'SuratRekomendasiController@search');
        Route::get('surat-rekomendasi/{surat_rekomendasi}/cetak', 'SuratRekomendasiController@cetakSuratRekomendasi');
        Route::resource('surat-rekomendasi','SuratRekomendasiController');
        // Surat Tugas
        Route::get('surat-tugas/search', 'SuratTugasController@search');
        Route::get('surat-tugas/{surat_tugas}/cetak', 'SuratTugasController@cetakSuratTugas');
        Route::resource('surat-tugas','SuratTugasController');
        // Surat Persetujuan Pindah
        Route::patch('surat-persetujuan-pindah/pengajuan/tolak-pengajuan/{pengajuan_persetujuan_pindah}','SuratPersetujuanPindahController@tolakPengajuan');
        Route::post('surat-persetujuan-pindah/pengajuan','SuratPersetujuanPindahController@storeSurat');
        Route::get('surat-persetujuan-pindah/pengajuan/{pengajuan_persetujuan_pindah}/create','SuratPersetujuanPindahController@createSurat');
        Route::get('surat-persetujuan-pindah/search', 'SuratPersetujuanPindahController@search');
        Route::get('surat-persetujuan-pindah/{surat_persetujuan_pindah}/cetak', 'SuratPersetujuanPindahController@cetakSuratPindah');
        Route::get('surat-persetujuan-pindah/pengajuan/{pengajuan_persetujuan_pindah}','PengajuanSuratPersetujuanPindahController@show');
        Route::resource('surat-persetujuan-pindah','SuratPersetujuanPindahController');
         // Surat Kegiatan Mahasiswa
        Route::get('surat-kegiatan-mahasiswa/pengajuan/{pengajuan_kegiatan_mahasiswa}/create','PengajuanSuratKegiatanMahasiswaController@createSurat');
        Route::get('surat-kegiatan-mahasiswa/pengajuan/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@show');
        Route::post('surat-kegiatan-mahasiswa/pengajuan','PengajuanSuratKegiatanMahasiswaController@storeSurat');
        Route::patch('surat-kegiatan-mahasiswa/pengajuan/tolak/{pengajuan_kegiatan_mahasiswa}', 'PengajuanSuratKegiatanMahasiswaController@tolak');
        Route::patch('surat-kegiatan-mahasiswa/pengajuan/terima/{pengajuan_kegiatan_mahasiswa}', 'PengajuanSuratKegiatanMahasiswaController@terima');
        Route::get('surat-kegiatan-mahasiswa/{surat_kegiatan_mahasiswa}/cetak', 'SuratKegiatanMahasiswaController@cetakSuratKegiatan');
        Route::get('surat-kegiatan-mahasiswa/search', 'SuratKegiatanMahasiswaController@search');
        Route::resource('surat-kegiatan-mahasiswa','SuratKegiatanMahasiswaController');
        // Surat Tugas
        Route::get('pendaftaran-cuti/search', 'PendaftaranCutiController@search');
        Route::patch('pendaftaran-cuti/terima/{pendaftaran_cuti}', 'PendaftaranCutiController@terima');
        Route::patch('pendaftaran-cuti/tolak/{pendaftaran_cuti}', 'PendaftaranCutiController@tolak');
        Route::resource('pendaftaran-cuti','PendaftaranCutiController');
        // Surat Pengantar Cuti
        Route::get('surat-pengantar-cuti/search', 'SuratPengantarCutiController@search');
        Route::get('surat-pengantar-cuti/{surat_pengantar_cuti}/cetak', 'SuratPengantarCutiController@cetakSuratCuti');
        Route::resource('surat-pengantar-cuti','SuratPengantarCutiController');
        // Surat Pengantar Beasiswa
        Route::get('surat-pengantar-beasiswa/search', 'SuratPengantarBeasiswaController@search');
        Route::get('surat-pengantar-beasiswa/{surat_pengantar_beasiswa}/cetak', 'SuratPengantarBeasiswaController@cetakSuratBeasiswa');
        Route::resource('surat-pengantar-beasiswa','SuratPengantarBeasiswaController');
        // Waktu Cuti
        Route::get('waktu-cuti/search', 'WaktuCutiController@search');
        Route::resource('waktu-cuti','WaktuCutiController')->except('show');
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
        Route::post('laporan','LaporanController@show');
        // Notifikasi
        Route::get('notifikasi/{notifikasi_user}','NotifikasiUserController@show');
        Route::get('notifikasi','NotifikasiUserController@index');
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
    Route::get('login','Auth\LoginController@getLogin');
    Route::post('login','Auth\LoginController@postLogin');
    Route::get('logout','Auth\LoginController@postLogout');

    Route::middleware(['auth:admin'])->group(function(){   
        // Dashbord
        Route::get('/', 'AdminController@index');
        // Jurusan
        Route::get('jurusan/all','JurusanController@getAllJurusan');
        Route::resource('jurusan', 'JurusanController');
        // Program Studi
        Route::get('program-studi/all','ProgramStudiController@getAllProdi');
        Route::resource('program-studi', 'ProgramStudiController');
        // Tahun Akademik
        Route::get('tahun-akademik/all','TahunAkademikController@getAllTahunAkademik');
        Route::resource('tahun-akademik', 'TahunAkademikController');
        // Mahasiswa
        Route::get('mahasiswa/all','MahasiswaController@getAllMahasiswa');
        Route::post('mahasiswa/import-mahasiswa','MahasiswaController@storeImport');
        Route::get('mahasiswa/import-mahasiswa','MahasiswaController@createImport');
        Route::resource('mahasiswa', 'MahasiswaController');
        // User
        Route::get('user/all','UserController@getAllUser');
        Route::resource('user','UserController');
        // Status Mahasiswa
        Route::post('status-mahasiswa/import-status-mahasiswa','StatusMahasiswaController@storeImport');
        Route::get('status-mahasiswa/import-status-mahasiswa','StatusMahasiswaController@createImport');
        Route::get('status-mahasiswa/all','StatusMahasiswaController@getAllStatusMahasiswa');
        Route::get('status-mahasiswa/{id_tahun_akademik}/{nim}/','StatusMahasiswaController@show');
        Route::post('status-mahasiswa','StatusMahasiswaController@store');
        Route::get('status-mahasiswa','StatusMahasiswaController@index');
        Route::get('status-mahasiswa/create','StatusMahasiswaController@create');
        Route::patch('status-mahasiswa','StatusMahasiswaController@update');
        Route::delete('status-mahasiswa','StatusMahasiswaController@destroy');
        Route::get('status-mahasiswa/{id_tahun_akademik}/{nim}/edit','StatusMahasiswaController@edit');
        // Ormawa
        Route::get('ormawa/all','OrmawaController@getAllOrmawa');
        Route::resource('ormawa','OrmawaController');
        // Pimpinan Ormawa
        Route::get('pimpinan-ormawa/all','PimpinanOrmawaController@getAllPimpinanOrmawa');
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
        // Surat Keterangan Aktif Kuliah
        Route::get('surat-keterangan-aktif-kuliah/search/','SuratKeteranganAktifKuliahController@search');
        Route::resource('surat-keterangan-aktif-kuliah','SuratKeteranganAktifKuliahController')->except(['show','destroy']);
        Route::get('surat-keterangan-aktif-kuliah/{surat_keterangan}','SuratKeteranganController@show');
        // Surat Keterangan Kelakuan Baik
        Route::get('surat-keterangan-kelakuan-baik/search/','SuratKeteranganKelakuanBaikController@search');
        Route::resource('surat-keterangan-kelakuan-baik','SuratKeteranganKelakuanBaikController')->except(['show','destroy']);
        Route::get('surat-keterangan-kelakuan-baik/{surat_keterangan}','SuratKeteranganController@show');
        // Surat Dispensasi
        Route::get('surat-dispensasi', 'SuratDispensasiController@suratDispensasiPimpinan');
        Route::get('surat-dispensasi/search', 'SuratDispensasiController@search');
        Route::resource('surat-dispensasi','SuratDispensasiController')->only(['show']);
        Route::post('surat-dispensasi/pengajuan/tanda-tangan','SuratDispensasiController@tandaTanganDispensasi');
        // Surat Rekomendasi
        Route::get('surat-rekomendasi', 'SuratRekomendasiController@suratRekomendasiPimpinan');
        Route::get('surat-rekomendasi/search', 'SuratRekomendasiController@search');
        Route::resource('surat-rekomendasi','SuratRekomendasiController')->only(['show']);
        Route::post('surat-rekomendasi/pengajuan/tanda-tangan','SuratRekomendasiController@tandaTanganRekomendasi');
        // Surat Tugas
        Route::get('surat-tugas', 'SuratTugasController@suratTugasPimpinan');
        Route::get('surat-tugas/search', 'SuratTugasController@search');
        Route::resource('surat-tugas','SuratTugasController')->only(['show']);
        Route::post('surat-tugas/pengajuan/tanda-tangan','SuratTugasController@tandaTanganTugas');
        // Surat Persetujuan Pindah
        Route::get('surat-persetujuan-pindah', 'SuratPersetujuanPindahController@suratPindahPimpinan');
        Route::get('surat-persetujuan-pindah/search', 'SuratPersetujuanPindahController@searchPimpinan');
        Route::get('surat-persetujuan-pindah/pengajuan/{pengajuan_persetujuan_pindah}','PengajuanSuratPersetujuanPindahController@show');
        Route::resource('surat-persetujuan-pindah','SuratPersetujuanPindahController')->only('show');
        Route::post('surat-persetujuan-pindah/pengajuan/tanda-tangan','SuratPersetujuanPindahController@tandaTanganPindah');
        // Surat Pengantar Cuti
        Route::get('surat-pengantar-cuti/search', 'SuratPengantarCutiController@search');
        Route::resource('surat-pengantar-cuti','SuratPengantarCutiController');
        //  Surat Pengantar Beasiswa
        Route::get('surat-pengantar-beasiswa', 'SuratPengantarBeasiswaController@suratBeasiswaPimpinan');
        Route::get('surat-pengantar-beasiswa/search', 'SuratPengantarBeasiswaController@searchPimpinan');
        Route::resource('surat-pengantar-beasiswa', 'SuratPengantarBeasiswaController')->only('show');
        Route::post('surat-pengantar-beasiswa/pengajuan/tanda-tangan','SuratPengantarBeasiswaController@tandaTanganBeasiswa');
        //  Surat Kegiatan Mahasiswa
        Route::get('surat-kegiatan-mahasiswa', 'SuratKegiatanMahasiswaController@suratKegiatanPimpinan');
        Route::get('surat-kegiatan-mahasiswa/search', 'SuratKegiatanMahasiswaController@searchPimpinan');
        Route::resource('surat-kegiatan-mahasiswa','SuratKegiatanMahasiswaController')->only('show');
        Route::get('surat-kegiatan-mahasiswa/pengajuan/{pengajuan_kegiatan_mahasiswa}','PengajuanSuratKegiatanMahasiswaController@show');
        Route::get('surat-kegiatan-mahasiswa/{pengajuan_kegiatan_mahasiswa}/disposisi','PengajuanSuratKegiatanMahasiswaController@detailDisposisi');
        Route::post('surat-kegiatan-mahasiswa/pengajuan/tanda-tangan','SuratKegiatanMahasiswaController@tandaTanganKegiatan');
        Route::post('surat-kegiatan-mahasiswa/disposisi','PengajuanSuratKegiatanMahasiswaController@disposisi');
        // Mahasiswa
        Route::get('mahasiswa/search', 'MahasiswaController@search');
        Route::get('mahasiswa', 'MahasiswaController@mahasiswaPimpinan');
        Route::get('detail/mahasiswa/{mahasiswa}','MahasiswaController@show');
        Route::get('mahasiswa/{mahasiswa}','MahasiswaController@showPimpinan');
        // Surat Keterangan Lulus
        Route::get('surat-keterangan-lulus/search', 'SuratKeteranganLulusController@searchPimpinan');
        Route::get('surat-keterangan-lulus', 'SuratKeteranganLulusController@suratLulusPimpinan');
        Route::get('surat-keterangan-lulus/{surat_keterangan_lulus}','SuratKeteranganLulusController@show');
        Route::post('surat-keterangan-lulus/pengajuan/tanda-tangan','SuratKeteranganLulusController@tandaTanganLulus');
        // Surat Permohonan Pengambilan Material
        Route::get('surat-permohonan-pengambilan-material/search', 'SuratPermohonanPengambilanMaterialController@searchPimpinan');
        Route::get('surat-permohonan-pengambilan-material','SuratPermohonanPengambilanMaterialController@suratMaterialPimpinan');
        Route::get('surat-permohonan-pengambilan-material/{surat_material}','SuratPermohonanPengambilanMaterialController@show');
        Route::post('surat-permohonan-pengambilan-material/pengajuan/tanda-tangan','SuratPermohonanPengambilanMaterialController@tandaTanganMaterial');
        // Surat Permohonan Survei
        Route::get('surat-permohonan-survei','SuratPermohonanSurveiController@suratSurveiPimpinan');
        Route::get('surat-permohonan-survei/search', 'SuratPermohonanSurveiController@searchPimpinan');
        Route::get('surat-permohonan-survei/{surat_permohonan_survei}','SuratPermohonanSurveiController@show');
        Route::post('surat-permohonan-survei/pengajuan/tanda-tangan','SuratPermohonanSurveiController@tandaTanganSurvei');
        // Surat Rekomendasi Penelitian
        Route::get('surat-rekomendasi-penelitian','SuratRekomendasiPenelitianController@suratPenelitianPimpinan');
        Route::get('surat-rekomendasi-penelitian/search', 'SuratRekomendasiPenelitianController@searchPimpinan');
        Route::get('surat-rekomendasi-penelitian/{surat_rekomendasi_penelitian}','SuratRekomendasiPenelitianController@show');
        Route::post('surat-rekomendasi-penelitian/pengajuan/tanda-tangan','SuratRekomendasiPenelitianController@tandaTanganPenelitian');
        // Surat Permohonan Pengambilan Data Awal
        Route::get('surat-permohonan-pengambilan-data-awal','SuratPermohonanPengambilanDataAwalController@suratDataAwalPimpinan');
        Route::get('surat-permohonan-pengambilan-data-awal/search', 'SuratPermohonanPengambilanDataAwalController@searchPimpinan');
        Route::get('surat-permohonan-pengambilan-data-awal/{surat_data_awal}','SuratPermohonanPengambilanDataAwalController@show');
        Route::post('surat-permohonan-pengambilan-data-awal/pengajuan/tanda-tangan','SuratPermohonanPengambilanDataAwalController@tandaTanganDataAwal');
         // Notifikasi
        Route::get('notifikasi/{notifikasi_user}','NotifikasiUserController@show');
        Route::get('notifikasi','NotifikasiUserController@index');
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
