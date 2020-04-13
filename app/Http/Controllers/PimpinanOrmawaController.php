<?php

namespace App\Http\Controllers;

use App\Ormawa;
use App\Jurusan;
use App\PimpinanOrmawa;
use Illuminate\Http\Request;
use App\Http\Requests\PimpinanOrmawaRequest;

class PimpinanOrmawaController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $pimpinanOrmawaList = PimpinanOrmawa::paginate($perPage);
        $countPimpinanOrmawa = $pimpinanOrmawaList->count();
        $countAllPimpinanOrmawa = $countPimpinanOrmawa;
        $jurusan = Jurusan::pluck('nama_jurusan','id')->toArray();
        $mahasiswa = $this->generateMahasiswa();
        $ormawaList = Ormawa::pluck('nama','id')->toArray();
        return view('user.'.$this->segmentUser.'.pimpinan_ormawa',compact('pimpinanOrmawaList','countPimpinanOrmawa','countAllPimpinanOrmawa','jurusan','perPage','mahasiswa','ormawaList'));
    }

    public function create()
    {
        $ormawaList = Ormawa::pluck('nama','id')->toArray();
        $mahasiswaList = $this->generateMahasiswa();
        return view('user.'.$this->segmentUser.'.tambah_pimpinan_ormawa',compact('mahasiswaList','ormawaList'));
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['jurusan']) || isset($keyword['ormawa']) || isset($keyword['status_aktif']) ){
            $perPage = $this->perPage;
            $nim = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $pimpinanOrmawaList = PimpinanOrmawa::where('pimpinan_ormawa.nim','like','%'.$nim.'%')
                                    ->join('mahasiswa','mahasiswa.nim','=','pimpinan_ormawa.nim')
                                    ->join('prodi','prodi.id','=','mahasiswa.id_prodi');
            (isset($keyword['jurusan'])) ? $pimpinanOrmawaList = $pimpinanOrmawaList->where('id_jurusan',$keyword['jurusan']):'';
            (isset($keyword['ormawa'])) ? $pimpinanOrmawaList = $pimpinanOrmawaList->where('id_ormawa',$keyword['ormawa']):'';
            (isset($keyword['status_aktif'])) ? $pimpinanOrmawaList = $pimpinanOrmawaList->where('status_aktif',$keyword['status_aktif']):'';
            $countAllPimpinanOrmawa = PimpinanOrmawa::all()->count();
            $jurusan = Jurusan::pluck('nama_jurusan','id')->toArray();
            $mahasiswa = $this->generateMahasiswa();
            $ormawaList = Ormawa::pluck('nama','id')->toArray();
            $pimpinanOrmawaList= $pimpinanOrmawaList->paginate($perPage)->appends($request->except('page'));
            $countPimpinanOrmawa = $pimpinanOrmawaList->count();
            if($countPimpinanOrmawa < 1){
                $this->setFlashData('search','Hasil Pencarian','Data pimpinan ormawa tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.pimpinan_ormawa',compact('pimpinanOrmawaList','countPimpinanOrmawa','countAllPimpinanOrmawa','jurusan','perPage','mahasiswa','ormawaList'));
        }else{
            return redirect($this->segmentUser.'/pimpinan-ormawa');
        }
        dd($keyword);
    }

    public function store(PimpinanOrmawaRequest $request)
    {
        $input = $request->all();
        $pimpinanOrmawa = PimpinanOrmawa::create($input);
        $this->setFlashData('success','Berhasil','Data pimpinan ormawa dengan nim'.strtolower($input['nim']).' berhasil ditambahkan');
        return redirect($this->segmentUser.'/pimpinan-ormawa');
    }

    public function edit(PimpinanOrmawa $pimpinanOrmawa)
    {
        $ormawaList = Ormawa::pluck('nama','id')->toArray();
        $mahasiswaList = $this->generateMahasiswa();
        return view('user.'.$this->segmentUser.'.edit_pimpinan_ormawa',compact('mahasiswaList','pimpinanOrmawa','ormawaList'));
    }

    public function update(PimpinanOrmawaRequest $request,PimpinanOrmawa $pimpinanOrmawa)
    {
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data pimpinan ormawa dengan nama '.strtolower($pimpinanOrmawa->mahasiswa->nama).' berhasil diubah');
        $pimpinanOrmawa->update($input);
        return redirect($this->segmentUser.'/pimpinan-ormawa');
    }

    public function destroy(PimpinanOrmawa $pimpinanOrmawa)
    {
        $pimpinanOrmawa->delete();
        $this->setFlashData('success','Berhasil','Data pimpinan ormawa dengan nama '.strtolower($pimpinanOrmawa->mahasiswa->nama).' berhasil dihapus');
        return redirect($this->segmentUser.'/pimpinan-ormawa');
    }
}
