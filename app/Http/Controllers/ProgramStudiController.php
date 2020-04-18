<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Requests\ProgramStudiRequest;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $prodiList = ProgramStudi::all()->sortBy('id_jurusan');
        $countProdi = $prodiList->count();
        $countAllProdi = ProgramStudi::all()->count();
        $countJurusan = Jurusan::all()->count();
        return view('user.'.$this->segmentUser.'.program_studi',compact('prodiList','countProdi','countJurusan','countAllProdi'));
    }

    public function create()
    {
        $countJurusan = Jurusan::all()->count();
        if($countJurusan < 1){
            $this->setFlashData('info','Data Jurusan Kosong','Tambahkan data jurusan terlebih dahulu sebelum menambahkan data program studi!');
            return redirect($this->segmentUser.'/program-studi');
        }
        $jurusanList = Jurusan::pluck('nama_jurusan','id')->toArray();
        return view('user.'.$this->segmentUser.'.tambah_prodi',compact('jurusanList'));
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keyword'])){
            $countAllProdi = ProgramStudi::all()->count();
            $countJurusan = Jurusan::all()->count();
            $nama = $keyword['keyword'] != null ? $keyword['keyword'] : '';
            $prodiList = ProgramStudi::where('nama_prodi','like','%'.$nama.'%')->get();
            $countProdi = count($prodiList);
            if($countProdi < 1){
                $this->setFlashData('search','Hasil Pencarian','Data program studi tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.program_studi',compact('prodiList','countProdi','countJurusan','countAllProdi'));
        }else{
            return redirect($this->segmentUser.'/program-studi');
        }
    }
    
    public function store(ProgramStudiRequest $request)
    {
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data program studi '.strtolower($input['nama_prodi']).' berhasil ditambahkan');
        ProgramStudi::create($input);
        return redirect($this->segmentUser.'/program-studi');
    }
    
    public function edit(ProgramStudi $prodi)
    {   
        $jurusanList = Jurusan::pluck('nama_jurusan','id')->toArray();
        return view('user.'.$this->segmentUser.'.edit_prodi',compact('prodi','jurusanList'));
    }

    public function update(ProgramStudiRequest $request, ProgramStudi $prodi)
    {
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data program studi '.strtolower($prodi->nama_prodi).' berhasil diubah');
        $prodi->update($request->all());
        return redirect($this->segmentUser.'/program-studi');
    }

    public function destroy(ProgramStudi $prodi)
    {
        $prodi->delete();
        $this->setFlashData('success','Berhasil','Data program studi '.strtolower($prodi->nama_prodi).' berhasil dihapus');
        return redirect($this->segmentUser.'/program-studi');
    }
}
