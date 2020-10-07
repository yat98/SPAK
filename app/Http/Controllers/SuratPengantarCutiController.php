<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use DataTables;
use App\Operator;
use App\KodeSurat;
use App\WaktuCuti;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use App\SuratPengantarCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SuratPengantarCutiRequest;

class SuratPengantarCutiController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        
        $countAllSurat = SuratPengantarCuti::whereIn('status',['verifikasi kabag','selesai','menunggu tanda tangan'])
                                             ->count();
        
        $countAllVerifikasi = SuratPengantarCuti::where('status','verifikasi kasubag')
                                                  ->count();

        return view('user.'.$this->segmentUser.'.surat_pengantar_cuti',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllSurat = SuratPengantarCuti::count();
                                                                         
        return view($this->segmentUser.'.surat_pengantar_cuti',compact('perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = SuratPengantarCuti::where('status','verifikasi kabag')
                                                  ->count();
                                            
        $countAllSurat = SuratPengantarCuti::where('status','selesai')
                                             ->count();
        
        $countAllTandaTangan = SuratPengantarCuti::where('status','menunggu tanda tangan')
                                                   ->where('nip',Auth::user()->nip)
                                                   ->count();

        return view('user.'.$this->segmentUser.'.surat_pengantar_cuti',compact('perPage','countAllVerifikasi','countAllSurat','countAllTandaTangan'));
    }

    public function getAllPengajuan(){
        $suratCuti = SuratPengantarCuti::join('waktu_cuti','waktu_cuti.id','=','surat_pengantar_cuti.id_waktu_cuti')
                            ->join('tahun_akademik','waktu_cuti.id_tahun_akademik','=','tahun_akademik.id')
                            ->select(['surat_pengantar_cuti.*','tahun_akademik.semester','tahun_akademik.tahun_akademik'])
                            ->with(['waktuCuti.tahunAkademik','user','kodeSurat']);
                                                    
        if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
            $suratCuti = $suratCuti->where('status','verifikasi kasubag');
        }else if(Auth::user()->jabatan == 'kabag tata usaha'){
            $suratCuti = $suratCuti->where('status','verifikasi kabag');
        }

        return DataTables::of($suratCuti)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn('semester', function ($data) {
                            return ucwords($data->waktuCuti->tahunAkademik->semester);
                        })
                        ->editColumn("tahun_akademik", function ($data) {
                            return $data->waktuCuti->tahunAkademik->toArray();
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
    }

    public function getAllSurat(){
        $suratCuti = SuratPengantarCuti::join('waktu_cuti','waktu_cuti.id','=','surat_pengantar_cuti.id_waktu_cuti')
                            ->join('tahun_akademik','waktu_cuti.id_tahun_akademik','=','tahun_akademik.id')
                            ->select(['surat_pengantar_cuti.*','tahun_akademik.semester','tahun_akademik.tahun_akademik'])
                            ->with(['waktuCuti.tahunAkademik','user','kodeSurat']);

        if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratCuti = $suratCuti->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratCuti = $suratCuti->where('status','selesai');
            }
        }

        return DataTables::of($suratCuti)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn('semester', function ($data) {
                            return ucwords($data->waktuCuti->tahunAkademik->semester);
                        })
                        ->editColumn("tahun_akademik", function ($data) {
                            return $data->waktuCuti->tahunAkademik->toArray();
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
        $suratCuti =  SuratPengantarCuti::join('waktu_cuti','waktu_cuti.id','=','surat_pengantar_cuti.id_waktu_cuti')
                                          ->join('tahun_akademik','waktu_cuti.id_tahun_akademik','=','tahun_akademik.id')
                                          ->where('surat_pengantar_cuti.nip',Auth::user()->nip)
                                          ->select(['surat_pengantar_cuti.*','tahun_akademik.semester','tahun_akademik.tahun_akademik'])
                                          ->with(['waktuCuti.tahunAkademik','user','kodeSurat']);
                                    
        return DataTables::of($suratCuti)
                    ->addColumn('aksi', function ($data) {
                        return $data->id;
                    })
                    ->addColumn('semester', function ($data) {
                        return ucwords($data->waktuCuti->tahunAkademik->semester);
                    })
                    ->editColumn("tahun_akademik", function ($data) {
                        return $data->waktuCuti->tahunAkademik->toArray();
                    })
                    ->editColumn("status", function ($data) {
                        return ucwords($data->status);
                    })
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                    })
                    ->make(true);                        
    }

    public function create()
    {
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-pengantar-cuti');
        }
        $waktuCuti = $this->generateWaktuCuti();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        if(isset(Auth::user()->id)) return view($this->segmentUser.'.tambah_surat_pengantar_cuti',compact('userList','nomorSuratBaru','kodeSurat','waktuCuti'));
        return view('user.'.$this->segmentUser.'.tambah_surat_pengantar_cuti',compact('userList','nomorSuratBaru','kodeSurat','waktuCuti'));
    }
    
    public function store(SuratPengantarCutiRequest $request)
    {
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag kemahasiswaan')
                      ->first();

        DB::beginTransaction();
        try{
            SuratPengantarCuti::create($input);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Pengantar Cuti',
                'isi_notifikasi'=>'Verifikasi surat pengantar cuti',
                'link_notifikasi'=>url('pegawai/surat-pengantar-cuti')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat pengantar cuti gagal ditambahkan.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat pengantar cuti berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-pengantar-cuti');
    }

    public function show(SuratPengantarCuti $suratCuti)
    {
        $surat = collect($suratCuti->load(['waktuCuti.pendaftaranCuti.mahasiswa.prodi.jurusan','waktuCuti.tahunAkademik','user','operator','kodeSurat']));
        $surat->put('dibuat',$suratCuti->created_at->isoFormat('D MMMM YYYY HH:mm:ss'));
        $surat->put('semester',ucwords($suratCuti->waktuCuti->tahunAkademik->semester));
        $surat->put('status',ucwords($suratCuti->status));
        $surat->put('tahun',$suratCuti->created_at->isoFormat('Y'));
        $surat->put('updated_at',$suratCuti->updated_at->isoFormat('D MMMM Y'));
        return $surat->toJson();
    }

    public function edit(SuratPengantarCuti $suratCuti)
    {
        $waktuCuti = $this->generateWaktuCuti();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        if(isset(Auth::user()->id)) return view($this->segmentUser.'.edit_surat_pengantar_cuti',compact('userList','kodeSurat','waktuCuti','suratCuti'));
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

    public function cetak(SuratPengantarCuti $suratCuti){
        $jumlahCetak = ++$suratCuti->jumlah_cetak;
        $suratCuti->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_pengantar_cuti',compact('suratCuti'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-cuti'.' - '.$suratCuti->created_at->format('dmY-Him').'.pdf');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $suratCuti = SuratPengantarCuti::findOrFail($request->id);
        $user = $suratCuti->user;

        $isiNotifikasi = 'Verifikasi surat pengantar cuti';

        if(Auth::user()->jabatan == 'kabag tata usaha' || $suratCuti->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat pengantar cuti';
        }

        DB::beginTransaction();
        try{
            $suratCuti->update([
                'status'=>$status,
            ]);
            
            if($status == 'verifikasi kabag'){
                $user = User::where('jabatan','kabag tata usaha')->where('status_aktif','aktif')->first();
            }

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Pengantar Cuti',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-pengantar-cuti')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat pengantar cuti gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat pengantar cuti berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-pengantar-cuti');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-pengantar-cuti');
        }
        
        $suratCuti = SuratPengantarCuti::findOrFail($request->id);
        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();  
        $suratCuti->update([
            'status'=>'selesai',
        ]);

        NotifikasiOperator::create([
            'id_operator'=>$operator->id,
            'judul_notifikasi'=>'Surat Pengantar Cuti',
            'isi_notifikasi'=>'Surat pengantar cuti telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-pengantar-cuti')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat pengantar cuti berhasil');
        return redirect($this->segmentUser.'/surat-pengantar-cuti');
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
