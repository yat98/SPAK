<?php

namespace App\Http\Controllers;

use App\Ormawa;
use DataTables;
use App\Jurusan;
use App\PimpinanOrmawa;
use Illuminate\Http\Request;
use App\Http\Requests\PimpinanOrmawaRequest;

class PimpinanOrmawaController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $countAllPimpinanOrmawa = PimpinanOrmawa::count();
        return view('user.'.$this->segmentUser.'.pimpinan_ormawa',compact('countAllPimpinanOrmawa','perPage'));
    }

    public function getAllPimpinanOrmawa(){
        return DataTables::of(PimpinanOrmawa::with(['mahasiswa.prodi.jurusan'])
                                ->join('ormawa', 'ormawa.id', '=', 'pimpinan_ormawa.id_ormawa')
                                ->join('mahasiswa', 'mahasiswa.nim', '=', 'pimpinan_ormawa.nim')
                                ->select(['*']))
                ->editColumn("status_aktif", function ($data) {
                    return ucwords($data->status_aktif);
                })
                ->addColumn('aksi', function ($data) {
                    return $data->id;
                })
                ->make(true);
    }

    public function getLimitPimpinanOrmawa(){
        return DataTables::collection(PimpinanOrmawa::join('ormawa', 'ormawa.id', '=', 'pimpinan_ormawa.id_ormawa')
                                            ->join('mahasiswa', 'mahasiswa.nim', '=', 'pimpinan_ormawa.nim')
                                            ->get()
                                            ->take(5)
                                            ->sortByDesc('updated_at')
                                            ->load('mahasiswa.prodi.jurusan'))
                    ->editColumn("status_aktif", function ($data) {
                        return ucwords($data->status_aktif);
                    })
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->diffForHumans();
                    })
                    ->editColumn("updated_at", function ($data) {
                        return $data->updated_at->diffForHumans();
                    })
                    ->toJson();
    }

    public function show(PimpinanOrmawa $pimpinanOrmawa){
        $data = collect($pimpinanOrmawa->load(['mahasiswa.prodi.jurusan','ormawa']));

        $data->put('created_at',$pimpinanOrmawa->created_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('updated_at',$pimpinanOrmawa->updated_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('jabatan',ucwords($pimpinanOrmawa->jabatan));
        
        return $data->toJson();
    }

    public function create()
    {
        $ormawaList = Ormawa::pluck('nama','id')->toArray();
        $mahasiswaList = $this->generateMahasiswa();
        return view('user.'.$this->segmentUser.'.tambah_pimpinan_ormawa',compact('mahasiswaList','ormawaList'));
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
