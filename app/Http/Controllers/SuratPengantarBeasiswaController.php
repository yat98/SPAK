<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use App\KodeSurat;
use App\SuratMasuk;
use App\NotifikasiUser;
use Illuminate\Http\Request;
use App\SuratPengantarBeasiswa;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SuratPengantarBeasiswaRequest;

class SuratPengantarBeasiswaController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $nomorSurat = $this->generateNomorSuratBeasiswa(['menunggu tanda tangan','selesai']);
        $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->paginate($perPage);
        $countAllSuratBeasiswa = SuratPengantarBeasiswa::all()->count();
        $countSuratBeasiswa = $suratBeasiswaList->count();
        return view('user.'.$this->segmentUser.'.surat_pengantar_beasiswa',compact('perPage','suratBeasiswaList','countAllSuratBeasiswa','countSuratBeasiswa','nomorSurat'));
    }

    public function suratBeasiswaPimpinan(){
        $perPage = $this->perPage;
        $nomorSurat = $this->generateNomorSuratBeasiswa(['selesai']);
        $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->where('status','selesai')->paginate($perPage);
        $pengajuanBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->where('status','menunggu tanda tangan')->paginate($perPage);
        $countAllSuratBeasiswa = SuratPengantarBeasiswa::all()->count();
        $countAllPengajuanBeasiswa = SuratPengantarBeasiswa::where('status','menunggu tanda tangan')->count();
        $countSuratBeasiswa = $suratBeasiswaList->count();
        $countPengajuanBeasiswa = $pengajuanBeasiswaList->count();
        return view('user.'.$this->segmentUser.'.surat_pengantar_beasiswa',compact('perPage','nomorSurat','suratBeasiswaList','pengajuanBeasiswaList','countAllSuratBeasiswa','countAllPengajuanBeasiswa','countSuratBeasiswa','countPengajuanBeasiswa'));
    }

    public function create(){
        if(!$this->isKodeSuratBeasiswaExists() || !$this->isKodeSuratExists() || !$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-dispensasi');
        }
        $mahasiswa = $this->generateMahasiswa();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        $kodeSurat = $this->generateKodeSurat();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList = $this->generatePimpinan();
        return view('user.'.$this->segmentUser.'.tambah_surat_pengantar_beasiswa',compact('mahasiswa','suratMasuk','nomorSuratBaru','kodeSurat','userList'));
    }

    public function show(SuratPengantarBeasiswa $suratBeasiswa){
        $surat = collect($suratBeasiswa->load(['kodeSurat','user','suratMasuk','mahasiswa.prodi.jurusan','kasubag']));
        if($suratBeasiswa->user->jabatan == 'wd3'){
            $kode = explode('/',$suratBeasiswa->kodeSurat->kode_surat);
            $nomorSurat = 'B/'.$suratBeasiswa->nomor_surat.'/'.$kode[0].'.3/'.$kode[1].'/'.$suratBeasiswa->created_at->format('Y');
        }else{
            $nomorSurat = 'B/'.$suratBeasiswa->nomor_surat.'/'.$suratBeasiswa->kodeSurat->kode_surat.'/'.$suratBeasiswa->created_at->format('Y');
        }
        $surat->put('nama_file',explode('.',$suratBeasiswa->suratMasuk->file_surat_masuk)[0]);
        $surat->put('link_file',asset('upload_surat_masuk/'.$suratBeasiswa->suratMasuk->file_surat_masuk));
        $surat->put('created_at',$suratBeasiswa->created_at->isoFormat('D MMMM Y'));
        $surat->put('updated_at',$suratBeasiswa->updated_at->isoFormat('D MMMM Y'));
        $surat = $surat->put('nomor_surat',$nomorSurat);
        return $surat->toJson();
    }

    public function search(Request $request){
        $input = $request->all();
        if(isset($input['keywords'])){
            $nomor = $input['keywords'] ?? ' ';
            $perPage = $this->perPage;
            $nomorSurat = $this->generateNomorSuratBeasiswa(['menunggu tanda tangan','selesai']);
            $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->where('nomor_surat','like',"%$nomor%")->paginate($perPage);
            $countAllSuratBeasiswa = SuratPengantarBeasiswa::all()->count();
            $countSuratBeasiswa = $suratBeasiswaList->count();
            return view('user.'.$this->segmentUser.'.surat_pengantar_beasiswa',compact('perPage','suratBeasiswaList','countAllSuratBeasiswa','countSuratBeasiswa','nomorSurat'));
        }else{
            return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
        }
    }

    public function searchPimpinan(Request $request){
        $input = $request->all();
        if(isset($input['keywords'])){
            $nomor = $input['keywords'] ?? ' ';
            $perPage = $this->perPage;
            $nomorSurat = $this->generateNomorSuratBeasiswa(['selesai']);
            $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->where('nomor_surat','like',"%$nomor%")->where('status','selesai')->paginate($perPage);
            $pengajuanBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->where('status','menunggu tanda tangan')->paginate($perPage);
            $countAllSuratBeasiswa = SuratPengantarBeasiswa::all()->count();
            $countAllPengajuanBeasiswa = SuratPengantarBeasiswa::where('status','menunggu tanda tangan')->count();
            $countSuratBeasiswa = $suratBeasiswaList->count();
            $countPengajuanBeasiswa = $pengajuanBeasiswaList->count();
            return view('user.'.$this->segmentUser.'.surat_pengantar_beasiswa',compact('perPage','nomorSurat','suratBeasiswaList','pengajuanBeasiswaList','countAllSuratBeasiswa','countAllPengajuanBeasiswa','countSuratBeasiswa','countPengajuanBeasiswa'));
        }else{
            return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
        }
    }

    public function store(SuratPengantarBeasiswaRequest $request){
        $input = $request->all();
        $input['nip_kasubag'] = Session::get('nip');
        DB::beginTransaction();
        try{
            $suratBeasiswa = SuratPengantarBeasiswa::create($input);
            if($request->nip != Session::get('nip')){
                NotifikasiUser::create([
                    'nip'=>$request->nip,
                    'judul_notifikasi'=>'Surat Pengantar Beasiswa',
                    'isi_notifikasi'=>'Tanda tangan surat pengantar beasiswa.',
                    'link_notifikasi'=>url('pimpinan/surat-pengantar-beasiswa')
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat pengantar beasiswa gagal ditambahkan.');
        }

        try{
             $suratBeasiswa->mahasiswa()->attach($request->nim);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat pengantar beasiswa gagal ditambahkan.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat pengantar beasiswa berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
    }

    public function edit(SuratPengantarBeasiswa $suratBeasiswa)
    {
        $mahasiswa = $this->generateMahasiswa();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        $kodeSurat = $this->generateKodeSurat();
        $userList = $this->generatePimpinan();
        return view('user.'.$this->segmentUser.'.edit_surat_pengantar_beasiswa',compact('mahasiswa','suratMasuk','kodeSurat','userList','suratBeasiswa'));
    }

    public function update(SuratPengantarBeasiswaRequest $request, SuratPengantarBeasiswa $suratBeasiswa)
    {
        $input = $request->all();
        DB::beginTransaction();
        try{
            $suratBeasiswa->update($input);
            $suratBeasiswa->mahasiswa()->sync($request->nim);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat pengantar beasiswa gagal diubah.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat pengantar beasiswa berhasil diubah');
        return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
    }

    public function destroy(SuratPengantarBeasiswa $suratBeasiswa)
    {
        $suratBeasiswa->delete();
        $this->setFlashData('success','Berhasil','Surat pengantar beasiswa berhasil dihapus');
        return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
    }

    public function tandaTanganBeasiswa(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
        }
        $suratBeasiswa = SuratPengantarBeasiswa::findOrFail($request->id);
        $suratBeasiswa->update([
            'status'=>'selesai',
        ]);
        NotifikasiUser::create([
            'nip'=>$suratBeasiswa->nip_kasubag,
            'judul_notifikasi'=>'Surat Pengantar Beasiswa',
            'isi_notifikasi'=>'Surat pengantar beasiswa telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-pengantar-beasiswa')
        ]);
        $this->setFlashData('success','Berhasil','Tanda tangan surat pengantar beasiswa berhasil');
        return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
    }

    public function cetakSuratBeasiswa(SuratPengantarBeasiswa $suratBeasiswa){
        $jumlahCetak = ++$suratBeasiswa->jumlah_cetak;
        $suratBeasiswa->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_pengantar_beasiswa',compact('suratBeasiswa'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-pengantar-beasiswa'.' - '.$suratBeasiswa->created_at->format('dmY-Him').'.pdf');
    }

    private function generateKodeSurat()
    {
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat pengantar beasiswa')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generateNomorSuratBeasiswa($status){
        $suratBeasiswaList = SuratPengantarBeasiswa::whereIn('status',$status)->get();
        $nomorSuratList = [];
        foreach ($suratBeasiswaList as $suratBeasiswa) {
            if($suratBeasiswa->user->jabatan == 'dekan'){
                $nomorSuratList[$suratBeasiswa->nomor_surat] = 'B/'.$suratBeasiswa->nomor_surat.'/'.$suratBeasiswa->kodeSurat->kode_surat.'/'.$suratBeasiswa->created_at->year;
            }else{
                $kode = explode('/',$suratBeasiswa->kodeSurat->kode_surat);
                $nomorSuratList[$suratBeasiswa->nomor_surat] = 'B/'.$suratBeasiswa->nomor_surat.'/'.$kode[0].'.3/'.$kode[1].'/'.$suratBeasiswa->created_at->year;
            }
        }
        return $nomorSuratList;
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinanList = User::whereIn('jabatan',['dekan','wd3'])->where('status_aktif','aktif')->get();
        foreach ($pimpinanList as $pimpinan) {
            $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        }
        return $user;
    }

    private function isKodeSuratBeasiswaExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat pengantar beasiswa')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }
}
