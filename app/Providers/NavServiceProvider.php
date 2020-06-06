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
        if (request()->segment(1) == 'admin') {
            $posisi = 'admin'  ;
            if ($segment == '') {
                $halaman = 'dashboard-admin';
            } else if ($segment == 'jurusan') {
                $halaman = 'jurusan';
            } else if ($segment == 'program-studi') {
                $halaman = 'program-studi';
            } else if ($segment == 'tahun-akademik') {
                $halaman = 'tahun-akademik';
            } else if ($segment == 'mahasiswa') {
                $halaman = 'mahasiswa';
            } else if ($segment == 'user') {
                $halaman = 'user';
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
                $halaman = 'pendaftaran-cuti';
            } else if (($segment == 'pengajuan' && request()->segment(3) == 'surat-kegiatan-mahasiswa') || $segment == 'surat-kegiatan-mahasiswa') {
                $halaman = 'surat-kegiatan-mahasiswa';
            } else if ($segment == 'profil') {
                $halaman = 'profil';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-keterangan-lulus') {
                $halaman = 'surat-keterangan-lulus';
            } else if ($segment == 'pengajuan' && request()->segment(3) == 'surat-permohonan-pengambilan-material') {
                $halaman = 'surat-permohonan-pengambilan-material';
            }
        } elseif (request()->segment(1) == 'pegawai') {
            $posisi = 'pegawai';
            if ($segment == '') {
                $halaman = 'dashboard-pegawai';
            } else if ($segment == 'kode-surat') {
                $halaman = 'kode-surat';
            } else if ($segment == 'tanda-tangan') {
                $halaman = 'tanda-tangan';
            } else if ($segment == 'surat-keterangan-aktif-kuliah') {
                $halaman = 'surat-keterangan-aktif-kuliah';
            } else if ($segment == 'surat-keterangan-kelakuan-baik') {
                $halaman = 'surat-keterangan-kelakuan-baik';
            } else if ($segment == 'profil') {
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
            }
        } elseif (request()->segment(1) == 'pimpinan') {
            $posisi = 'pimpinan';
            if ($segment == '') {
                $halaman = 'dashboard-pimpinan';
            } else if ($segment == 'tanda-tangan') {
                $halaman = 'tanda-tangan';
            } else if ($segment == 'surat-keterangan-aktif-kuliah') {
                $halaman = 'surat-keterangan-aktif-kuliah';
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
                $halaman = 'profil';
            } else if($segment == 'surat-keterangan-lulus'){
                $halaman = 'surat-keterangan-lulus';
            }
        }
        view()->share(['halaman'=>$halaman,'posisi'=>$posisi]);
    }
}
