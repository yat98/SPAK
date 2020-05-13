<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use App\KodeSurat;
use App\WaktuCuti;
use App\SuratPengantarCuti;
use Illuminate\Http\Request;
use App\Http\Requests\SuratPengantarCutiRequest;

class SuratPengantarCutiController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $nomorSurat = $this->generateNomorSuratCuti();
        $suratCutiList = SuratPengantarCuti::orderByDesc('nomor_surat')->paginate($perPage);
        $countAllSuratCuti = SuratPengantarCuti::all()->count();
        $countSuratCuti = $suratCutiList->count();
        return view('user.'.$this->segmentUser.'.surat_pengantar_cuti',compact('perPage','suratCutiList','countAllSuratCuti','countSuratCuti','nomorSurat'));
    }

    public function create()
    {
        $waktuCuti = $this->generateWaktuCuti();
        $userList = $this->generateKasubagKemahasiswaan();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.tambah_surat_pengantar_cuti',compact('userList','nomorSuratBaru','kodeSurat','waktuCuti'));
    }

    public function store(SuratPengantarCutiRequest $request)
    {
        $input = $request->all();
        SuratPengantarCuti::create($input);
        $this->setFlashData('success','Berhasil','Surat pengantar cuti berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-pengantar-cuti');
    }

    public function show(SuratPengantarCuti $suratCuti)
    {
        $surat = collect($suratCuti->load(['waktuCuti.pendaftaranCuti.mahasiswa.prodi.jurusan','user']));
        $kodeSurat = explode('/',$suratCuti->kodeSurat->kode_surat);
        $nomorSurat = $suratCuti->nomor_surat.'/'.$kodeSurat[0].'.4/'.$kodeSurat[1].'/'.$suratCuti->created_at->year;

        $surat->put('created_at',$suratCuti->created_at->isoFormat('D MMMM Y'));
        $surat->put('nomor_surat',$nomorSurat);
        $surat->put('updated_at',$suratCuti->updated_at->isoFormat('D MMMM Y'));
        return $surat->toJson();
    }

    public function edit(SuratPengantarCuti $suratCuti)
    {
        $waktuCuti = $this->generateWaktuCuti();
        $userList = $this->generateKasubagKemahasiswaan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.edit_surat_pengantar_cuti',compact('userList','kodeSurat','waktuCuti','suratCuti'));
    }

    public function update(SuratPengantarCutiRequest $request, SuratPengantarCuti $suratCuti)
    {
        $input = $request->all();
        $suratCuti->update($input);
        $this->setFlashData('success','Berhasil','Surat pengantar cuti berhasil diubah');
        return redirect($this->segmentUser.'/surat-pengantar-cuti');
    }

    public function destroy(SuratPengantarCuti $suratCuti)
    {
        $suratCuti->delete();
        $this->setFlashData('success','Berhasil','Surat pengantar cuti berhasil dihapus');
        return redirect($this->segmentUser.'/surat-pengantar-cuti');
    }

    public function cetakSuratCuti(SuratPengantarCuti $suratCuti){
        $jumlahCetak = ++$suratCuti->jumlah_cetak;
        $suratCuti->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_pengantar_cuti',compact('suratCuti'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-cuti'.' - '.$suratCuti->created_at->format('dmY-Him').'.pdf');
    }

    private function generateNomorSuratCuti(){
        $suratCutiList = SuratPengantarCuti::all();
        $nomorSuratList = [];
        foreach ($suratCutiList as $suratCuti) {
            $kodeSurat = explode('/',$suratCuti->kodeSurat->kode_surat);
            $nomorSuratList[$suratCuti->nomor_surat] = $suratCuti->nomor_surat.'/'.$kodeSurat[0].'.4/'.$kodeSurat[1].'/'.$suratCuti->created_at->year;
        }
        return $nomorSuratList;
    }

    private function generateKasubagKemahasiswaan(){
        $user = [];
        $kasubagList = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->get();
        foreach ($kasubagList as $kasubag) {
            $user[$kasubag->nip] = strtoupper($kasubag->jabatan).' - '.$kasubag->nama;
        }
        return $user;
    }

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat pengantar cuti')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generateWaktuCuti(){
        $waktuCuti = WaktuCuti::all();
        $waktuCutiList = [];
        foreach($waktuCuti as $value){
            $waktuCutiList[$value->id] = $value->tahunAkademik->tahun_akademik.' - '.ucwords($value->tahunAkademik->semester);
        }
        return $waktuCutiList;
    }
}
