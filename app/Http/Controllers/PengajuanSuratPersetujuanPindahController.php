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
use App\PengajuanSuratPersetujuanPindah;
use App\Http\Requests\PengajuanSuratPersetujuanPindahRequest;

class PengajuanSuratPersetujuanPindahController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $countAllPengajuan = PengajuanSuratPersetujuanPindah::where('nim',Auth::user()->nim)
                                                              ->count();
        return view($this->segmentUser.'.surat_persetujuan_pindah',compact('perPage','countAllPengajuan'));
    }

    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratPersetujuanPindah::where('pengajuan_surat_persetujuan_pindah.nim',Auth::user()->nim)
                                    ->select('mahasiswa.nama','pengajuan_surat_persetujuan_pindah.*','mahasiswa.nim')
                                    ->join('mahasiswa','pengajuan_surat_persetujuan_pindah.nim','=','mahasiswa.nim')
                                    ->with(['mahasiswa']))
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
            $pengajuanSurat;

            if(Auth::user()->bagian == 'front office'){
                $pengajuanSurat = PengajuanSuratPersetujuanPindah::whereIn('status',['diajukan','ditolak'])
                                                 ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian kemahasiswaan'){
                $pengajuanSurat = PengajuanSuratPersetujuanPindah::whereIn('status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->join('mahasiswa','pengajuan_surat_persetujuan_pindah.nim','=','mahasiswa.nim')
                                             ->select('mahasiswa.nama','pengajuan_surat_persetujuan_pindah.*','mahasiswa.nim')
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
            $pengajuanSurat = PengajuanSuratPersetujuanPindah::join('surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                                        ->select('surat_persetujuan_pindah.nomor_surat','pengajuan_surat_persetujuan_pindah.*')
                                                        ->with(['mahasiswa','suratPersetujuanPindah.kodeSurat']);
                                                        
            if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_persetujuan_pindah.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_persetujuan_pindah.status','verifikasi kabag');
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
                return redirect($this->segmentUser.'/surat-persetujuan-pindah');
            }
            return view($this->segmentUser.'.tambah_pengajuan_surat_persetujuan_pindah');
        }else{
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_persetujuan_pindah',compact('mahasiswa'));
        }
    }

    public function show(PengajuanSuratPersetujuanPindah $pengajuanSuratPindah){
        $pengajuan = collect($pengajuanSuratPindah->load(['mahasiswa','operator']));
        $pengajuan->put('status',ucwords($pengajuanSuratPindah->status)); 
        $pengajuan->put('dibuat',$pengajuanSuratPindah->created_at->isoFormat('D MMMM Y HH:mm:ss'));
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

    public function storePengajuan(PengajuanSuratPersetujuanPindahRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;

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

        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();

        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat persetujuan pindah.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat persetujuan pindah dengan nama mahasiswa '.$mahasiswa->nama;
        }

        DB::beginTransaction();
        try{
            PengajuanSuratPersetujuanPindah::create($input);
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Persetujuan Pindah',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat persetujuan pindah.',
                'link_notifikasi'=>url('operator/surat-persetujuan-pindah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat persetujuan pindah gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat persetujuan pindah berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function edit(PengajuanSuratPersetujuanPindah $pengajuanSurat)
    {   
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_persetujuan_pindah',compact('mahasiswa','pengajuanSurat'));
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
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function destroy(PengajuanSuratPersetujuanPindah $pengajuanSurat)
    {
        $this->deleteImage($pengajuanSurat->file_surat_keterangan_lulus_butuh);
        $this->deleteImage($pengajuanSurat->file_ijazah_terakhir);
        $this->deleteImage($pengajuanSurat->file_surat_rekomendasi_jurusan);
        $this->deleteImage($pengajuanSurat->file_surat_keterangan_bebas_perlengkapan_universitas);
        $this->deleteImage($pengajuanSurat->file_surat_keterangan_bebas_perlengkapan_fakultas);
        $this->deleteImage($pengajuanSurat->file_surat_keterangan_bebas_perpustakaan_universitas);
        $this->deleteImage($pengajuanSurat->file_surat_keterangan_bebas_perpustakaan_fakultas);
        $pengajuanSurat->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat persetujuan pindah berhasil dihapus');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratPersetujuanPindah::findOrFail($request->id);
        $user = $pengajuan->suratPersetujuanPindah->user;

        $isiNotifikasi = 'Verifikasi surat persetujuan pindah mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratPersetujuanPindah->user->jabatan == 'kabag tata usaha'){
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
                'judul_notifikasi'=>'Surat Persetujuan Pindah',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-persetujuan-pindah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat persetujuan pindah gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat persetujuan pindah berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-persetujuan-pindah');
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

    private function isSuratDiajukanExists(){
        $suratPindah = PengajuanSuratPersetujuanPindah::where('status','diajukan')->exists();
        if($suratPindah){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat persetujuan pindah sementara diproses!');
            return false;
        }
        return true;
    }
}
