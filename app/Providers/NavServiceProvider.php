<?php

namespace App\Providers;

use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
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
        $posisi = '';
        $segment = request()->segment(2);
        if(request()->segment(1) == 'admin'){ 
            $posisi = 'admin'  ;
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
            if($segment == 'user'){
                $halaman = 'user';
            }
            if($segment == 'status-mahasiswa'){
                $halaman = 'status-mahasiswa';
            }
            if($segment == 'ormawa'){
                $halaman = 'ormawa';
            }
            if($segment == 'pimpinan-ormawa'){
                $halaman = 'pimpinan-ormawa';
            }
            if($segment == 'profil'){
                $halaman = 'profil';
            }
        }else if(request()->segment(1) == 'mahasiswa'){
            $posisi = 'mahasiswa';
            if($segment == ''){
                $halaman = 'dashboard-mahasiswa';
            }
            if($segment == 'pengajuan'){
                $halaman = 'surat-keterangan-aktif-kuliah';
            }
        }else if(request()->segment(1) == 'pegawai'){
            $pengajuanSurat = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                ->where('status','diajukan');
            $jenisSurat = $pengajuanSurat->groupBy('jenis_surat')->pluck('jenis_surat')->toArray();
            
            $countAllPengajuanSuratKeterangan= $pengajuanSurat->count();
            $posisi = 'pegawai';
            if($segment == ''){
                $halaman = 'dashboard-pegawai';
            }
            if($segment == 'kode-surat'){
                $halaman = 'kode-surat';
            }
            if($segment == 'tanda-tangan'){
                $halaman = 'tanda-tangan';
            }
            if($segment == 'surat-keterangan-aktif-kuliah'){
                $halaman = 'surat-keterangan-aktif-kuliah';
            }
            if($segment == 'profil'){
                $halaman = 'profil';
            }
            view()->share([
                'countAllPengajuanSuratKeterangan'=>$countAllPengajuanSuratKeterangan,
                'jenisSurat'=>$jenisSurat
            ]);
        }
        view()->share(['halaman'=>$halaman,'posisi'=>$posisi]);
    }
}
