<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use App\Mahasiswa;
use App\NotifikasiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PengajuanSuratPermohonanPengambilanDataAwal;
use App\Http\Requests\PengajuanSuratPermohonanPengambilanDataAwalRequest;

class PengajuanSuratPermohonanPengambilanDataAwalController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $pengajuanSuratDataAwalList = PengajuanSuratPermohonanPengambilanDataAwal::where('nim',Session::get('nim'))->paginate($perPage);
        $countAllPengajuan = $pengajuanSuratDataAwalList->count();
        $countPengajuanSuratDataAwal = $pengajuanSuratDataAwalList->count();
        return view($this->segmentUser.'.pengajuan_surat_permohonan_pengambilan_data_awal',compact('perPage','pengajuanSuratDataAwalList','countAllPengajuan','countPengajuanSuratDataAwal'));
    }

    public function create(){
        if(!$this->isSuratDataAwalDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-permohonan-pengambilan-data-awal');
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_permohonan_pengambilan_data_awal');
    }

    public function store(PengajuanSuratPermohonanPengambilanDataAwalRequest $request){
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
            PengajuanSuratPermohonanPengambilanDataAwal::create($input);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat permohonan pengambilan data awal.',
                'link_notifikasi'=>url('pegawai/surat-permohonan-pengambilan-data-awal')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat permohonan pengambilan data awal gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan data awal berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-permohonan-pengambilan-data-awal');
    }

    public function edit(PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSuratDataAwal)
    {
        return view($this->segmentUser.'.edit_pengajuan_surat_permohonan_pengambilan_data_awal',compact('pengajuanSuratDataAwal'));        
    }

    public function update(PengajuanSuratPermohonanPengambilanDataAwalRequest $request, PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSuratDataAwal){
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$pengajuanSuratDataAwal->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $pengajuanSuratDataAwal->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan data awal berhasil diubah.');
        return redirect($this->segmentUser.'/pengajuan/surat-permohonan-pengambilan-data-awal');
    }

    public function progress(PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSuratDataAwal){
        $pengajuan = $pengajuanSuratDataAwal->load(['mahasiswa']);
        $data = collect($pengajuan);
        $tanggalDiajukan = $pengajuanSuratDataAwal->created_at->isoFormat('D MMMM Y - HH:m:s');
        $data->put('tanggal_diajukan',$tanggalDiajukan);

        if($pengajuanSuratDataAwal->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratDataAwal->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $tanggalTunggu = $pengajuanSuratDataAwal->suratPermohonanPengambilanDataAwal->created_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
            $data->put('tanggal_selesai',$tanggalSelesai);
        }else if($pengajuanSuratDataAwal->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratDataAwal->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_ditolak',$tanggalDitolak);
        }else if($pengajuanSuratDataAwal->status == 'menunggu tanda tangan'){
            $tanggalTunggu = $pengajuanSuratDataAwal->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
        }
        return $data->toJson();
    }

    public function show(PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSuratDataAwal)
    {
        $pengajuan = collect($pengajuanSuratDataAwal->load('mahasiswa.prodi.jurusan'));
        $pengajuan->put('created_at',$pengajuanSuratDataAwal->created_at->isoFormat('D MMMM Y'));
        $pengajuan->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$pengajuanSuratDataAwal->file_rekomendasi_jurusan));

        $pengajuan->put('nama_file_rekomendasi_jurusan',explode('.',$pengajuanSuratDataAwal->file_rekomendasi_jurusan)[0]);
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

    private function isSuratDataAwalDiajukanExists(){
        $suratDataAwal = PengajuanSuratPermohonanPengambilanDataAwal::where('status','diajukan')->exists();
        if($suratDataAwal){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat rekomendasi penelitian sementara diproses!');
            return false;
        }
        return true;
    }
}
