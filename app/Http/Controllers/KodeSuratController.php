<?php

namespace App\Http\Controllers;

use App\KodeSurat;
use Illuminate\Http\Request;
use App\Http\Requests\KodeSuratRequest;

class KodeSuratController extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $jenisSurat = $this->getJenisSurat();
        $perPage=$this->perPage;
        $kodeSuratList = KodeSurat::paginate($perPage);
        $countKodeSurat = KodeSurat::all()->count();
        $countAllKodeSurat = $countKodeSurat;
        return view('user.'.$this->segmentUser.'.kode_surat',compact('kodeSuratList','countKodeSurat','countAllKodeSurat','perPage','jenisSurat'));
    }

    public function create()
    {
        $jenisSurat = $this->getJenisSurat();
        return view('user.'.$this->segmentUser.'.tambah_kode_surat',compact('jenisSurat'));
    }

    public function store(KodeSuratRequest $request)
    {
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data kode surat '.strtolower($input['jenis_surat'].' dengan kode ').$input['kode_surat'].' berhasil ditambahkan');
        KodeSurat::create($input);
        return redirect($this->segmentUser.'/kode-surat');
    }

    public function edit(KodeSurat $kodeSurat){
        $jenisSurat = $this->getJenisSurat();
        return view('user.'.$this->segmentUser.'.edit_kode_surat',compact('jenisSurat','kodeSurat'));
    }

    public function update(KodeSuratRequest $request,KodeSurat $kodeSurat){
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data kode surat '.strtolower($kodeSurat->jenis_surat.' dengan kode ').$kodeSurat->kode_surat.' berhasil ubah');
        $kodeSurat->update($input);
        return redirect($this->segmentUser.'/kode-surat');
    }

    public function destroy(KodeSurat $kodeSurat){
        $this->setFlashData('success','Berhasil','Data kode surat '.strtolower($kodeSurat->jenis_surat.' dengan kode ').$kodeSurat->kode_surat.' berhasil dihapus');
        $kodeSurat->delete();
        return redirect($this->segmentUser.'/kode-surat');
    }

    public function search(Request $request){
        $keywords = $request->all();
        if(isset($keywords['keyword']) || isset($keywords['jenis_surat'])){
            $jenisSurat = $this->getJenisSurat();
            $countAllKodeSurat = KodeSurat::all()->count();
            $perPage=$this->perPage;
            $kodeSurat = isset($keywords['keyword']) ? $keywords['keyword']:'';
            $kodeSuratList = KodeSurat::where('kode_surat','like',"%$kodeSurat%");
            (isset($keywords['jenis_surat'])) ? $kodeSuratList = $kodeSuratList->where('jenis_surat',$keywords['jenis_surat']) : '';
            $kodeSuratList = $kodeSuratList->paginate($perPage)->appends($request->except('page'));
            $countKodeSurat = count($kodeSuratList);
            if($countKodeSurat < 1){
                $this->setFlashData('search','Hasil Pencarian','Data kode surat tidak ditemukan!');
            }
            
            return view('user.'.$this->segmentUser.'.kode_surat',compact('kodeSuratList','countKodeSurat','countAllKodeSurat','perPage','jenisSurat'));
        }else{
            return redirect($this->segmentUser.'/kode-surat');
        }
    }

    private function getJenisSurat (){
        return [
            'surat keterangan'=>'Surat Keterangan',
            'surat dispensasi'=>'Surat Dispensasi',
            'surat pengantar cuti'=>'Surat Pengantar Cuti',
            'surat rekomendasi'=>'Surat Rekomendasi',
            'surat persetujuan pindah'=>'Surat Persetujuan Pindah',
        ];
    }
}
