<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Mahasiswa;
use App\NotifikasiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\PengajuanSuratPermohonanPengambilanMaterial;
use App\Http\Requests\PengajuanSuratPermohonanPengambilanMaterialRequest;

class PengajuanSuratPermohonanPengambilanMaterialController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $pengajuanSuratMaterialList = PengajuanSuratPermohonanPengambilanMaterial::join('daftar_kelompok_pengambilan_material','daftar_kelompok_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                        ->where('daftar_kelompok_pengambilan_material.nim',Session::get('nim'))
                                        ->paginate($perPage);
        $countAllPengajuan = $pengajuanSuratMaterialList->count();
        $countPengajuanSuratMaterial = $pengajuanSuratMaterialList->count();
        return view($this->segmentUser.'.pengajuan_surat_permohonan_pengambilan_material',compact('perPage','pengajuanSuratMaterialList','countAllPengajuan','countPengajuanSuratMaterial'));
    }

    public function create()
    {
        if(!$this->isSuratMaterialDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-permohonan-pengambilan-material');
        }
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.tambah_pengajuan_surat_permohonan_pengambilan_material',compact('mahasiswa'));
    }

    public function store(PengajuanSuratPermohonanPengambilanMaterialRequest $request){
        $input = $request->all();
        $input['nim'] = Session::get('nim');
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        try{ 
            $user = User::where('jabatan','kasubag pendidikan dan pengajaran')->where('status_aktif','aktif')->first();
            $pengajuan = PengajuanSuratPermohonanPengambilanMaterial::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat permohonan pengambilan material gagal dibuat.');
        }

        try{ 
            $pengajuan->daftarKelompok()->attach($request->mahasiswa);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat permohonan pengambilan material.',
                'link_notifikasi'=>url('pegawai/surat-permohonan-pengambilan-material')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat permohonan pengambilan material gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan material berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-permohonan-pengambilan-material');
    }

    public function edit(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSuratMaterial){
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_permohonan_pengambilan_material',compact('pengajuanSuratMaterial','mahasiswa'));
    }

    public function update(PengajuanSuratPermohonanPengambilanMaterialRequest $request, PengajuanSuratPermohonanPengambilanMaterial $pengajuanSuratMaterial){
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$pengajuanSuratMaterial->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $pengajuanSuratMaterial->update($input);
        $pengajuanSuratMaterial->daftarKelompok()->sync($request->mahasiswa);
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan material berhasil diubah.');
        return redirect($this->segmentUser.'/pengajuan/surat-permohonan-pengambilan-material');
    }

    public function show(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSuratMaterial)
    {
        $pengajuan = collect($pengajuanSuratMaterial->load(['mahasiswa.prodi.jurusan','daftarKelompok']));
        $pengajuan->put('created_at',$pengajuanSuratMaterial->created_at->isoFormat('D MMMM Y'));
        $pengajuan->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$pengajuanSuratMaterial->file_rekomendasi_jurusan));

        $pengajuan->put('nama_file_rekomendasi_jurusan',explode('.',$pengajuanSuratMaterial->file_rekomendasi_jurusan)[0]);
        return $pengajuan->toJson();
    }

    public function progress(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSuratMaterial){
        $pengajuan = $pengajuanSuratMaterial->load(['mahasiswa']);
        $data = collect($pengajuan);
        $tanggalDiajukan = $pengajuanSuratMaterial->created_at->isoFormat('D MMMM Y - HH:m:s');
        $data->put('tanggal_diajukan',$tanggalDiajukan);

        if($pengajuanSuratMaterial->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratMaterial->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $tanggalTunggu = $pengajuanSuratMaterial->suratPermohonanPengambilanMaterial->created_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
            $data->put('tanggal_selesai',$tanggalSelesai);
        }else if($pengajuanSuratMaterial->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratMaterial->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_ditolak',$tanggalDitolak);
        }else if($pengajuanSuratMaterial->status == 'menunggu tanda tangan'){
            $tanggalTunggu = $pengajuanSuratMaterial->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
        }
        return $data->toJson();
    }
    
    private function uploadImage($imageFieldName, $request, $uploadPath){
        $image = $request->file($imageFieldName);
        $ext = $image->getClientOriginalExtension();
        if($image->isValid()){
            $imageName = Session::get('nim').'_'.date('YmdHis').'_'.$imageFieldName.".$ext";
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

    private function isSuratMaterialDiajukanExists(){
        $suratMaterial = PengajuanSuratPermohonanPengambilanMaterial::where('status','diajukan')->exists();
        if($suratMaterial){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat permohonan pengambilan material sementara diproses!');
            return false;
        }
        return true;
    }
}
