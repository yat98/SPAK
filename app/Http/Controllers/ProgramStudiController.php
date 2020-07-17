<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\ProgramStudi;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ProgramStudiRequest;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $countAllProdi = ProgramStudi::count();
        $countAllJurusan = Jurusan::count();
        return view('user.'.$this->segmentUser.'.program_studi',compact('countAllJurusan','countAllProdi','perPage'));
    }

    public function getAllProdi(){
        return DataTables::of(ProgramStudi::with('jurusan'))
                ->addColumn('aksi', function ($data) {
                    return $data->id;
                })
                ->make(true);
    }

    public function getLimitProdi(){
        return DataTables::collection(ProgramStudi::all()->take(5)->sortByDesc('updated_at')->load('jurusan'))
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->diffForHumans();
                    })
                    ->editColumn("updated_at", function ($data) {
                        return $data->updated_at->diffForHumans();
                    })
                    ->toJson();
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

    public function show(ProgramStudi $prodi){
        $data = collect($prodi);
        $data->put('created_at',$prodi->created_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('updated_at',$prodi->updated_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('nama_jurusan',$prodi->jurusan->nama_jurusan);

        return $data->toJson();
    }
    
    public function store(ProgramStudiRequest $request)
    {
        $input = $request->all();
        ProgramStudi::create($input);
        $this->setFlashData('success','Berhasil','Data program studi '.strtolower($input['nama_prodi']).' berhasil ditambahkan');
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
