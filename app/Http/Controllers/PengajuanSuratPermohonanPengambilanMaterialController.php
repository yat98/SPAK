<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Operator;
use DataTables;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\PengajuanSuratPermohonanPengambilanMaterial;
use App\Http\Requests\PengajuanSuratPermohonanPengambilanMaterialRequest;

class PengajuanSuratPermohonanPengambilanMaterialController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratPermohonanPengambilanMaterial::join('daftar_kelompok_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','daftar_kelompok_pengambilan_material.id_pengajuan')
                                ->join('mahasiswa','mahasiswa.nim','=','daftar_kelompok_pengambilan_material.nim')
                                ->where('daftar_kelompok_pengambilan_material.nim',Auth::user()->nim)
                                ->count();
        
        return view($this->segmentUser.'.surat_permohonan_pengambilan_material',compact('countAllPengajuan','perPage'));
    }

    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratPermohonanPengambilanMaterial::join('daftar_kelompok_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','daftar_kelompok_pengambilan_material.id_pengajuan')
                                    ->join('mahasiswa','mahasiswa.nim','=','daftar_kelompok_pengambilan_material.nim')
                                    ->where('daftar_kelompok_pengambilan_material.nim',Auth::user()->nim)
                                    ->select('pengajuan_surat_permohonan_pengambilan_material.*'))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
        } else if(isset(Auth::user()->id)){
            if(Auth::user()->bagian == 'front office'){
                $pengajuanSurat = PengajuanSuratPermohonanPengambilanMaterial::whereIn('status',['diajukan','ditolak'])
                                    ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian pendidikan dan pengajaran'){
                $pengajuanSurat = PengajuanSuratPermohonanPengambilanMaterial::whereIn('status',['diajukan','ditolak']);
            }
            
            return DataTables::of($pengajuanSurat)
                        ->addColumn('aksi', function ($data) {
                            return $data->id_pengajuan;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
        } else if(isset(Auth::user()->nip)){
            $pengajuanSurat = PengajuanSuratPermohonanPengambilanMaterial::leftJoin('mahasiswa','pengajuan_surat_permohonan_pengambilan_material.nim','=','mahasiswa.nim')
                                ->join('surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                ->select('mahasiswa.nama','pengajuan_surat_permohonan_pengambilan_material.*','mahasiswa.nim')
                                ->with(['mahasiswa','suratPermohonanPengambilanMaterial.kodeSurat']);
            
            if (Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_permohonan_pengambilan_material.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_permohonan_pengambilan_material.status','verifikasi kabag');
            }

            return DataTables::of($pengajuanSurat)
                            ->addColumn('aksi', function ($data) {
                                return $data->id_pengajuan;
                            })
                            ->addColumn("waktu_pengajuan", function ($data) {
                                return $data->created_at->diffForHumans();                            
                            })
                            ->editColumn("status", function ($data) {
                                return ucwords($data->status);
                            })
                            ->editColumn("created_at", function ($data) {
                                return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                            })
                            ->make(true);
        }
    }

    public function getAllPengajuanByNim(Mahasiswa $mahasiswa){
        return DataTables::of(PengajuanSuratPermohonanPengambilanMaterial::join('daftar_kelompok_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','daftar_kelompok_pengambilan_material.id_pengajuan')
                                    ->join('mahasiswa','mahasiswa.nim','=','daftar_kelompok_pengambilan_material.nim')
                                    ->where('daftar_kelompok_pengambilan_material.nim',$mahasiswa->nim)
                                    ->select('pengajuan_surat_permohonan_pengambilan_material.*'))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
    }

    public function createPengajuan()
    {   
        $mahasiswa = $this->generateMahasiswa();
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists()){
                return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
            }
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_permohonan_pengambilan_material',compact('mahasiswa'));
    }

    public function storePengajuan(PengajuanSuratPermohonanPengambilanMaterialRequest $request){
        $input = $request->all();
        $operator = Operator::where('bagian','subbagian pendidikan dan pengajaran')->where('status_aktif','aktif')->first();

        if(isset(Auth::user()->nim)){
            $input['nim'] = Auth::user()->nim;
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat permohonan pengambilan material.';
        } else if(isset(Auth::user()->id)){
            $input['id_operator'] = Auth::user()->id;
            $isiNotifikasi = 'Front office membuat pengajuan surat permohonan pengambilan material.';
        }

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        try{ 
            $pengajuan = PengajuanSuratPermohonanPengambilanMaterial::create($input);

            $pengajuan->daftarKelompok()->attach($request->mahasiswa);

            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-permohonan-pengambilan-material')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat permohonan pengambilan material gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan material berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function edit(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSurat){
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_permohonan_pengambilan_material',compact('pengajuanSurat','mahasiswa'));
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
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function destroy(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSurat){
        $this->deleteImage('file_rekomendasi_jurusan',$pengajuanSurat->file_rekomendasi_jurusan);
        $pengajuanSurat->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan material berhasil dihapus.');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratPermohonanPengambilanMaterial::findOrFail($request->id);
        $user = $pengajuan->suratPermohonanPengambilanMaterial->user;

        $isiNotifikasi = 'Verifikasi surat permohonan pengambilan material';

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratPermohonanPengambilanMaterial->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat permohonan pengambilan material.';
        }

        DB::beginTransaction();
        try{
            $pengajuan->update([
                'status'=>$status,
            ]);
            
            if($status == 'verifikasi kabag'){
                $user = User::where('jabatan','kabag tata usaha')->where('status_aktif','aktif')->first();
            }

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-permohonan-pengambilan-material')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat permohonan pengambilan material gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat permohonan pengambilan material berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function show(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSuratMaterial)
    {
        $pengajuan = collect($pengajuanSuratMaterial->load(['mahasiswa.prodi.jurusan','daftarKelompok','operator']));
        $pengajuan->put('status',ucwords($pengajuanSuratMaterial->status));
        $pengajuan->put('dibuat',$pengajuanSuratMaterial->created_at->isoFormat('D MMMM Y HH:mm:ss'));
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

    private function isSuratDiajukanExists(){
        $suratMaterial = PengajuanSuratPermohonanPengambilanMaterial::where('status','diajukan')->exists();
        if($suratMaterial){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat permohonan pengambilan material sementara diproses!');
            return false;
        }
        return true;
    }
}
