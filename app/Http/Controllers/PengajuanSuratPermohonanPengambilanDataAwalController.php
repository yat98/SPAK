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
use App\PengajuanSuratPermohonanPengambilanDataAwal;
use App\Http\Requests\PengajuanSuratPermohonanPengambilanDataAwalRequest;

class PengajuanSuratPermohonanPengambilanDataAwalController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratPermohonanPengambilanDataAwal::where('nim',Auth::user()->nim)
                                                                          ->count();
        
        return view($this->segmentUser.'.surat_permohonan_pengambilan_data_awal',compact('countAllPengajuan','perPage'));
    }

    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratPermohonanPengambilanDataAwal::where('pengajuan_surat_permohonan_pengambilan_data_awal.nim',Auth::user()->nim)
                                    ->join('mahasiswa','pengajuan_surat_permohonan_pengambilan_data_awal.nim','=','mahasiswa.nim')
                                    ->select('mahasiswa.nama','pengajuan_surat_permohonan_pengambilan_data_awal.*','mahasiswa.nim')
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
            $pengajuanSurat = PengajuanSuratPermohonanPengambilanDataAwal::join('mahasiswa','pengajuan_surat_permohonan_pengambilan_data_awal.nim','=','mahasiswa.nim')
                                ->select('mahasiswa.nama','pengajuan_surat_permohonan_pengambilan_data_awal.*','mahasiswa.nim')
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
            $pengajuanSurat = PengajuanSuratPermohonanPengambilanDataAwal::join('mahasiswa','pengajuan_surat_permohonan_pengambilan_data_awal.nim','=','mahasiswa.nim')
                                ->join('surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                                ->select('mahasiswa.nama','pengajuan_surat_permohonan_pengambilan_data_awal.*','mahasiswa.nim')
                                ->with(['mahasiswa','SuratPermohonanPengambilanDataAwal.kodeSurat']);

            if (Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_permohonan_pengambilan_data_awal.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_permohonan_pengambilan_data_awal.status','verifikasi kabag');
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

    public function createPengajuan(){
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists()){
                return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
            }
            return view($this->segmentUser.'.tambah_pengajuan_surat_permohonan_pengambilan_data_awal');
        }else{
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_permohonan_pengambilan_data_awal',compact('mahasiswa'));
        }
    }

    public function storePengajuan(PengajuanSuratPermohonanPengambilanDataAwalRequest $request){
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
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat permohonan pengambilan data awal.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat permohonan pengambilan data awal dengan nama mahasiswa '.$mahasiswa->nama;
        }
        
        DB::beginTransaction();
        try{ 
            PengajuanSuratPermohonanPengambilanDataAwal::create($input);
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagl','Pengajuan surat permohonan pengambilan data awal gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan data awal berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function edit(PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSurat)
    {
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_permohonan_pengambilan_data_awal',compact('pengajuanSurat','mahasiswa'));        
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
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function destroy(PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSurat)
    {
        $this->deleteImage('file_rekomendasi_jurusan',$pengajuanSurat->file_rekomendasi_jurusan);
        $pengajuanSurat->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan data awal berhasil dihapus');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratPermohonanPengambilanDataAwal::findOrFail($request->id);
        $user = $pengajuan->suratPermohonanPengambilanDataAwal->user;

        $isiNotifikasi = 'Verifikasi surat permohonan pengambilan data awal mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratPermohonanPengambilanDataAwal->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat permohonan pengambilan data awal mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;
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
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-permohonan-pengambilan-data-awal')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat permohonan pengambilan data awal gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat permohonan pengambilan data awal berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
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
        $pengajuan = collect($pengajuanSuratDataAwal->load('mahasiswa.prodi.jurusan','operator'));
        $pengajuan->put('status',ucwords($pengajuanSuratDataAwal->status));
        $pengajuan->put('dibuat',$pengajuanSuratDataAwal->created_at->isoFormat('D MMMM Y HH:mm:ss'));
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

    private function isSuratDiajukanExists(){
        $suratDataAwal = PengajuanSuratPermohonanPengambilanDataAwal::where('status','diajukan')->exists();
        if($suratDataAwal){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat permohonan pengambilan data awal sementara diproses!');
            return false;
        }
        return true;
    }
}
