<?php

namespace App\Http\Controllers;

use PDF;
use Storage;
use Session;
use App\User;
use App\KodeSurat;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\SuratPersetujuanPindah;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use App\PengajuanSuratPersetujuanPindah;
use App\Http\Requests\SuratPersetujuanPindahRequest;

class SuratPersetujuanPindahController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                        ->orderByDesc('surat_persetujuan_pindah.created_at')
                                        ->orderByDesc('nomor_surat')
                                        ->paginate($perPage,['*'],'page');

        $pengajuanSuratPersetujuanList = PengajuanSuratPersetujuanPindah::whereNotIn('status',['selesai','menunggu tanda tangan'])
                                                ->orderByDesc('created_at')
                                                ->orderBy('status')
                                                ->paginate($perPage,['*'],'page_pengajuan');
        
        $countAllSuratPersetujuanPindah = $suratPersetujuanPindahList->count();
        $countSuratPersetujuanPindah = $suratPersetujuanPindahList->count();

        $countPengajuanSuratPersetujuanPindah = $pengajuanSuratPersetujuanList->count();
        $countAllPengajuanSuratPersetujuanPindah = $pengajuanSuratPersetujuanList->count();

        $nomorSurat = $this->generateNomorSuratPindah(['selesai','menunggu tanda tangan']);

        return view('user.'.$this->segmentUser.'.surat_persetujuan_pindah',compact('perPage','nomorSurat','suratPersetujuanPindahList','pengajuanSuratPersetujuanList','countAllSuratPersetujuanPindah','countSuratPersetujuanPindah','countPengajuanSuratPersetujuanPindah','countAllPengajuanSuratPersetujuanPindah'));
    }

    public function suratPindahPimpinan(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratPindah(['selesai']);
        $suratPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                            ->orderByDesc('surat_persetujuan_pindah.created_at')
                            ->where('status','selesai')
                            ->paginate($perPage);
        $pengajuanSuratPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                    ->whereIn('status',['menunggu tanda tangan'])
                                    ->where('nip',Session::get('nip'))
                                    ->paginate($perPage);
        $countAllPengajuanSuratPindah = $pengajuanSuratPindahList->count();
        $countAllSuratPindah = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                ->where('status','selesai')
                                ->count();
        $countSuratPindah = $suratPindahList->count();
        return view('user.'.$this->segmentUser.'.surat_persetujuan_pindah',compact('perPage','mahasiswa','nomorSurat','countAllSuratPindah','countSuratPindah','suratPindahList','pengajuanSuratPindahList','countAllPengajuanSuratPindah'));
    }

    public function create()
    {
        if(!$this->isKodeSuratPindahExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-tugas');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.tambah_surat_persetujuan_pindah',compact('userList','kodeSurat','nomorSuratBaru','mahasiswa','userList'));
    }   

    public function searchPimpinan(Request $request){
        $keyword = $request->all(); 
        if(isset($keyword['keywords'])){
            $nomor = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratPindah(['selesai']);
            $suratPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                ->where('nomor_surat','like',"%$nomor%")
                                ->orderByDesc('surat_persetujuan_pindah.created_at')
                                ->where('status','selesai')
                                ->paginate($perPage);
            $pengajuanSuratPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                        ->whereIn('status',['menunggu tanda tangan'])
                                        ->where('nip',Session::get('nip'))
                                        ->paginate($perPage);
            $countAllPengajuanSuratPindah = $pengajuanSuratPindahList->count();
            $countAllSuratPindah = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                    ->where('status','selesai')
                                    ->count();
            $countSuratPindah = $suratPindahList->count();
            if($countSuratPindah < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat persetujuan pindah tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_persetujuan_pindah',compact('perPage','mahasiswa','nomorSurat','countAllSuratPindah','countSuratPindah','suratPindahList','pengajuanSuratPindahList','countAllPengajuanSuratPindah'));
         }else{
            return redirect($this->segmentUser.'/surat-persetujuan-pindah');
        }
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords'])){
            $nomor = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            
            $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                            ->where('nomor_surat','like',"%$nomor%")
                                            ->orderByDesc('surat_persetujuan_pindah.created_at')
                                            ->orderByDesc('nomor_surat')
                                            ->paginate($perPage,['*'],'page');

            $pengajuanSuratPersetujuanList = PengajuanSuratPersetujuanPindah::whereNotIn('status',['selesai','menunggu tanda tangan'])
                                ->orderByDesc('created_at')
                                ->orderBy('status')
                                ->paginate($perPage,['*'],'page_pengajuan');

            $countAllSuratPersetujuanPindah = $suratPersetujuanPindahList->count();
            $countSuratPersetujuanPindah = $suratPersetujuanPindahList->count();

            $countPengajuanSuratPersetujuanPindah = $pengajuanSuratPersetujuanList->count();
            $countAllPengajuanSuratPersetujuanPindah = $pengajuanSuratPersetujuanList->count();

            $nomorSurat = $this->generateNomorSuratPindah(['selesai','menunggu tanda tangan']);

            if($countSuratPersetujuanPindah < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat persetujuan pindah tidak ditemukan!');
            }

            return view('user.'.$this->segmentUser.'.surat_persetujuan_pindah',compact('perPage','nomorSurat','suratPersetujuanPindahList','pengajuanSuratPersetujuanList','countAllSuratPersetujuanPindah','countSuratPersetujuanPindah','countPengajuanSuratPersetujuanPindah','countAllPengajuanSuratPersetujuanPindah'));
        }else{
            return redirect($this->segmentUser.'/surat-persetujuan-pindah');
        }
    }

    public function createSurat(PengajuanSuratPersetujuanPindah $pengajuanSuratPersetujuanPindah)
    {
        if(!$this->isKodeSuratPindahExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-persetujuan-pindah');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.tambah_pengajuan_surat_persetujuan_pindah',compact('userList','kodeSurat','nomorSuratBaru','mahasiswa','userList','pengajuanSuratPersetujuanPindah'));
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
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-persetujuan-pindah'),
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
            'id_pengajuan_persetujuan_pindah'=>'required|numeric',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat',
            'nip'=>'required|numeric',
            'id_kode_surat'=>'required|numeric',
        ]);
        $input = $request->all();
        $pengajuanSuratPersetujuanPindah = PengajuanSuratPersetujuanPindah::findOrFail($request->id_pengajuan_persetujuan_pindah);
        DB::beginTransaction();
        try{
            SuratPersetujuanPindah::create($input);
            $pengajuanSuratPersetujuanPindah->update([
                'status'=>'menunggu tanda tangan',
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratPersetujuanPindah->mahasiswa->nim,
                'judul_notifikasi'=>'Surat Persetujuan Pindah',
                'isi_notifikasi'=>'Surat persetujuan pindah telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-persetujuan-pindah'),
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Persetujuan Pindah',
                'isi_notifikasi'=>'Tanda tangan surat persetujuan pindah.',
                'link_notifikasi'=>url('pimpinan/surat-persetujuan-pindah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat Persetujuan Pindah gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat persetujuan pindah mahasiswa dengan nama '.$pengajuanSuratPersetujuanPindah->mahasiswa->nama.' berhasil ditambahkan');
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
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-persetujuan-pindah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat Persetujuan Pindah gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Pengajuan surat persetujuan pindah mahasiswa dengan nama '.$pengajuanSuratPersetujuanPindah->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function show(SuratPersetujuanPindah $suratPersetujuanPindah)
    {
        $pengajuanSuratPindah =$suratPersetujuanPindah->pengajuanSuratPersetujuanPindah;
        $pengajuan = collect($suratPersetujuanPindah->load(['pengajuanSuratPersetujuanPindah.mahasiswa','kodeSurat','user']));
        $pengajuan->put('created_at',$pengajuanSuratPindah->created_at->isoFormat('D MMMM Y'));
        $pengajuan->put('updated_at',$pengajuanSuratPindah->updated_at->isoFormat('D MMMM Y'));
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

    public function destroy(SuratPersetujuanPindah $suratPersetujuanPindah)
    {
        $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_lulus_butuh);
        $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_ijazah_terakhir);
        $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_rekomendasi_jurusan);
        $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_universitas);
        $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_fakultas);
        $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_universitas);
        $this->deleteImage($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_fakultas);
        $suratPersetujuanPindah->delete();
        $this->setFlashData('success','Berhasil','Surat persetujuan pindah mahasiswa dengan nama '.$suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function cetakSuratPindah(SuratPersetujuanPindah $suratPersetujuanPindah){
        if(Session::has('nim')){
            if($suratPersetujuanPindah->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Persetujuan Pindah','Anda telah mencetak surat persetujuan pindah sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-persetujuan-pindah');
            }
        }
        $jumlahCetak = ++$suratPersetujuanPindah->jumlah_cetak;
        $suratPersetujuanPindah->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_persetujuan_pindah',compact('suratPersetujuanPindah'))->setPaper('a4', 'potrait');
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

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat persetujuan pindah')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generateNomorSuratPindah($status){
        $suratPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                            ->whereIn('status',$status)
                            ->get();
        $nomorSuratList = [];
        foreach ($suratPindahList as $suratPindah) {
            $nomorSuratList[$suratPindah->nomor_surat] = 'B/'.$suratPindah->nomor_surat.'/'.$suratPindah->kodeSurat->kode_surat.'/'.$suratPindah->created_at->year;
        }
        return $nomorSuratList;
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
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-persetujuan-pindah'),
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
