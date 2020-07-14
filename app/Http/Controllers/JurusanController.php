<?php

namespace App\Http\Controllers;

use App\Jurusan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\JurusanRequest;

class JurusanController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $countAllJurusan = Jurusan::count();
        return view('user.'.$this->segmentUser.'.jurusan',compact('perPage','countAllJurusan'));
    }

    public function getAllJurusan(){
        return DataTables::of(Jurusan::select(['*']))
                ->addColumn('aksi', function ($data) {
                    return $data->id;
                })
                ->make(true);
    }

    public function show(Jurusan $jurusan){
        $data = collect($jurusan);
        $data->put('created_at',$jurusan->created_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('updated_at',$jurusan->updated_at->isoFormat('D MMMM Y H:mm:ss'));

        return $data->toJson();
    }

    public function create()
    {
        return view('user.'.$this->segmentUser.'.tambah_jurusan');
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
