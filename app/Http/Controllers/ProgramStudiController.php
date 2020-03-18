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
        return view('user.'.$this->segmentUser.'.program_studi',compact('prodiList','countProdi'));
    }

    public function create()
    {
        $jurusanList = Jurusan::pluck('nama_jurusan','id')->toArray();
        return view('user.'.$this->segmentUser.'.tambah_prodi',compact('jurusanList'));
    }
    
    public function store(ProgramStudiRequest $request)
    {
        $input = $request->all();
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
        $prodi->update($request->all());
        return redirect($this->segmentUser.'/program-studi');
    }

    public function destroy(ProgramStudi $prodi)
    {
        $prodi->delete();
        return redirect($this->segmentUser.'/program-studi');
    }
}
