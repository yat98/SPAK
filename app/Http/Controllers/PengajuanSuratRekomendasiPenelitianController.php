<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratRekomendasiPenelitian;
use App\Http\Requests\PengajuanSuratRekomendasiPenelitianRequest;

class PengajuanSuratRekomendasiPenelitianController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratRekomendasiPenelitian::where('nim',Auth::user()->nim)
                                                                  ->count();
        
        return view($this->segmentUser.'.surat_rekomendasi_penelitian',compact('countAllPengajuan','perPage'));
    }

    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratRekomendasiPenelitian::where('pengajuan_surat_rekomendasi_penelitian.nim',Auth::user()->nim)
                                    ->join('mahasiswa','pengajuan_surat_rekomendasi_penelitian.nim','=','mahasiswa.nim')
                                    ->select('mahasiswa.nama','pengajuan_surat_rekomendasi_penelitian.*','mahasiswa.nim')
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
            $pengajuanSurat = PengajuanSuratRekomendasiPenelitian::join('mahasiswa','pengajuan_surat_rekomendasi_penelitian.nim','=','mahasiswa.nim')
                                ->select('mahasiswa.nama','pengajuan_surat_rekomendasi_penelitian.*','mahasiswa.nim')
                                ->with(['mahasiswa']);

            if(Auth::user()->bagian == 'front office'){
                $pengajuanSurat = $pengajuanSurat->whereIn('status',['diajukan','ditolak'])
                                                 ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian pendidikan dan pengajaran'){
                $pengajuanSurat = $pengajuanSurat->whereIn('status',['diajukan','ditolak']);
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
        } else if(isset(Auth::user()->nip)){
            $pengajuanSurat = PengajuanSuratRekomendasiPenelitian::join('surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                ->join('mahasiswa','pengajuan_surat_rekomendasi_penelitian.nim','=','mahasiswa.nim')
                                ->select('mahasiswa.nama','pengajuan_surat_rekomendasi_penelitian.*','mahasiswa.nim')
                                ->with(['mahasiswa','suratRekomendasiPenelitian.kodeSurat']);

            if (Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_rekomendasi_penelitian.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_rekomendasi_penelitian.status','verifikasi kabag');
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
        return DataTables::of(PengajuanSuratRekomendasiPenelitian::where('pengajuan_surat_rekomendasi_penelitian.nim',$mahasiswa->nim)
                                    ->join('mahasiswa','pengajuan_surat_rekomendasi_penelitian.nim','=','mahasiswa.nim')
                                    ->select('mahasiswa.nama','pengajuan_surat_rekomendasi_penelitian.*','mahasiswa.nim')
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

    public function createPengajuan(){
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists()){
                return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
            }
            return view($this->segmentUser.'.tambah_pengajuan_surat_rekomendasi_penelitian');
        }else{
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_rekomendasi_penelitian',compact('mahasiswa'));
        }
    }

    public function storePengajuan(PengajuanSuratRekomendasiPenelitianRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        $operator = Operator::where('bagian','subbagian pendidikan dan pengajaran')->where('status_aktif','aktif')->first();

        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat rekomendasi penelitian.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat rekomendasi penelitian dengan nama mahasiswa '.$mahasiswa->nama;
        }

        DB::beginTransaction();
        try{ 
            PengajuanSuratRekomendasiPenelitian::create($input);
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat rekomendasi penelitian gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi penelitian berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function edit(PengajuanSuratRekomendasiPenelitian $pengajuanSurat)
    {
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_rekomendasi_penelitian',compact('pengajuanSurat','mahasiswa'));        
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
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function destroy(PengajuanSuratRekomendasiPenelitian $pengajuanSurat)
    {
        $this->deleteImage('file_rekomendasi_jurusan',$pengajuanSurat->file_rekomendasi_jurusan);
        $pengajuanSurat->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi penelitian berhasil dihapus');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratRekomendasiPenelitian::findOrFail($request->id);
        $user = $pengajuan->suratRekomendasiPenelitian->user;

        $isiNotifikasi = 'Verifikasi surat rekomendasi penelitian mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratRekomendasiPenelitian->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat rekomendasi penelitian mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;
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
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat rekomendasi penelitian gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat rekomendasi penelitian berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
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
        $pengajuan = collect($pengajuanSuratPenelitian->load('mahasiswa.prodi.jurusan','operator'));
        $pengajuan->put('status',ucwords($pengajuanSuratPenelitian->status));
        $pengajuan->put('dibuat',$pengajuanSuratPenelitian->created_at->isoFormat('D MMMM Y HH:mm:ss'));
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

    private function isSuratDiajukanExists(){
        $suratPenelitian = PengajuanSuratRekomendasiPenelitian::where('status','diajukan')->exists();
        if($suratPenelitian){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat rekomendasi penelitian sementara diproses!');
            return false;
        }
        return true;
    }
}
