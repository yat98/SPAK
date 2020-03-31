<?php

namespace App\Http\Controllers;

use App\Jurusan;
use Illuminate\Http\Request;
use App\Http\Requests\JurusanRequest;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusanList = Jurusan::all();
        $countJurusan = $jurusanList->count();
        $countAllJurusan = $countJurusan;
        return view('user.'.$this->segmentUser.'.jurusan',compact('jurusanList','countJurusan','countAllJurusan'));
    }

    public function create()
    {
        return view('user.'.$this->segmentUser.'.tambah_jurusan');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keyword'])){
            $nama = $keyword['keyword'] != null ? $keyword['keyword'] : '';
            $jurusanList = Jurusan::where('nama_jurusan','like','%'.$nama.'%')->get();
            $countJurusan = count($jurusanList);
            $countAllJurusan = Jurusan::all()->count();
            if($countJurusan < 1){
                $this->setFlashData('search','Hasil Pencarian','Data jurusan tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.jurusan',compact('jurusanList','countJurusan','countAllJurusan'));
        }else{
            return redirect($this->segmentUser.'/jurusan');
        }
    }

    public function store(JurusanRequest $request)
    {
        $input = $request->all();
        Jurusan::create($input);
        $this->setFlashData('success','Berhasil','Data jurusan '.strtolower($input['nama_jurusan']).' berhasil ditambahkan');
        return redirect($this->segmentUser.'/jurusan');
    }

    public function edit(Jurusan $jurusan)
    {   
        return view('user.'.$this->segmentUser.'.edit_jurusan',compact('jurusan'));
    }

    public function update(JurusanRequest $request, Jurusan $jurusan)
    {   
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data jurusan '.strtolower($jurusan->nama_jurusan).' berhasil diubah');
        $jurusan->update($input);
        return redirect($this->segmentUser.'/jurusan');
    }

    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();
        $this->setFlashData('success','Berhasil','Data jurusan '.$jurusan->nama_jurusan.' berhasil dihapus');
        return redirect($this->segmentUser.'/jurusan');
    }
}
