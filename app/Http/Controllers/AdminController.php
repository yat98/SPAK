<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Mahasiswa;
use App\ProgramStudi;
use App\TahunAkademik;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        $jurusanList = Jurusan::all()->sortBy('updated_at');
        $prodiList = ProgramStudi::all()->sortBy('updated_at');
        $tahunAkademikList = TahunAkademik::orderBy('status_aktif')->get();
        $mahasiswaList = Mahasiswa::all()->sortBy('updated_at');
        $countJurusan = $jurusanList->count();
        $countProdi = $prodiList->count();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $countMahasiswa = $mahasiswaList->count();
        $countTahunAkademik = $tahunAkademikList->count();
        $jurusanList = $jurusanList->take(5);
        $prodiList = $prodiList->take(5);
        $tahunAkademikList = $tahunAkademikList->take(5);
        $mahasiswaList = $mahasiswaList->take(5);
        return view('user.'.$this->segmentUser.'.dashboard',compact('countJurusan','countProdi','countMahasiswa','tahunAkademikAktif','jurusanList','prodiList','tahunAkademikList','mahasiswaList','countTahunAkademik'));
    }
}
