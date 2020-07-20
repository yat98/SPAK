<?php

namespace App\Http\Controllers;

use Session;
use DataTables;
use App\KodeSurat;
use Illuminate\Http\Request;
use App\Http\Requests\KodeSuratRequest;

class KodeSuratController extends Controller
{
    public function index()
    {
        $perPage=$this->perPage;
        $countAllKodeSurat = KodeSurat::count();
        return view('user.'.$this->segmentUser.'.kode_surat',compact('countAllKodeSurat','perPage'));
    }

    public function getAllKodeSurat(){
        return DataTables::of(KodeSurat::select(['*']))
                ->editColumn("status_aktif", function ($data) {
                    return ucwords($data->status_aktif);
                })
                ->addColumn('aksi', function ($data) {
                    return $data->id;
                })
                ->make(true);
    }

    public function getLimitKodeSurat(){
        return DataTables::collection(KodeSurat::all()->take(5)->sortByDesc('updated_at'))
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

    public function show(KodeSurat $kodeSurat){
        $data = collect($kodeSurat);
        $data->put('created_at',$kodeSurat->created_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('updated_at',$kodeSurat->updated_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('status_aktif',ucwords($kodeSurat->status_aktif));

        return $data->toJson();
    }

    public function create()
    {
        return view('user.'.$this->segmentUser.'.tambah_kode_surat');
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

    private function getJenisSurat (){
        if(Session::get('jabatan') == 'kasubag kemahasiswaan'){
            $kodeSurat = [
                'surat keterangan'=>'Surat Keterangan',
                'surat dispensasi'=>'Surat Dispensasi',
                'surat pengantar cuti'=>'Surat Pengantar Cuti',
                'surat rekomendasi'=>'Surat Rekomendasi',
                'surat persetujuan pindah'=>'Surat Persetujuan Pindah',
                'surat tugas'=>'Surat Tugas',
                'surat pengantar beasiswa'=>'Surat Pengantar Beasiswa',
                'surat kegiatan mahasiswa'=>'Surat Kegiatan Mahasiswa'
            ];
        }else if(Session::get('jabatan') == 'kasubag pendidikan dan pengajaran'){
            $kodeSurat = [
                'surat keterangan lulus'=>'Surat Keterangan Lulus',
                'surat permohonan pengambilan material'=>'Surat Permohonan Pengambilan Material',
                'surat permohonan survei'=>'Surat Permohonan Survei',
                'surat rekomendasi penelitian'=>'Surat Rekomendasi Penelitian',
                'surat permohonan pengambilan data awal'=>'Surat Permohonan Pengambilan Data Awal',
            ];
        }else{
            $kodeSurat = [
                'surat keterangan bebas perpustakaan'=>'Surat Keterangan Bebas Perpustakaan',
                'surat keterangan bebas perlengkapan'=>'Surat Keterangan Bebas Perlengkapan',
            ];
        }
        return $kodeSurat;
    }
}
