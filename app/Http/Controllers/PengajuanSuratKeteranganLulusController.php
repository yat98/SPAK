<?php

namespace App\Http\Controllers;

use Storage;
use DB;
use Session;
use App\User;
use App\Mahasiswa;
use App\NotifikasiUser;
use Illuminate\Http\Request;
use App\PengajuanSuratKeteranganLulus;
use App\Http\Requests\PengajuanSuratKeteranganLulusRequest;

class PengajuanSuratKeteranganLulusController extends Controller
{
    public function indexMahasiswa()
    {
        $perPage = $this->perPage;
        $pengajuanSuratLulusList = PengajuanSuratKeteranganLulus::where('nim',Session::get('nim'))->paginate($perPage);
        $countAllPengajuan = $pengajuanSuratLulusList->count();
        $countPengajuanSuratLulus = $pengajuanSuratLulusList->count();
        return view($this->segmentUser.'.pengajuan_surat_keterangan_lulus',compact('perPage','pengajuanSuratLulusList','countAllPengajuan','countPengajuanSuratLulus'));
    }

    public function create()
    {
        if(!$this->isSuratLulusDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-keterangan-lulus');
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_lulus');
    }

    public function store(PengajuanSuratKeteranganLulusRequest $request)
    {
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_berita_acara_ujian')){
            $imageFieldName = 'file_berita_acara_ujian'; 
            $uploadPath = 'upload_berita_acara_ujian';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        DB::beginTransaction();

        try{ 
            $user = User::where('jabatan','kasubag pendidikan dan pengajaran')->where('status_aktif','aktif')->first();
            PengajuanSuratKeteranganLulus::create($input);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan lulus.',
                'link_notifikasi'=>url('pegawai/surat-keterangan-lulus')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan lulus gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan lulus berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-lulus');
    }

    public function progress(PengajuanSuratKeteranganLulus $pengajuanSuratLulus){
        $pengajuan = $pengajuanSuratLulus->load(['mahasiswa']);
        $data = collect($pengajuan);
        $tanggalDiajukan = $pengajuanSuratLulus->created_at->isoFormat('D MMMM Y - HH:m:s');
        $data->put('tanggal_diajukan',$tanggalDiajukan);

        if($pengajuanSuratLulus->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratLulus->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $tanggalTunggu = $pengajuanSuratLulus->suratKeteranganLulus->created_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
            $data->put('tanggal_selesai',$tanggalSelesai);
        }else if($pengajuanSuratLulus->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratLulus->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_ditolak',$tanggalDitolak);
        }else if($pengajuanSuratLulus->status == 'menunggu tanda tangan'){
            $tanggalTunggu = $pengajuanSuratLulus->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
        }
        return $data->toJson();
    }

    public function show(PengajuanSuratKeteranganLulus $pengajuanSuratLulus)
    {
        $pengajuan = collect($pengajuanSuratLulus->load('mahasiswa.prodi.jurusan'));
        $pengajuan->put('created_at',$pengajuanSuratLulus->created_at->isoFormat('D MMMM Y'));
        $pengajuan->put('tanggal_wisuda',$pengajuanSuratLulus->tanggal_wisuda->isoFormat('D MMMM Y'));
        $pengajuan->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$pengajuanSuratLulus->file_rekomendasi_jurusan));
        $pengajuan->put('file_berita_acara_ujian',asset('upload_berita_acara_ujian/'.$pengajuanSuratLulus->file_berita_acara_ujian));

        $pengajuan->put('nama_file_rekomendasi_jurusan',explode('.',$pengajuanSuratLulus->file_rekomendasi_jurusan)[0]);
        $pengajuan->put('nama_file_berita_acara_ujian',explode('.',$pengajuanSuratLulus->file_berita_acara_ujian)[0]);
        return $pengajuan->toJson();
    }

    public function edit(PengajuanSuratKeteranganLulus $pengajuanSuratLulus)
    {
        return view($this->segmentUser.'.edit_pengajuan_surat_keterangan_lulus',compact('pengajuanSuratLulus'));        
    }

    public function update(PengajuanSuratKeteranganLulusRequest $request, PengajuanSuratKeteranganLulus $pengajuanSuratLulus)
    {
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$pengajuanSuratLulus->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_berita_acara_ujian')){
            $imageFieldName = 'file_berita_acara_ujian'; 
            $uploadPath = 'upload_berita_acara_ujian';
            $this->deleteImage($imageFieldName,$pengajuanSuratLulus->file_berita_acara_ujian);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $pengajuanSuratLulus->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan lulus berhasil diubah.');
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-lulus');
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
    
    private function deleteImage($imageFieldName,$imageName){
        $exist = Storage::disk($imageFieldName)->exists($imageName);
        if(isset($imageName) && $exist){
            $delete = Storage::disk($imageFieldName)->delete($imageName);
            if($delete) return true;
            return false;
        }
    }

    private function isSuratLulusDiajukanExists(){
        $suratKeterangan = PengajuanSuratKeteranganLulus::where('status','diajukan')->exists();
        if($suratKeterangan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat keterangan lulus sementara diproses!');
            return false;
        }
        return true;
    }
}
