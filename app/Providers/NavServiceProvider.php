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
        $show = true;
        $segment = request()->segment(2);
        if (request()->segment(1) == 'admin') {
            $posisi = 'admin';
            $show = false;
            if ($segment == '') {
                $halaman = 'dashboard-admin';
            } else if ($segment == 'jurusan') {
                $halaman = 'jurusan';
            } else if ($segment == 'program-studi') {
                $halaman = 'program-studi';
            } else if ($segment == 'tahun-akademik') {
                $halaman = 'tahun-akademik';
            } else if ($segment == 'mahasiswa') {
                 $show = true;
                $halaman = 'mahasiswa';
            } else if ($segment == 'user') {
                 $show = true;
                $halaman = 'user';
            } else if ($segment == 'operator') {
                $show = true;
                $halaman = 'operator';
            } else if ($segment == 'status-mahasiswa') {
                $halaman = 'status-mahasiswa';
            } else if ($segment == 'ormawa') {
                $halaman = 'ormawa';
            } else if ($segment == 'pimpinan-ormawa') {
                $halaman = 'pimpinan-ormawa';
            } else if ($segment == 'profil') {
                $halaman = 'profil';
            }
        } else if (request()->segment(1) == 'mahasiswa') {
            $posisi = 'mahasiswa';
            if ($segment == '') {
                $show = false;
                $halaman = 'dashboard-mahasiswa';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-keterangan-aktif-kuliah') {
                $halaman = 'surat-keterangan-aktif-kuliah';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-keterangan-kelakuan-baik') {
                $halaman = 'surat-keterangan-kelakuan-baik';
            } else if ($segment == 'surat-dispensasi') {
                $halaman = 'surat-dispensasi';
            } else if ($segment == 'surat-rekomendasi') {
                $halaman = 'surat-rekomendasi';
            } else if ($segment == 'surat-tugas') {
                $halaman = 'surat-tugas';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-persetujuan-pindah') {
                $halaman = 'surat-persetujuan-pindah';
            } else if ($segment == 'pendaftaran-cuti') {
                $show = false;
                $halaman = 'pendaftaran-cuti';
            } else if (($segment == 'pengajuan' && request()->segment(3) == 'surat-kegiatan-mahasiswa') || $segment == 'surat-kegiatan-mahasiswa') {
                $halaman = 'surat-kegiatan-mahasiswa';
            } else if ($segment == 'profil') {
                $show = false;
                $halaman = 'profil';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-keterangan-lulus') {
                $halaman = 'surat-keterangan-lulus';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-permohonan-pengambilan-material') {
                $halaman = 'surat-permohonan-pengambilan-material';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-permohonan-survei') {
                $halaman = 'surat-permohonan-survei';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-rekomendasi-penelitian') {
                $halaman = 'surat-rekomendasi-penelitian';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-permohonan-pengambilan-data-awal') {
                $halaman = 'surat-permohonan-pengambilan-data-awal';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-keterangan-bebas-perpustakaan') {
                $halaman = 'surat-keterangan-bebas-perpustakaan';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-keterangan-bebas-perlengkapan') {
                $halaman = 'surat-keterangan-bebas-perlengkapan';
            }
        } elseif (request()->segment(1) == 'pegawai') {
            $posisi = 'pegawai';
            if ($segment == '') {
                $show = false;
                $halaman = 'dashboard-pegawai';
            } else if ($segment == 'surat-keterangan-aktif-kuliah') {
                $halaman = 'surat-keterangan-aktif-kuliah';
            } else if ($segment == 'surat-keterangan-kelakuan-baik') {
                $halaman = 'surat-keterangan-kelakuan-baik';
            } else if ($segment == 'profil') {
                $show = false;
                $halaman = 'profil';
            } else if($segment == 'surat-masuk'){
                $halaman = 'surat-masuk';
            } else if($segment == 'surat-dispensasi'){
                $halaman = 'surat-dispensasi';
            } else if($segment == 'surat-rekomendasi'){
                $halaman = 'surat-rekomendasi';
            } else if($segment == 'surat-tugas'){
                $halaman = 'surat-tugas';
            } else if ($segment == 'surat-persetujuan-pindah') {
                $halaman = 'surat-persetujuan-pindah';
            } else if ($segment == 'waktu-cuti') {
                $halaman = 'waktu-cuti';
            } else if ($segment == 'pendaftaran-cuti') {
                $halaman = 'pendaftaran-cuti';
            } else if($segment == 'surat-pengantar-cuti'){
                $halaman = 'surat-pengantar-cuti';
            } else if($segment == 'surat-pengantar-beasiswa'){
                $halaman = 'surat-pengantar-beasiswa';
            } else if ($segment == 'surat-kegiatan-mahasiswa') {
                $halaman = 'surat-kegiatan-mahasiswa';
            } else if($segment == 'surat-keterangan-lulus'){
                $halaman = 'surat-keterangan-lulus';
            } else if($segment == 'surat-permohonan-pengambilan-material'){
                $halaman = 'surat-permohonan-pengambilan-material';
            } else if ($segment == 'surat-permohonan-survei') {
                $halaman = 'surat-permohonan-survei';
            } else if ($segment == 'surat-rekomendasi-penelitian') {
                $halaman = 'surat-rekomendasi-penelitian';
            } else if ($segment == 'surat-permohonan-pengambilan-data-awal') {
                $halaman = 'surat-permohonan-pengambilan-data-awal';
            } else if ($segment == 'surat-keterangan-bebas-perpustakaan') {
                $halaman = 'surat-keterangan-bebas-perpustakaan';
            } else if ($segment == 'laporan') {
                $show = false;
                $halaman = 'laporan';
            }
        } elseif (request()->segment(1) == 'pimpinan') {
            $posisi = 'pimpinan';
            if ($segment == '') {
                $show = false;
                $halaman = 'dashboard-pimpinan';
            } else if ($segment == 'tanda-tangan') {
                $show = false;
                $halaman = 'tanda-tangan';
            } else if ($segment == 'kode-surat') {
                $show = false;
                $halaman = 'kode-surat';
            } else if ($segment == 'surat-keterangan-aktif-kuliah') {
                $halaman = 'surat-keterangan-aktif-kuliah';
            } else if ($segment == 'surat-masuk') {
                $halaman = 'surat-masuk';
            } else if ($segment == 'mahasiswa') {
                $show = false;
                $halaman = 'mahasiswa';
            } else if ($segment == 'surat-keterangan-kelakuan-baik') {
                $halaman = 'surat-keterangan-kelakuan-baik';
            } else if($segment == 'surat-dispensasi'){
                $halaman = 'surat-dispensasi';
            } else if($segment == 'surat-rekomendasi'){
                $halaman = 'surat-rekomendasi';
            } else if($segment == 'surat-tugas'){
                $halaman = 'surat-tugas';
            } else if ($segment == 'surat-persetujuan-pindah') {
                $halaman = 'surat-persetujuan-pindah';
            } else if($segment == 'surat-pengantar-cuti'){
                $halaman = 'surat-pengantar-cuti';
            } else if($segment == 'surat-pengantar-beasiswa'){
                $halaman = 'surat-pengantar-beasiswa';
            } else if($segment == 'surat-kegiatan-mahasiswa'){
                $halaman = 'surat-kegiatan-mahasiswa';
            } else if ($segment == 'profil') {
                $show = false;
                $halaman = 'profil';
            } else if($segment == 'surat-keterangan-lulus'){
                $halaman = 'surat-keterangan-lulus';
            } else if($segment == 'surat-permohonan-pengambilan-material'){
                $halaman = 'surat-permohonan-pengambilan-material';
            } else if ($segment == 'surat-permohonan-survei') {
                $halaman = 'surat-permohonan-survei';
            } else if ($segment == 'surat-rekomendasi-penelitian') {
                $halaman = 'surat-rekomendasi-penelitian';
            } else if ($segment == 'surat-permohonan-pengambilan-data-awal') {
                $halaman = 'surat-permohonan-pengambilan-data-awal';
            }
        } else if (request()->segment(1) == 'operator'){
            $posisi = 'operator';
            $show = false;
            if ($segment == '') {
                $halaman = 'dashboard-operator';
            } else if ($segment == 'profil') {
                $halaman = 'profil';
            } else if (($segment == 'pengajuan' && request()->segment(3) == 'surat-keterangan-aktif-kuliah') || $segment == 'surat-keterangan-aktif-kuliah') {
                $show = true;
                $halaman = 'surat-keterangan-aktif-kuliah';
            } else if ($segment == 'surat-masuk') {
                $show = true;
                $halaman = 'surat-masuk';
            } else if (($segment == 'pengajuan' && request()->segment(3) == 'surat-keterangan-kelakuan-baik') || $segment == 'surat-keterangan-kelakuan-baik') {
                $show = true;
                $halaman = 'surat-keterangan-kelakuan-baik';
            }
        }
        view()->share(['halaman'=>$halaman,'posisi'=>$posisi,'show'=>$show]);
    }
}
