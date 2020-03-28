<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Requests\ProgramStudiRequest;
use Session;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $prodiList = ProgramStudi::all()->sortBy('id_jurusan');
        $countProdi = $prodiList->count();
        $countJurusan = Jurusan::all()->count();
        return view('user.'.$this->segmentUser.'.program_studi',compact('prodiList','countProdi','countJurusan'));
    }

    public function create()
    {
        $countJurusan = Jurusan::all()->count();
        if($countJurusan < 1){
            Session::flash('info-title','Data Jurusan Kosong');
            Session::flash('info','Tambahkan data jurusan terlebih dahulu sebelum menambahkan data program studi!');
            return redirect($this->segmentUser.'/program-studi');
        }
        $jurusanList = Jurusan::pluck('nama_jurusan','id')->toArray();
        return view('user.'.$this->segmentUser.'.tambah_prodi',compact('jurusanList'));
    }
    
    public function store(ProgramStudiRequest $request)
    {
        $input = $request->all();
        Session::flash('success-title','Berhasil');
        Session::flash('success','Data program studi '.strtolower($input['nama_prodi']).' berhasil ditambahkan');
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
        Session::flash('success-title','Berhasil');
        Session::flash('success','Data program studi '.strtolower($prodi->nama_prodi).' berhasil diubah');
        $prodi->update($request->all());
        return redirect($this->segmentUser.'/program-studi');
    }

    public function destroy(ProgramStudi $prodi)
    {
        Session::flash('success-title','Berhasil');
        Session::flash('success','Data program studi '.strtolower($prodi->nama_jurusan).' berhasil dihapus');
        $prodi->delete();
        return redirect($this->segmentUser.'/program-studi');
    }
}
