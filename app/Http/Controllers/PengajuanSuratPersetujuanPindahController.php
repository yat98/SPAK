<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use Illuminate\Http\Request;
use App\PengajuanSuratPersetujuanPindah;
use App\Http\Requests\PengajuanSuratPersetujuanPindahRequest;

class PengajuanSuratPersetujuanPindahController extends Controller
{
    public function persetujuanPindahMahasiswa(){
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
        PengajuanSuratPersetujuanPindah::create($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat persetujuan pindah berhasil ditambahkan');
        return redirect($this->segmentUser.'/pengajuan/surat-persetujuan-pindah');
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
}
