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
