<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use App\Operator;
use DataTables;
use App\KodeSurat;
use App\SuratMasuk;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use App\SuratPengantarBeasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SuratPengantarBeasiswaRequest;

class SuratPengantarBeasiswaController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        
        $countAllSurat = SuratPengantarBeasiswa::whereIn('status',['verifikasi kabag','selesai','menunggu tanda tangan'])
                                             ->count();
        
        $countAllVerifikasi = SuratPengantarBeasiswa::where('status','verifikasi kasubag')
                                                  ->count();

        return view('user.'.$this->segmentUser.'.surat_pengantar_beasiswa',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllSurat = SuratPengantarBeasiswa::count();
                                                                         
        return view($this->segmentUser.'.surat_pengantar_beasiswa',compact('perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = SuratPengantarBeasiswa::where('status','verifikasi kabag')
                                                  ->count();
                                            
        $countAllSurat = SuratPengantarBeasiswa::where('status','selesai')
                                             ->count();
        
        $countAllTandaTangan = SuratPengantarBeasiswa::where('status','menunggu tanda tangan')
                                                   ->where('nip',Auth::user()->nip)
                                                   ->count();

        return view('user.'.$this->segmentUser.'.surat_pengantar_beasiswa',compact('perPage','countAllVerifikasi','countAllSurat','countAllTandaTangan'));
    }

    public function getAllSurat(){
        $suratBeasiswa = SuratPengantarBeasiswa::with(['mahasiswa','user','kodeSurat','operator']);

        if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratBeasiswa = $suratBeasiswa->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratBeasiswa = $suratBeasiswa->where('status','selesai');
            }
        }

        return DataTables::of($suratBeasiswa)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
    }

    public function getAllPengajuan(){
        $suratBeasiswa = SuratPengantarBeasiswa::with(['mahasiswa','user','kodeSurat','operator']);
                                                    
        if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
            $suratBeasiswa = $suratBeasiswa->where('status','verifikasi kasubag');
        }else if(Auth::user()->jabatan == 'kabag tata usaha'){
            $suratBeasiswa = $suratBeasiswa->where('status','verifikasi kabag');
        }

        return DataTables::of($suratBeasiswa)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
    }

    public function getAllTandaTangan(){
        $suratBeasiswa =  SuratPengantarBeasiswa::with(['mahasiswa','user','kodeSurat','operator'])
                                                  ->where('nip',Auth::user()->nip);
                                    
        return DataTables::of($suratBeasiswa)
                    ->addColumn('aksi', function ($data) {
                        return $data->id;
                    })
                    ->editColumn("status", function ($data) {
                        return ucwords($data->status);
                    })
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                    })
                    ->make(true);                        
    }

    public function create(){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-pengantar-cuti');
        }
        $mahasiswa = $this->generateMahasiswa();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        if(isset(Auth::user()->id)) return view($this->segmentUser.'.tambah_surat_pengantar_beasiswa',compact('mahasiswa','suratMasuk','nomorSuratBaru','kodeSurat','userList'));
        return view('user.'.$this->segmentUser.'.tambah_surat_pengantar_beasiswa',compact('mahasiswa','suratMasuk','nomorSuratBaru','kodeSurat','userList'));
    }

    public function show(SuratPengantarBeasiswa $suratBeasiswa){
        $surat = collect($suratBeasiswa->load(['kodeSurat','user','suratMasuk','mahasiswa.prodi.jurusan','operator']));
        $surat->put('dibuat',$suratBeasiswa->created_at->isoFormat('D MMMM YYYY HH:mm:ss'));
        $surat->put('status',ucwords($suratBeasiswa->status));
        $surat->put('tahun',$suratBeasiswa->created_at->isoFormat('Y'));
        $surat->put('nama_file',explode('.',$suratBeasiswa->suratMasuk->file_surat_masuk)[0]);
        $surat->put('link_file',asset('upload_surat_masuk/'.$suratBeasiswa->suratMasuk->file_surat_masuk));
        return $surat->toJson();
    }

    public function store(SuratPengantarBeasiswaRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;
        $user = User::where('status_aktif','aktif')
        ->where('jabatan','kasubag kemahasiswaan')
        ->first();
        
        DB::beginTransaction();
        try{
            $suratBeasiswa = SuratPengantarBeasiswa::create($input);
            $suratBeasiswa->mahasiswa()->attach($request->nim);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Pengantar Beasiswa',
                'isi_notifikasi'=>'Verifikasi surat pengantar beasiswa',
                'link_notifikasi'=>url('pegawai/surat-pengantar-beasiswa')
            ]);
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
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        $userList =$this->generateTandaTanganKemahasiswaan();
        if(isset(Auth::user()->id)) return view($this->segmentUser.'.edit_surat_pengantar_beasiswa',compact('mahasiswa','suratMasuk','kodeSurat','userList','suratBeasiswa'));
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

    public function verification(Request $request){
        $status='verifikasi kabag';
        $suratBeasiswa = SuratPengantarBeasiswa::findOrFail($request->id);
        $user = $suratBeasiswa->user;

        $isiNotifikasi = 'Verifikasi surat pengantar beasiswa';

        if(Auth::user()->jabatan == 'kabag tata usaha' || $suratBeasiswa->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat pengantar beasiswa';
        }

        DB::beginTransaction();
        try{
            $suratBeasiswa->update([
                'status'=>$status,
            ]);
            
            if($status == 'verifikasi kabag'){
                $user = User::where('jabatan','kabag tata usaha')->where('status_aktif','aktif')->first();
            }

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Pengantar Beasiswa',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-pengantar-beasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat pengantar beasiswa gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat pengantar beasiswa berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
        }
        $suratBeasiswa = SuratPengantarBeasiswa::findOrFail($request->id);
        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();  
        $suratBeasiswa->update([
            'status'=>'selesai',
        ]);

        NotifikasiOperator::create([
            'id_operator'=>$operator->id,
            'judul_notifikasi'=>'Surat Pengantar Beasiswa',
            'isi_notifikasi'=>'Surat pengantar beasiswa telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-pengantar-beasiswa')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat pengantar beasiswa berhasil');
        return redirect($this->segmentUser.'/surat-pengantar-beasiswa');
    }

    public function cetak(SuratPengantarBeasiswa $suratBeasiswa){
        $jumlahCetak = ++$suratBeasiswa->jumlah_cetak;
        $suratBeasiswa->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_pengantar_beasiswa',compact('suratBeasiswa'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-pengantar-beasiswa'.' - '.$suratBeasiswa->created_at->format('dmY-Him').'.pdf');
    }
}
