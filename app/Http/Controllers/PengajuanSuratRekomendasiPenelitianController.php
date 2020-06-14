<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use App\Mahasiswa;
use App\NotifikasiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PengajuanSuratRekomendasiPenelitian;
use App\Http\Requests\PengajuanSuratRekomendasiPenelitianRequest;

class PengajuanSuratRekomendasiPenelitianController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $pengajuanSuratPenelitianList = PengajuanSuratRekomendasiPenelitian::where('nim',Session::get('nim'))->paginate($perPage);
        $countAllPengajuan = $pengajuanSuratPenelitianList->count();
        $countPengajuanSuratPenelitian = $pengajuanSuratPenelitianList->count();
        return view($this->segmentUser.'.pengajuan_surat_rekomendasi_penelitian',compact('perPage','pengajuanSuratPenelitianList','countAllPengajuan','countPengajuanSuratPenelitian'));
    }

    public function create(){
        if(!$this->isSuratPenelitianDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-rekomendasi-penelitian');
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_rekomendasi_penelitian');
    }

    public function store(PengajuanSuratRekomendasiPenelitianRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        DB::beginTransaction();

        try{ 
            $user = User::where('jabatan','kasubag pendidikan dan pengajaran')->where('status_aktif','aktif')->first();
            PengajuanSuratRekomendasiPenelitian::create($input);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat rekomendasi penelitian.',
                'link_notifikasi'=>url('pegawai/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat rekomendasi penelitian gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi penelitian berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-rekomendasi-penelitian');
    }

    public function edit(PengajuanSuratRekomendasiPenelitian $pengajuanSuratPenelitian)
    {
        return view($this->segmentUser.'.edit_pengajuan_surat_rekomendasi_penelitian',compact('pengajuanSuratPenelitian'));        
    }

    public function update(PengajuanSuratRekomendasiPenelitianRequest $request, PengajuanSuratRekomendasiPenelitian $pengajuanSuratPenelitian){
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$pengajuanSuratPenelitian->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $pengajuanSuratPenelitian->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi penelitian berhasil diubah.');
        return redirect($this->segmentUser.'/pengajuan/surat-rekomendasi-penelitian');
    }

    public function progress(PengajuanSuratRekomendasiPenelitian $pengajuanSuratPenelitian){
        $pengajuan = $pengajuanSuratPenelitian->load(['mahasiswa']);
        $data = collect($pengajuan);
        $tanggalDiajukan = $pengajuanSuratPenelitian->created_at->isoFormat('D MMMM Y - HH:m:s');
        $data->put('tanggal_diajukan',$tanggalDiajukan);

        if($pengajuanSuratPenelitian->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratPenelitian->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $tanggalTunggu = $pengajuanSuratPenelitian->suratRekomendasiPenelitian->created_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
            $data->put('tanggal_selesai',$tanggalSelesai);
        }else if($pengajuanSuratPenelitian->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratPenelitian->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_ditolak',$tanggalDitolak);
        }else if($pengajuanSuratPenelitian->status == 'menunggu tanda tangan'){
            $tanggalTunggu = $pengajuanSuratPenelitian->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
        }
        return $data->toJson();
    }

    public function show(PengajuanSuratRekomendasiPenelitian $pengajuanSuratPenelitian)
    {
        $pengajuan = collect($pengajuanSuratPenelitian->load('mahasiswa.prodi.jurusan'));
        $pengajuan->put('created_at',$pengajuanSuratPenelitian->created_at->isoFormat('D MMMM Y'));
        $pengajuan->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$pengajuanSuratPenelitian->file_rekomendasi_jurusan));

        $pengajuan->put('nama_file_rekomendasi_jurusan',explode('.',$pengajuanSuratPenelitian->file_rekomendasi_jurusan)[0]);
        return $pengajuan->toJson();
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

    private function isSuratPenelitianDiajukanExists(){
        $suratPenelitian = PengajuanSuratRekomendasiPenelitian::where('status','diajukan')->exists();
        if($suratPenelitian){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat rekomendasi penelitian sementara diproses!');
            return false;
        }
        return true;
    }
}
