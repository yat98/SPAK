<?php

namespace App\Providers;

use App\KodeSurat;
use App\SuratTugas;
use App\ProgramStudi;
use App\TahunAkademik;
use App\PimpinanOrmawa;
use App\SuratKeterangan;
use App\SuratPengantarCuti;
use App\SuratKeteranganLulus;
use App\SuratKegiatanMahasiswa;
use App\SuratPengantarBeasiswa;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\Route;
use App\PengajuanSuratKeteranganLulus;
use App\PengajuanSuratKegiatanMahasiswa;
use App\PengajuanSuratPersetujuanPindah;
use App\SuratPermohonanPengambilanMaterial;
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
        Route::model('pimmpinan_ormawa', PimpinanOrmawa::class);
        Route::model('pengajuan_surat_keterangan', PengajuanSuratKeterangan::class);
        Route::model('surat_tuga', SuratTugas::class);
        Route::model('pengajuan_persetujuan_pindah', PengajuanSuratPersetujuanPindah::class);
        Route::model('surat_keterangan_aktif_kuliah', SuratKeterangan::class);
        Route::model('surat_keterangan_kelakuan_baik', SuratKeterangan::class);
        Route::model('surat_pengantar_cuti', SuratPengantarCuti::class);
        Route::model('surat_pengantar_beasiswa', SuratPengantarBeasiswa::class);
        Route::model('pengajuan_kegiatan_mahasiswa', PengajuanSuratKegiatanMahasiswa::class);
        Route::model('surat_kegiatan_mahasiswa', SuratKegiatanMahasiswa::class);
        Route::model('pengajuan_surat_keterangan_lulus', PengajuanSuratKeteranganLulus::class);
        Route::model('surat_keterangan_lulus', SuratKeteranganLulus::class);
        Route::model('pengajuan_surat_material', PengajuanSuratPermohonanPengambilanMaterial::class);
        Route::model('surat_material', SuratPermohonanPengambilanMaterial::class);
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
