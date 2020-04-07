<?php

namespace App\Http\Controllers;

use Validator;
use App\TahunAkademik;
use Illuminate\Http\Request;
use App\Http\Requests\TahunAkademikRequest;

class TahunAkademikController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $perPage = $this->perPage;
        $tahunAkademikList = TahunAkademik::orderBy('tahun_akademik','DESC')
                                    ->orderBy('created_at','DESC')
                                    ->orderBy('status_aktif')
                                    ->paginate($this->perPage);
        $tahun = $this->generateTahunAkademik();
        $countTahunAkademik = $tahunAkademikList->count();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        return view('user.'.$this->segmentUser.'.tahun_akademik',compact('tahunAkademikList','countTahunAkademik','tahunAkademikAktif','tahun','perPage'));
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
