<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use App\NotifikasiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PengajuanSuratPersetujuanPindah;
use App\Http\Requests\PengajuanSuratPersetujuanPindahRequest;

class PengajuanSuratPersetujuanPindahController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $pengajuanSuratPindahList = PengajuanSuratPersetujuanPindah::where('nim',Session::get('nim'))
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
        $countAllPengajuan = PengajuanSuratPersetujuanPindah::where('nim',Session::get('nim'))->count();
        $countPengajuanSuratPersetujuanPindah = PengajuanSuratPersetujuanPindah::where('nim',Session::get('nim'))
                                            ->count();
        return view($this->segmentUser.'.pengajuan_surat_persetujuan_pindah',compact('countAllPengajuan','countPengajuanSuratPersetujuanPindah','perPage','pengajuanSuratPindahList'));
    }

    public function create(){
        if(!$this->isSuratPindahDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-persetujuan-pindah');
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_persetujuan_pindah');
    }

    public function show(PengajuanSuratPersetujuanPindah $pengajuanSuratPindah){
        $pengajuan = collect($pengajuanSuratPindah->load(['mahasiswa']));
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

    public function store(PengajuanSuratPersetujuanPindahRequest $request){
        $input = $request->all();
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
        $user = User::where('jabatan','kasubag kemahasiswaan')->first();
        DB::beginTransaction();
        try{
            $pengajuan = PengajuanSuratPersetujuanPindah::create($input);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Pengajuan Surat Persetujuan Pindah',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$pengajuan->mahasiswa->nama.' membuat pengajuan surat persetujuan pindah.',
                'link_notifikasi'=>url('pegawai/surat-persetujuan-pindah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Pengajuan surat persetujuan pindah gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat persetujuan pindah berhasil ditambahkan');
        return redirect($this->segmentUser.'/pengajuan/surat-persetujuan-pindah');
    }

    public function edit(PengajuanSuratPersetujuanPindah $pengajuanSuratPersetujuanPindah){
        return view($this->segmentUser.'.edit_pengajuan_surat_persetujuan_pindah',compact('pengajuanSuratPersetujuanPindah'));
    }

    public function update(PengajuanSuratPersetujuanPindahRequest $request, PengajuanSuratPersetujuanPindah $pengajuanSuratPersetujuanPindah){
        $input = $request->all();
        if($request->has('file_surat_keterangan_lulus_butuh')){
            $imageFieldName = 'file_surat_keterangan_lulus_butuh'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($pengajuanSuratPersetujuanPindah->file_surat_keterangan_lulus_butuh);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_ijazah_terakhir')){
            $imageFieldName = 'file_ijazah_terakhir'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($pengajuanSuratPersetujuanPindah->file_ijazah_terakhir);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_rekomendasi_jurusan')){
            $imageFieldName = 'file_surat_rekomendasi_jurusan'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($pengajuanSuratPersetujuanPindah->file_surat_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_keterangan_bebas_perlengkapan_universitas')){
            $imageFieldName = 'file_surat_keterangan_bebas_perlengkapan_universitas'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_universitas);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_keterangan_bebas_perlengkapan_fakultas')){
            $imageFieldName = 'file_surat_keterangan_bebas_perlengkapan_fakultas'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_fakultas);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_keterangan_bebas_perpustakaan_universitas')){
            $imageFieldName = 'file_surat_keterangan_bebas_perpustakaan_universitas'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_universitas);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_surat_keterangan_bebas_perpustakaan_fakultas')){
            $imageFieldName = 'file_surat_keterangan_bebas_perpustakaan_fakultas'; 
            $uploadPath = 'upload_persetujuan_pindah';
            $this->deleteImage($pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_fakultas);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $pengajuanSuratPersetujuanPindah->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat persetujuan pindah berhasil diubah');
        return redirect($this->segmentUser.'/pengajuan/surat-persetujuan-pindah');
    }

    public function progress(PengajuanSuratPersetujuanPindah $pengajuanSuratPersetujuanPindah){
        $pengajuan = $pengajuanSuratPersetujuanPindah->load(['mahasiswa']);
        $data = collect($pengajuan);
        $tanggalDiajukan = $pengajuan->created_at->isoFormat('D MMMM Y - HH:m:s');
        $data->put('tanggal_diajukan',$tanggalDiajukan);

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuan->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $tanggalTunggu = $pengajuan->suratPersetujuanPindah->created_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_tunggu_tanda_tangan',$tanggalTunggu);
            $data->put('tanggal_selesai',$tanggalSelesai);
        }else if($pengajuan->status == 'ditolak'){
            $tanggalDitolak = $pengajuan->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_ditolak',$tanggalDitolak);
        }else if($pengajuan->status == 'menunggu tanda tangan'){
            $tanggalTunggu = $pengajuan->updated_at->isoFormat('D MMMM Y - HH:m:s');
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
    
    private function deleteImage($imageName){
        $exist = Storage::disk('file_persetujuan_pindah')->exists($imageName);
        if(isset($imageName) && $exist){
            $delete = Storage::disk('file_persetujuan_pindah')->delete($imageName);
            if($delete) return true;
            return false;
        }
    }

    private function isSuratPindahDiajukanExists(){
        $suratKeterangan = PengajuanSuratPersetujuanPindah::where('status','diajukan')->exists();
        if($suratKeterangan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat persetujuan pindah sementara diproses!');
            return false;
        }
        return true;
    }
}
