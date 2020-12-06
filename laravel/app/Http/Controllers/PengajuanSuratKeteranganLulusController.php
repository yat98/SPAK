<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Storage;
use App\User;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratKeteranganLulus;
use App\Http\Requests\PengajuanSuratKeteranganLulusRequest;

class PengajuanSuratKeteranganLulusController extends Controller
{
    public function indexMahasiswa()
    {
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratKeteranganLulus::where('nim',Auth::user()->nim)
                                                            ->count();
        
        return view($this->segmentUser.'.surat_keterangan_lulus',compact('countAllPengajuan','perPage'));
    }

    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratKeteranganLulus::where('pengajuan_surat_keterangan_lulus.nim',Auth::user()->nim)
                                    ->select('mahasiswa.nama','pengajuan_surat_keterangan_lulus.*','mahasiswa.nim')
                                    ->join('mahasiswa','pengajuan_surat_keterangan_lulus.nim','=','mahasiswa.nim')
                                    ->with(['mahasiswa']))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
        } else if(isset(Auth::user()->id)){
            $pengajuanSurat;

            if(Auth::user()->bagian == 'front office'){
                $pengajuanSurat = PengajuanSuratKeteranganLulus::whereIn('status',['diajukan','ditolak'])
                                                                 ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian pendidikan dan pengajaran'){
                $pengajuanSurat = PengajuanSuratKeteranganLulus::whereIn('status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->select('mahasiswa.nama','pengajuan_surat_keterangan_lulus.*','mahasiswa.nim')
                                             ->join('mahasiswa','pengajuan_surat_keterangan_lulus.nim','=','mahasiswa.nim')
                                             ->with(['mahasiswa']);

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
        } else if(isset(Auth::user()->nip)){
            $pengajuanSurat = PengajuanSuratKeteranganLulus::select('mahasiswa.nama','pengajuan_surat_keterangan_lulus.*','mahasiswa.nim')
                                                        ->join('mahasiswa','pengajuan_surat_keterangan_lulus.nim','=','mahasiswa.nim')
                                                        ->join('surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan')
                                                        ->with(['mahasiswa','suratKeteranganLulus.kodeSurat']);
                                                        
            if (Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan_lulus.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan_lulus.status','verifikasi kabag');
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
        return DataTables::of(PengajuanSuratKeteranganLulus::where('pengajuan_surat_keterangan_lulus.nim',$mahasiswa->nim)
                                    ->select('mahasiswa.nama','pengajuan_surat_keterangan_lulus.*','mahasiswa.nim')
                                    ->join('mahasiswa','pengajuan_surat_keterangan_lulus.nim','=','mahasiswa.nim')
                                    ->with(['mahasiswa']))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
    }

    public function createPengajuan()
    {
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists()){
                return redirect($this->segmentUser.'/surat-keterangan-lulus');
            }
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_lulus');
        }else{
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_lulus',compact('mahasiswa'));
        }
    }

    public function storePengajuan(PengajuanSuratKeteranganLulusRequest $request)
    {
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;

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

        $operator = Operator::where('bagian','subbagian pendidikan dan pengajaran')->where('status_aktif','aktif')->first();
        
        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan lulus.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat keterangan lulus dengan nama mahasiswa '.$mahasiswa->nama;
        }
        
        DB::beginTransaction();
        try{ 
            PengajuanSuratKeteranganLulus::create($input);
            
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-keterangan-lulus')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat keterangan lulus gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan lulus berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function show(PengajuanSuratKeteranganLulus $pengajuanSuratLulus)
    {
        $pengajuan = collect($pengajuanSuratLulus->load('mahasiswa.prodi.jurusan','operator'));
        $pengajuan->put('status',ucwords($pengajuanSuratLulus->status));
        $pengajuan->put('dibuat',$pengajuanSuratLulus->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $pengajuan->put('tanggal_wisuda',$pengajuanSuratLulus->tanggal_wisuda->isoFormat('D MMMM Y'));
        $pengajuan->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$pengajuanSuratLulus->file_rekomendasi_jurusan));
        $pengajuan->put('file_berita_acara_ujian',asset('upload_berita_acara_ujian/'.$pengajuanSuratLulus->file_berita_acara_ujian));

        $pengajuan->put('nama_file_rekomendasi_jurusan',explode('.',$pengajuanSuratLulus->file_rekomendasi_jurusan)[0]);
        $pengajuan->put('nama_file_berita_acara_ujian',explode('.',$pengajuanSuratLulus->file_berita_acara_ujian)[0]);
        return $pengajuan->toJson();
    }

    public function edit(PengajuanSuratKeteranganLulus $pengajuanSurat)
    {   
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_keterangan_lulus',compact('mahasiswa','pengajuanSurat'));
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
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function destroy(PengajuanSuratKeteranganLulus $pengajuanSurat)
    {
        $this->deleteImage('file_rekomendasi_jurusan',$pengajuanSurat->file_rekomendasi_jurusan);
        $this->deleteImage('file_berita_acara_ujian',$pengajuanSurat->file_berita_acara_ujian);
        $pengajuanSurat->delete();;
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan lulus berhasil dihapus');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratKeteranganLulus::findOrFail($request->id);
        $user = $pengajuan->suratKeteranganLulus->user;

        $isiNotifikasi = 'Verifikasi surat keterangan lulus mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratKeteranganLulus->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat persetujuan pindah mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;
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
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-keterangan-lulus')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat keterangan lulus gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat keterangan lulus berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
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

    private function isSuratDiajukanExists(){
        $suratKeterangan = PengajuanSuratKeteranganLulus::where('nim',Auth::user()->nim)
                                ->where('status','diajukan')
                                ->exists();
        if($suratKeterangan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat keterangan lulus sementara diproses!');
            return false;
        }
        return true;
    }
}
