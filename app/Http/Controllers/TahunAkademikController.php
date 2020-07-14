<?php

namespace App\Http\Controllers;

use Validator;
use App\TahunAkademik;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\TahunAkademikRequest;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $tahun = $this->generateTahunAkademik();
        $countAllTahunAkademik = TahunAkademik::count();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        return view('user.'.$this->segmentUser.'.tahun_akademik',compact('countAllTahunAkademik','tahunAkademikAktif','tahun','perPage'));
    }

    public function getAllTahunAkademik(){
        return DataTables::of(TahunAkademik::select(['*']))
                ->addColumn('aksi', function ($data) {
                    return $data->id;
                })
                ->editColumn("status_aktif", function ($data) {
                    return ucwords($data->status_aktif);
                })
                ->editColumn("semester", function ($data) {
                    return ucwords($data->semester);
                })
                ->make(true);
    }

    public function show(TahunAkademik $tahunAkademik){
        $data = collect($tahunAkademik);
        $data->put('created_at',$tahunAkademik->created_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('updated_at',$tahunAkademik->updated_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('semester',ucwords($tahunAkademik->semester));
        $data->put('status_aktif',ucwords($tahunAkademik->status_aktif));

        return $data->toJson();
    }

    public function create()
    {
        $tahun = $this->generateTahunAkademik();
        return view('user.'.$this->segmentUser.'.tambah_tahun_akademik',compact('tahun'));
    }

    public function search(Request $request){
        $keyword = $request->all();
        $perPage = $this->perPage;
        if(isset($keyword['tahun_akademik']) || isset($keyword['semester']) ||isset($keyword['status_aktif'])){
            $tahunAkademikList = TahunAkademik::orderBy('created_at','DESC');
            $tahun = $this->generateTahunAkademik();
            $countTahunAkademik = $tahunAkademikList->count();
            $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
            isset($keyword['tahun_akademik']) ? $tahunAkademikList = $tahunAkademikList->where('tahun_akademik',$keyword['tahun_akademik']):'';
            isset($keyword['semester']) ? $tahunAkademikList = $tahunAkademikList->where('semester',$keyword['semester']):'';
            isset($keyword['status_aktif']) ? $tahunAkademikList = $tahunAkademikList->where('status_aktif',$keyword['status_aktif']):'';
            $tahunAkademikList = $tahunAkademikList->paginate($perPage)->appends($request->except('page'));
            $countTahunAkademik = count($tahunAkademikList);
            if($countTahunAkademik < 1){
                $this->setFlashData('search','Hasil Pencarian','Data tahun akademik tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.tahun_akademik',compact('tahunAkademikList','countTahunAkademik','tahunAkademikAktif','tahun','perPage'));
        }else{
            return redirect($this->segmentUser.'/tahun-akademik');
        }
    }

    public function store(TahunAkademikRequest $request)
    {
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data tahun akademik '.strtolower($input['tahun_akademik'].' - '.$input['semester']).' berhasil ditambahkan');
        TahunAkademik::create($input);
        return redirect($this->segmentUser.'/tahun-akademik');
    }

    public function edit(TahunAkademik $tahunAkademik)
    {   
        $tahun = $this->generateTahunAkademik();
        return view('user.'.$this->segmentUser.'.edit_tahun_akademik',compact('tahun','tahunAkademik'));
    }

    public function update(TahunAkademikRequest $request, TahunAkademik $tahunAkademik)
    {  
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data tahun akademik '.strtolower($tahunAkademik->tahun_akademik.' - '.$tahunAkademik->semester.' berhasil diubah'));
        $tahunAkademik->update($input);
        return redirect($this->segmentUser.'/tahun-akademik');
    }

    public function destroy(TahunAkademik $tahunAkademik)
    {
        $this->setFlashData('success','Berhasil','Data tahun akademik '.strtolower($tahunAkademik->tahun_akademik.' - '.$tahunAkademik->semester).' berhasil dihapus');
        $tahunAkademik->delete();
        return redirect($this->segmentUser.'/tahun-akademik');
    }
}
