<?php

namespace App\Providers;

use App\KodeSurat;
use App\Mahasiswa;
use App\SuratTugas;
use App\ProgramStudi;
use App\TahunAkademik;
use App\PimpinanOrmawa;
use App\SuratKeterangan;
use App\SuratPengantarCuti;
use App\PengajuanSuratTugas;
use App\SuratKeteranganLulus;
use App\SuratPermohonanSurvei;
use App\SuratKegiatanMahasiswa;
use App\SuratPengantarBeasiswa;
use App\SuratPersetujuanPindah;
use App\PengajuanSuratDispensasi;
use App\PengajuanSuratKeterangan;
use App\PengajuanSuratRekomendasi;
use App\SuratRekomendasiPenelitian;
use Illuminate\Support\Facades\Route;
use App\PengajuanSuratKeteranganLulus;
use App\PengajuanSuratPermohonanSurvei;
use App\DisposisiSuratKegiatanMahasiswa;
use App\PengajuanSuratKegiatanMahasiswa;
use App\PengajuanSuratPersetujuanPindah;
use App\SuratKeteranganBebasPerlengkapan;
use App\SuratKeteranganBebasPerpustakaan;
use App\SuratPermohonanPengambilanDataAwal;
use App\SuratPermohonanPengambilanMaterial;
use App\PengajuanSuratRekomendasiPenelitian;
use App\PengajuanSuratKeteranganBebasPerlengkapan;
use App\PengajuanSuratKeteranganBebasPerpustakaan;
use App\PengajuanSuratPermohonanPengambilanDataAwal;
use App\PengajuanSuratPermohonanPengambilanMaterial;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
        Route::model('program_studi', ProgramStudi::class);
        Route::model('tahun_akademik', TahunAkademik::class);
        Route::model('kode_surat', KodeSurat::class);
        Route::model('pimpinan_ormawa', PimpinanOrmawa::class);
        Route::model('nim', Mahasiswa::class);
        Route::model('pengajuan_surat_keterangan', PengajuanSuratKeterangan::class);
        Route::model('surat_tugas', SuratTugas::class);
        Route::model('pengajuan_persetujuan_pindah', PengajuanSuratPersetujuanPindah::class);
        Route::model('surat_keterangan_aktif_kuliah', SuratKeterangan::class);
        Route::model('surat_keterangan_kelakuan_baik', SuratKeterangan::class);
        Route::model('pengajuan_surat_dispensasi', PengajuanSuratDispensasi::class);
        Route::model('pengajuan_surat_rekomendasi', PengajuanSuratRekomendasi::class);
        Route::model('pengajuan_surat_tugas', PengajuanSuratTugas::class);
        Route::model('surat_pengantar_cuti', SuratPengantarCuti::class);
        Route::model('surat_persetujuan_pindah', SuratPersetujuanPindah::class);
        Route::model('surat_pengantar_beasiswa', SuratPengantarBeasiswa::class);
        Route::model('pengajuan_kegiatan_mahasiswa', PengajuanSuratKegiatanMahasiswa::class);
        Route::model('surat_kegiatan_mahasiswa', SuratKegiatanMahasiswa::class);
        Route::model('pengajuan_surat_lulus', PengajuanSuratKeteranganLulus::class);
        Route::model('surat_keterangan_lulus', SuratKeteranganLulus::class);
        Route::model('pengajuan_surat_material', PengajuanSuratPermohonanPengambilanMaterial::class);
        Route::model('surat_material', SuratPermohonanPengambilanMaterial::class);
        Route::model('pengajuan_surat_survei', PengajuanSuratPermohonanSurvei::class);
        Route::model('pengajuan_surat_penelitian', PengajuanSuratRekomendasiPenelitian::class);
        Route::model('pengajuan_surat_data_awal', PengajuanSuratPermohonanPengambilanDataAwal::class);
        Route::model('surat_permohonan_survei', SuratPermohonanSurvei::class);
        Route::model('surat_rekomendasi_penelitian',SuratRekomendasiPenelitian::class);
        Route::model('surat_data_awal',SuratPermohonanPengambilanDataAwal::class);
        Route::model('pengajuan_surat_perlengkapan',PengajuanSuratKeteranganBebasPerlengkapan::class);
        Route::model('surat_perlengkapan',SuratKeteranganBebasPerlengkapan::class);
        Route::model('pengajuan_surat_perpustakaan',PengajuanSuratKeteranganBebasPerpustakaan::class);
        Route::model('surat_perpustakaan',SuratKeteranganBebasPerpustakaan::class); 
        Route::model('disposisi_surat_kegiatan',DisposisiSuratKegiatanMahasiswa::class); 
}

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
