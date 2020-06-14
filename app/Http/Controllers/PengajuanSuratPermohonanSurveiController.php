<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use App\Mahasiswa;
use App\NotifikasiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PengajuanSuratPermohonanSurvei;
use App\Http\Requests\PengajuanSuratPermohonanSurveiRequest;

class PengajuanSuratPermohonanSurveiController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $pengajuanSuratSurveiList = PengajuanSuratPermohonanSurvei::where('nim',Session::get('nim'))->paginate($perPage);
        $countAllPengajuan = $pengajuanSuratSurveiList->count();
        $countPengajuanSuratSurvei = $pengajuanSuratSurveiList->count();
        return view($this->segmentUser.'.pengajuan_surat_permohonan_survei',compact('perPage','pengajuanSuratSurveiList','countAllPengajuan','countPengajuanSuratSurvei'));
    }

    public function show(PengajuanSuratPermohonanSurvei $pengajuanSuratSurvei)
    {
        $pengajuan = collect($pengajuanSuratSurvei->load('mahasiswa.prodi.jurusan'));
        $pengajuan->put('created_at',$pengajuanSuratSurvei->created_at->isoFormat('D MMMM Y'));
        $pengajuan->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$pengajuanSuratSurvei->file_rekomendasi_jurusan));

        $pengajuan->put('nama_file_rekomendasi_jurusan',explode('.',$pengajuanSuratSurvei->file_rekomendasi_jurusan)[0]);
        return $pengajuan->toJson();
    }

    public function create(){
        if(!$this->isSuratSurveiDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-permohonan-survei');
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_permohonan_survei');
    }

    public function store(PengajuanSuratPermohonanSurveiRequest $request){
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
            PengajuanSuratPermohonanSurvei::create($input);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat permohonan survei.',
                'link_notifikasi'=>url('pegawai/surat-permohonan-survei')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat permohonan survei gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan survei berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-permohonan-survei');
    }

    public function edit(PengajuanSuratPermohonanSurvei $pengajuanSuratSurvei)
    {
        return view($this->segmentUser.'.edit_pengajuan_surat_permohonan_survei',compact('pengajuanSuratSurvei'));        
    }

    public function update(PengajuanSuratPermohonanSurveiRequest $request, PengajuanSuratPermohonanSurvei $pengajuanSuratSurvei){
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$pengajuanSuratSurvei->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $pengajuanSuratSurvei->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan survei berhasil diubah.');
        return redirect($this->segmentUser.'/pengajuan/surat-permohonan-survei');
    }

    public function progress(PengajuanSuratPermohonanSurvei $pengajuanSuratSurvei){
        $pengajuan = $pengajuanSuratSurvei->load(['mahasiswa']);
        $data = collect($pengajuan);
        $tanggalDiajukan = $pengajuanSuratSurvei->created_at->isoFormat('D MMMM Y - HH:m:s');
        $data->put('tanggal_diajukan',$tanggalDiajukan);

        if($pengajuanSuratSurvei->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratSurvei->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $tanggalTunggu = $pengajuanSuratSurvei->suratPermohonanSurvei->created_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
            $data->put('tanggal_selesai',$tanggalSelesai);
        }else if($pengajuanSuratSurvei->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratSurvei->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_ditolak',$tanggalDitolak);
        }else if($pengajuanSuratSurvei->status == 'menunggu tanda tangan'){
            $tanggalTunggu = $pengajuanSuratSurvei->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
        }
        return $data->toJson();
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

    private function isSuratSurveiDiajukanExists(){
        $suratSurvei = PengajuanSuratPermohonanSurvei::where('status','diajukan')->exists();
        if($suratSurvei){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat permohonan survei sementara diproses!');
            return false;
        }
        return true;
    }
}
