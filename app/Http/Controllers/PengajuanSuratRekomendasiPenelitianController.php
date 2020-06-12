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
