<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use Storage;
use App\User;
use DataTables;
use App\KodeSurat;
use App\SuratTugas;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use App\PengajuanSuratTugas;
use Illuminate\Http\Request;
use App\SuratPersetujuanPindah;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratPersetujuanPindah;
use App\Http\Requests\SuratPersetujuanPindahRequest;

class SuratPersetujuanPindahController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        
        $countAllSurat = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                                 ->whereIn('pengajuan_surat_persetujuan_pindah.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                 ->count();
        
        $countAllVerifikasi = PengajuanSuratPersetujuanPindah::where('status','verifikasi kasubag')
                                                               ->count();

        return view('user.'.$this->segmentUser.'.surat_persetujuan_pindah',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratPersetujuanPindah::where('status','verifikasi kabag')
                                                        ->count();
                                            
        $countAllSurat = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                          ->where('pengajuan_surat_persetujuan_pindah.status','selesai')
                                          ->count();
        
        $countAllTandaTangan = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                            ->where('pengajuan_surat_persetujuan_pindah.status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.'.$this->segmentUser.'.surat_persetujuan_pindah',compact('perPage','countAllVerifikasi','countAllSurat','countAllTandaTangan'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratPersetujuanPindah::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                                 ->whereNotIn('status',['diajukan'])
                                                 ->count();
             
        return view($this->segmentUser.'.surat_persetujuan_pindah',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function create()
    {
        if(!$this->isKodeSuratPindahExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-persetujuan-pindah');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.tambah_surat_persetujuan_pindah',compact('userList','kodeSurat','nomorSuratBaru','mahasiswa','userList'));
    }   

    public function getAllSurat(){
        $suratPindah = PengajuanSuratPersetujuanPindah::join('surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                    ->select('surat_persetujuan_pindah.nomor_surat','pengajuan_surat_persetujuan_pindah.*')
                                    ->with(['suratPersetujuanPindah.kodeSurat','mahasiswa']);

        if(isset(Auth::user()->id)){
            $suratPindah = $suratPindah->whereNotIn('pengajuan_surat_persetujuan_pindah.status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratPindah = $suratPindah->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratPindah = $suratPindah->where('status','selesai');
            }
        }

        return DataTables::of($suratPindah)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();
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
        $suratPindah =  PengajuanSuratPersetujuanPindah::join('surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                    ->where('pengajuan_surat_persetujuan_pindah.status','menunggu tanda tangan')
                                    ->where('surat_persetujuan_pindah.nip',Auth::user()->nip)
                                    ->select('surat_persetujuan_pindah.nomor_surat','pengajuan_surat_persetujuan_pindah.*')
                                    ->with(['mahasiswa','suratPersetujuanPindah.kodeSurat']);
                                    
        return DataTables::of($suratPindah)
                                    ->addColumn('aksi', function ($data) {
                                        return $data->id;
                                    })
                                      ->addColumn('waktu_pengajuan', function ($data) {
                                        return $data->created_at->diffForHumans();
                                    })
                                    ->editColumn("status", function ($data) {
                                        return ucwords($data->status);
                                    })
                                    ->editColumn("created_at", function ($data) {
                                        return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                                    })
                                    ->make(true);                            
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-persetujuan-pindah');
        }

        $suratPindah = SuratPersetujuanPindah::findOrFail($request->id);
        
        $suratPindah->pengajuanSuratPersetujuanPindah->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::create([
            'nim'=>$suratPindah->pengajuanSuratPersetujuanPindah->nim,
            'judul_notifikasi'=>'Surat Persetujuan Pindah',
            'isi_notifikasi'=>'Surat persetujuan pindah telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-persetujuan-pindah')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat persetujuan pindah berhasil');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function createSurat(PengajuanSuratPersetujuanPindah $pengajuanSuratPindah)
    {
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-persetujuan-pindah');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view($this->segmentUser.'.tambah_surat_persetujuan_pindah',compact('userList','kodeSurat','nomorSuratBaru','userList','pengajuanSuratPindah'));
    }

    public function store(SuratPersetujuanPindahRequest $request)
    {
        $input = $request->all();
        DB::beginTransaction();
        try{ 
            if($request->has('file_surat_keterangan_lulus_butuh')){
                $imageFieldName = 'file_surat_keterangan_lulus_butuh'; 
                $uploadPath = 'upload_persetujuan_pindah';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
            }
            if($request->has('file_ijazah_terakhir')){
                $imageFieldName = 'file_ijazah_terakhir'; 
                $uploadPath = 'upload_persetujuan_pindah';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
            }
            if($request->has('file_surat_rekomendasi_jurusan')){
                $imageFieldName = 'file_surat_rekomendasi_jurusan'; 
                $uploadPath = 'upload_persetujuan_pindah';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
            }
            if($request->has('file_surat_keterangan_bebas_perlengkapan_universitas')){
                $imageFieldName = 'file_surat_keterangan_bebas_perlengkapan_universitas'; 
                $uploadPath = 'upload_persetujuan_pindah';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
            }
            if($request->has('file_surat_keterangan_bebas_perlengkapan_fakultas')){
                $imageFieldName = 'file_surat_keterangan_bebas_perlengkapan_fakultas'; 
                $uploadPath = 'upload_persetujuan_pindah';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
            }
            if($request->has('file_surat_keterangan_bebas_perpustakaan_universitas')){
                $imageFieldName = 'file_surat_keterangan_bebas_perpustakaan_universitas'; 
                $uploadPath = 'upload_persetujuan_pindah';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
            }
            if($request->has('file_surat_keterangan_bebas_perpustakaan_fakultas')){
                $imageFieldName = 'file_surat_keterangan_bebas_perpustakaan_fakultas'; 
                $uploadPath = 'upload_persetujuan_pindah';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
            }
            $input['status'] = 'menunggu tanda tangan';
            $pengajuanSuratPersetujuanPindah = PengajuanSuratPersetujuanPindah::create($input);
            $input['id_pengajuan_persetujuan_pindah'] = $pengajuanSuratPersetujuanPindah->id;
            SuratPersetujuanPindah::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratPersetujuanPindah->nim,
                'judul_notifikasi'=>'Surat Persetujuan Pindah',
                'isi_notifikasi'=>'Surat persetujuan pindah telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/surat-persetujuan-pindah'),
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Persetujuan Pindah',
                'isi_notifikasi'=>'Tanda tangan surat persetujuan pindah.',
                'link_notifikasi'=>url('pimpinan/surat-persetujuan-pindah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Surat Persetujuan Pindah','Surat persetujuan pindah gagal ditambah.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat persetujuan pindah mahasiswa dengan nama '.$pengajuanSuratPersetujuanPindah->mahasiswa->nama.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function storeSurat(Request $request)
    {
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat|unique:surat_keterangan_bebas_perlengkapan,nomor_surat',
            'nip'=>'required',
        ]);

        $pengajuanSuratPindah = PengajuanSuratPersetujuanPindah::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag kemahasiswaan')
                      ->first();

        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSuratPindah->id;

        DB::beginTransaction();
        try{
            SuratPersetujuanPindah::create($input);

            $pengajuanSuratPindah->update([
                'status'=>'verifikasi kasubag',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Persetujuan Pindah',
                'isi_notifikasi'=>'Verifikasi surat persetujuan pindah mahasiswa dengan nama '.$pengajuanSuratPindah->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-persetujuan-pindah')
            ]);
           
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat Persetujuan Pindah gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat persetujuan pindah mahasiswa berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratPersetujuanPindah $pengajuanSuratPersetujuanPindah){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSuratPersetujuanPindah->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratPersetujuanPindah->nim,
                'judul_notifikasi'=>'Pengajuan Surat Persetujuan Pindah',
                'isi_notifikasi'=>'Pengajuan surat persetujuan pindah di tolak.',
                'link_notifikasi'=>url('mahasiswa/surat-persetujuan-pindah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat persetujuan pindah gagal ditolak.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Pengajuan surat persetujuan pindah mahasiswa dengan nama '.$pengajuanSuratPersetujuanPindah->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function show(SuratPersetujuanPindah $suratPersetujuanPindah)
    {
        $pengajuanSuratPindah =$suratPersetujuanPindah->pengajuanSuratPersetujuanPindah;
        $pengajuan = collect($suratPersetujuanPindah->load(['pengajuanSuratPersetujuanPindah.mahasiswa','kodeSurat','user','pengajuanSuratPersetujuanPindah.operator']));
        $pengajuan->put('status',ucwords($pengajuanSuratPindah->status));
        $pengajuan->put('tahun',$pengajuanSuratPindah->suratPersetujuanPindah->created_at->isoFormat('Y'));
        $pengajuan->put('dibuat',$pengajuanSuratPindah->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $pengajuan->put('file_surat_keterangan_lulus_butuh',asset('upload_persetujuan_pindah/'.$pengajuanSuratPindah->file_surat_keterangan_lulus_butuh));
        $pengajuan->put('file_ijazah_terakhir',asset('upload_persetujuan_pindah/'.$pengajuanSuratPindah->file_ijazah_terakhir));
        $pengajuan->put('file_surat_rekomendasi_jurusan',asset('upload_persetujuan_pindah/'.$pengajuanSuratPindah->file_surat_rekomendasi_jurusan));
        $pengajuan->put('file_surat_keterangan_bebas_perlengkapan_universitas',asset('upload_persetujuan_pindah/'.$pengajuanSuratPindah->file_surat_keterangan_bebas_perlengkapan_universitas));
        $pengajuan->put('file_surat_keterangan_bebas_perlengkapan_fakultas',asset('upload_persetujuan_pindah/'.$pengajuanSuratPindah->file_surat_keterangan_bebas_perlengkapan_fakultas));
        $pengajuan->put('file_surat_keterangan_bebas_perpustakaan_universitas',asset('upload_persetujuan_pindah/'.$pengajuanSuratPindah->file_surat_keterangan_bebas_perpustakaan_universitas));
        $pengajuan->put('file_surat_keterangan_bebas_perpustakaan_fakultas',asset('upload_persetujuan_pindah/'.$pengajuanSuratPindah->file_surat_keterangan_bebas_perpustakaan_fakultas));
        $pengajuan->put('nama_file_surat_keterangan_lulus_butuh',explode('.',$pengajuanSuratPindah->file_surat_keterangan_lulus_butuh)[0]);
        $pengajuan->put('nama_file_ijazah_terakhir',explode('.',$pengajuanSuratPindah->file_ijazah_terakhir)[0]);
        $pengajuan->put('nama_file_surat_rekomendasi_jurusan',explode('.',$pengajuanSuratPindah->file_surat_rekomendasi_jurusan)[0]);
        $pengajuan->put('nama_file_surat_keterangan_bebas_perlengkapan_universitas',explode('.',$pengajuanSuratPindah->file_surat_keterangan_bebas_perlengkapan_universitas)[0]);
        $pengajuan->put('nama_file_surat_keterangan_bebas_perlengkapan_fakultas',explode('.',$pengajuanSuratPindah->file_surat_keterangan_bebas_perlengkapan_fakultas)[0]);
        $pengajuan->put('nama_file_surat_keterangan_bebas_perpustakaan_universitas',explode('.',$pengajuanSuratPindah->file_surat_keterangan_bebas_perpustakaan_universitas)[0]);
        $pengajuan->put('nama_file_surat_keterangan_bebas_perpustakaan_fakultas',explode('.',$pengajuanSuratPindah->file_surat_keterangan_bebas_perpustakaan_fakultas)[0]);
        return $pengajuan->toJson();
    }

    public function edit(SuratPersetujuanPindah $suratPersetujuanPindah)
    {
        $mahasiswa = $this->generateMahasiswa();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.edit_surat_persetujuan_pindah',compact('userList','kodeSurat','mahasiswa','userList','suratPersetujuanPindah'));
    }

    public function update(SuratPersetujuanPindahRequest $request, SuratPersetujuanPindah $suratPersetujuanPindah)
    {
        $input = $request->all();
        if($request->has('file_surat_keterangan_lulus_butuh')){
            $imageFieldName = 'file_surat_keterangan_lulus_butuh'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_lulus_butuh);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_ijazah_terakhir')){
            $imageFieldName = 'file_ijazah_terakhir'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_ijazah_terakhir);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_rekomendasi_jurusan')){
            $imageFieldName = 'file_surat_rekomendasi_jurusan'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_keterangan_bebas_perlengkapan_universitas')){
            $imageFieldName = 'file_surat_keterangan_bebas_perlengkapan_universitas'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_universitas);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_keterangan_bebas_perlengkapan_fakultas')){
            $imageFieldName = 'file_surat_keterangan_bebas_perlengkapan_fakultas'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_fakultas);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_keterangan_bebas_perpustakaan_universitas')){
            $imageFieldName = 'file_surat_keterangan_bebas_perpustakaan_universitas'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_universitas);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_keterangan_bebas_perpustakaan_fakultas')){
            $imageFieldName = 'file_surat_keterangan_bebas_perpustakaan_fakultas'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_fakultas);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->update($input);
        $suratPersetujuanPindah->update($input);
        $this->setFlashData('success','Berhasil','Surat persetujuan pindah mahasiswa dengan nama '.$suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nama.' berhasil diubah');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function progress(PengajuanSuratPersetujuanPindah $pengajuanSuratPindah){
        $data = collect($pengajuanSuratPindah);
        $data->put('status',ucwords($pengajuanSuratPindah->status));

        if($pengajuanSuratPindah->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratPindah->suratPersetujuanPindah->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalSelesai);
        }else if($pengajuanSuratPindah->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratPindah->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalDitolak);
        }else{
            $tanggal = $pengajuanSuratPindah->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggal);
        }

        return $data->toJson();
    }

    public function cetak(SuratPersetujuanPindah $suratPersetujuanPindah){
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->nim){
                abort(404);
            }

            if($suratPersetujuanPindah->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat persetujuan pindah sebanyak 3 kali.');
                return redirect('mahasiswa/surat-persetujuan-pindah');
            }
        }

        $data = $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->nim.' - '.$suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nama.' - '.$suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",5,5);

        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratPersetujuanPindah->jumlah_cetak;
                $suratPersetujuanPindah->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }
        }

        $pdf = PDF::loadview('surat.surat_persetujuan_pindah',compact('suratPersetujuanPindah','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-persetujuan-pindah'.' - '.$suratPersetujuanPindah->created_at->format('dmY-Him').'.pdf');
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinan = User::where('jabatan','dekan')->where('status_aktif','aktif')->first();
        $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        return $user;
    }

    private function isKodeSuratPindahExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat persetujuan pindah')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }

    public function tandaTanganPindah(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-persetujuan-pindah');
        }
        $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first();
        $pengajuanSuratPindah = PengajuanSuratPersetujuanPindah::findOrFail($request->id);
        NotifikasiMahasiswa::create([
            'nim'=>$pengajuanSuratPindah->nim,
            'judul_notifikasi'=>'Surat Persetujuan Pindah',
            'isi_notifikasi'=>'Surat persetujuan pindah telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-persetujuan-pindah'),
        ]);
        $pengajuanSuratPindah->update([
            'status'=>'selesai',
        ]);
        NotifikasiUser::create([
            'nip'=>$user->nip,
            'judul_notifikasi'=>'Surat Persetujuan Pindah',
            'isi_notifikasi'=>'Surat persetujuan pindah telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-persetujuan-pindah')
        ]);
        $this->setFlashData('success','Berhasil','Tanda tangan surat persetujuan pindah berhasil');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    private function uploadImage($imageFieldName, $request, $uploadPath){
        $image = $request->file($imageFieldName);
        $ext = $image->getClientOriginalExtension();
        if($image->isValid()){
            $imageName = $request->nim.'_'.date('YmdHis').'_'.$imageFieldName.".$ext";
            $image->move($uploadPath,$imageName);            
            return $imageName;
        }
        return false;
    }

    private function deleteImage($imageName){
        $exist = Storage::disk('file_persetujuan_pindah')->exists($imageName);
        if(isset($imageName) && $exist){
            $delete = Storage::disk('file_persetujuan_pindah')->delete($imageName);
            if($delete) return true;
            return false;
        }
    }
}
