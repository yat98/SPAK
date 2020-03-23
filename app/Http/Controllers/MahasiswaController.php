<?php

namespace App\Http\Controllers;

use Session;
use App\Jurusan;
use App\Mahasiswa;
use App\ProgramStudi;
use App\TahunAkademik;
use App\StatusMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\MahasiswaRequest;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswaList = Mahasiswa::all()->sortBy('angkatan');
        $countMahasiswa = $mahasiswaList->count();
        $countProdi = ProgramStudi::all()->count();
        $countJurusan = Jurusan::all()->count();
        return view('user.'.$this->segmentUser.'.mahasiswa',compact('mahasiswaList','countMahasiswa','countProdi','countJurusan'));
    }

    public function create()
    {
        $countProdi = ProgramStudi::all()->count();
        if($countProdi < 1){
            Session::flash('info-title','Data Program Studi Kosong');
            Session::flash('info','Tambahkan data program studi terlebih dahulu sebelum menambahkan data mahasiswa!');
            return redirect($this->segmentUser.'/mahasiswa');
        }
        $prodiList = $this->generateProdi();
        $angkatan = $this->generateAngkatan();
        return view('user.'.$this->segmentUser.'.tambah_mahasiswa',compact('prodiList','angkatan'));
    }

    public function store(MahasiswaRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        Session::flash('success-title','Berhasil');
        Session::flash('success','Data mahasiswa dengan nama '.strtolower($input['nama']).' berhasil ditambahkan');
        Mahasiswa::create($input);
        return redirect($this->segmentUser.'/mahasiswa');
    }

    public function edit(Mahasiswa $mahasiswa)
    {   
        $angkatan = $this->generateAngkatan();
        $prodiList = $this->generateProdi();
        return view('user.'.$this->segmentUser.'.edit_mahasiswa',compact('mahasiswa','angkatan','prodiList'));
    }

    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $jurusan->update($request->all());
        return redirect($this->segmentUser.'/mahasiswa');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect($this->segmentUser.'/mahasiswa');
    }

    public function importData(){
        
    }

    private function generateAngkatan(){
        $tahun = [];
        for($i = 2000;$i <= 2099;$i++){
            $tahun[$i] = $i;
        }
        return $tahun;
    }

    private function generateProdi(){
        $prodi = ProgramStudi::all();
        $prodiList = [];
        foreach ($prodi as $value) {
            $prodiList[$value->id] = $value->strata.' - '.$value->nama_prodi;
        }
        return $prodiList;
    }
}
