<?php

namespace App\Http\Controllers;

use App\Ormawa;
use App\Jurusan;
use Illuminate\Http\Request;
use App\Http\Requests\OrmawaRequest;

class OrmawaController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $ormawaList = Ormawa::paginate($perPage);
        $countOrmawa = $ormawaList->count();
        $countAllOrmawa = $countOrmawa;
        $jurusan = Jurusan::pluck('nama_jurusan','id')->toArray();
        return view('user.'.$this->segmentUser.'.ormawa',compact('ormawaList','countOrmawa','countAllOrmawa','jurusan','perPage'));
    }
    
    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keyword']) || isset($keyword['jurusan'])){
            $perPage = $this->perPage;
            $nama = $keyword['keyword'] != null ? $keyword['keyword'] : '';
            $ormawaList = Ormawa::where('nama','like','%'.$nama.'%');
            (isset($keyword['jurusan'])) ? $ormawaList = $ormawaList->where('id_jurusan',$keyword['jurusan']):'';
            $ormawaList= $ormawaList->paginate($perPage)->appends($request->except('page'));
            $countOrmawa = count($ormawaList);
            $countAllOrmawa = Ormawa::all()->count();
            if($countOrmawa < 1){
                $this->setFlashData('search','Hasil Pencarian','Data ormawa tidak ditemukan!');
            }
            $jurusan = Jurusan::pluck('nama_jurusan','id')->toArray();
            return view('user.'.$this->segmentUser.'.ormawa',compact('ormawaList','countOrmawa','countAllOrmawa','jurusan','perPage'));
        }else{
            return redirect($this->segmentUser.'/ormawa');
        }
    }

    public function create(){
        $jurusan = Jurusan::pluck('nama_jurusan','id')->toArray();
        return view('user.'.$this->segmentUser.'.tambah_ormawa',compact('jurusan'));
    }
    
    public function store(OrmawaRequest $request){
        $input = $request->all();
        Ormawa::create($input);
        $this->setFlashData('success','Berhasil','Data ormawa '.strtolower($input['nama']).' berhasil ditambahkan');
        return redirect($this->segmentUser.'/ormawa');
    }
    
    public function edit(Ormawa $ormawa){
        $jurusan = Jurusan::pluck('nama_jurusan','id')->toArray();
        return view('user.'.$this->segmentUser.'.edit_ormawa',compact('ormawa','jurusan'));
    }

    public function update(OrmawaRequest $request,Ormawa $ormawa){
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data ormawa '.strtolower($ormawa->nama).' berhasil diubah');
        $ormawa->update($input);
        return redirect($this->segmentUser.'/ormawa');
    }

    public function destroy(Ormawa $ormawa){
        $ormawa->delete();
        $this->setFlashData('success','Berhasil','Data ormawa '.$ormawa->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/ormawa');
    }
}
