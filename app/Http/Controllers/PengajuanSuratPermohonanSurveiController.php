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
use App\PengajuanSuratPermohonanSurvei;
use App\Http\Requests\PengajuanSuratPermohonanSurveiRequest;

class PengajuanSuratPermohonanSurveiController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratPermohonanSurvei::where('nim',Auth::user()->nim)
                                                            ->count();
        
        return view($this->segmentUser.'.surat_permohonan_survei',compact('countAllPengajuan','perPage'));
    }
    
    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratPermohonanSurvei::where('pengajuan_surat_permohonan_survei.nim',Auth::user()->nim)
                                    ->join('mahasiswa','pengajuan_surat_permohonan_survei.nim','=','mahasiswa.nim')
                                    ->select('mahasiswa.nama','pengajuan_surat_permohonan_survei.*','mahasiswa.nim')
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
            $pengajuanSurat = PengajuanSuratPermohonanSurvei::join('mahasiswa','pengajuan_surat_permohonan_survei.nim','=','mahasiswa.nim')
                                ->select('mahasiswa.nama','pengajuan_surat_permohonan_survei.*','mahasiswa.nim')
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
            $pengajuanSurat = PengajuanSuratPermohonanSurvei::join('mahasiswa','pengajuan_surat_permohonan_survei.nim','=','mahasiswa.nim')
                                ->join('surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                ->select('mahasiswa.nama','pengajuan_surat_permohonan_survei.*','mahasiswa.nim')
                                ->with(['mahasiswa','suratPermohonanSurvei.kodeSurat']);

            if (Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_permohonan_survei.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_permohonan_survei.status','verifikasi kabag');
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
        return DataTables::of(PengajuanSuratPermohonanSurvei::where('pengajuan_surat_permohonan_survei.nim',$mahasiswa->nim)
                                    ->join('mahasiswa','pengajuan_surat_permohonan_survei.nim','=','mahasiswa.nim')
                                    ->select('mahasiswa.nama','pengajuan_surat_permohonan_survei.*','mahasiswa.nim')
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

    public function show(PengajuanSuratPermohonanSurvei $pengajuanSuratSurvei)
    {
        $pengajuan = collect($pengajuanSuratSurvei->load('mahasiswa.prodi.jurusan','operator'));
        $pengajuan->put('status',ucwords($pengajuanSuratSurvei->status));
        $pengajuan->put('dibuat',$pengajuanSuratSurvei->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $pengajuan->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$pengajuanSuratSurvei->file_rekomendasi_jurusan));
        $pengajuan->put('nama_file_rekomendasi_jurusan',explode('.',$pengajuanSuratSurvei->file_rekomendasi_jurusan)[0]);

        return $pengajuan->toJson();
    }

    public function createPengajuan(){
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists()){
                return redirect($this->segmentUser.'/pengajuan/surat-permohonan-survei');
            }
            return view($this->segmentUser.'.tambah_pengajuan_surat_permohonan_survei');
        }else{
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_permohonan_survei',compact('mahasiswa'));
        }
    }

    public function storePengajuan(PengajuanSuratPermohonanSurveiRequest $request){
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
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat permohonan survei.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat permohonan survei dengan nama mahasiswa '.$mahasiswa->nama;
        }

        DB::beginTransaction();
        try{ 
            PengajuanSuratPermohonanSurvei::create($input);
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-permohonan-survei')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat permohonan survei gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan survei berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function edit(PengajuanSuratPermohonanSurvei $pengajuanSurat)
    {
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_permohonan_survei',compact('pengajuanSurat','mahasiswa'));        
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
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function destroy(PengajuanSuratPermohonanSurvei $pengajuanSurvei)
    {
        $this->deleteImage('file_rekomendasi_jurusan',$pengajuanSurvei->file_rekomendasi_jurusan);
        $pengajuanSurvei->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan survei berhasil dihapus');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratPermohonanSurvei::findOrFail($request->id);
        $user = $pengajuan->suratPermohonanSurvei->user;

        $isiNotifikasi = 'Verifikasi surat permohonan survei mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratPermohonanSurvei->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat permohonan survei mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;
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
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-permohonan-survei')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat permohonan survei gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat permohonan survei berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
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

    private function isSuratDiajukanExists(){
        $suratSurvei = PengajuanSuratPermohonanSurvei::where('status','diajukan')->exists();
        if($suratSurvei){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat permohonan survei sementara diproses!');
            return false;
        }
        return true;
    }
}
