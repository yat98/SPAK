<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use Storage;
use App\User;
use DataTables;
use App\KodeSurat;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\SuratPermohonanSurvei;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratPermohonanSurvei;
use App\Http\Requests\SuratPermohonanSurveiRequest;

class SuratPermohonanSurveiController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        
        $countAllSurat = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','surat_permohonan_survei.id_pengajuan','=','pengajuan_surat_permohonan_survei.id')
                                                     ->whereIn('pengajuan_surat_permohonan_survei.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                     ->count();
        
        $countAllVerifikasi = PengajuanSuratPermohonanSurvei::where('status','verifikasi kasubag')
                                                                   ->count();

        return view('user.'.$this->segmentUser.'.surat_permohonan_survei',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratPermohonanSurvei::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','surat_permohonan_survei.id_pengajuan','=','pengajuan_surat_permohonan_survei.id')
                                                 ->whereNotIn('status',['diajukan'])
                                                 ->count();
             
        return view($this->segmentUser.'.surat_permohonan_survei',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratPermohonanSurvei::where('status','verifikasi kabag')
                                            ->count();

        $countAllSurat = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','surat_permohonan_survei.id_pengajuan','=','pengajuan_surat_permohonan_survei.id')
                                            ->where('status','selesai')
                                            ->count();
        
        $countAllTandaTangan = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','surat_permohonan_survei.id_pengajuan','=','pengajuan_surat_permohonan_survei.id')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.pimpinan.surat_permohonan_survei',compact('countAllVerifikasi','perPage','countAllSurat','countAllTandaTangan'));
    }

    public function getAllSurat(){
        $suratSurvei = PengajuanSuratPermohonanSurvei::join('surat_permohonan_survei','surat_permohonan_survei.id_pengajuan','=','pengajuan_surat_permohonan_survei.id')
                                    ->select('surat_permohonan_survei.nomor_surat','pengajuan_surat_permohonan_survei.*')
                                    ->with(['suratPermohonanSurvei.kodeSurat','mahasiswa']);

        if(isset(Auth::user()->id)){
            $suratSurvei = $suratSurvei->whereNotIn('status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran'){
                $suratSurvei = $suratSurvei->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratSurvei = $suratSurvei->where('status','selesai');
            }
        }

        return DataTables::of($suratSurvei)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
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

    public function getAllTandaTangan(){
        $suratSurvei =  PengajuanSuratPermohonanSurvei::join('surat_permohonan_survei','surat_permohonan_survei.id_pengajuan','=','pengajuan_surat_permohonan_survei.id')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('nip',Auth::user()->nip)
                                    ->select('surat_permohonan_survei.nomor_surat','pengajuan_surat_permohonan_survei.*')
                                    ->with(['mahasiswa','suratPermohonanSurvei.kodeSurat']);
                                   
        return DataTables::of($suratSurvei)
                                    ->addColumn('aksi', function ($data) {
                                        return $data->id;
                                    })
                                    ->addColumn('waktu_pengajuan', function ($data) {
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

    public function show(SuratPermohonanSurvei $suratSurvei)
    {
        $surat = collect($suratSurvei->load(['pengajuanSuratPermohonanSurvei.mahasiswa.prodi.jurusan','kodeSurat','user','pengajuanSuratPermohonanSurvei.operator']));
        $surat->put('status',ucwords($suratSurvei->pengajuanSuratPermohonanSurvei->status));
        $surat->put('tahun',$suratSurvei->created_at->isoFormat('Y'));
        $surat->put('dibuat',$suratSurvei->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratSurvei->pengajuanSuratPermohonanSurvei->file_rekomendasi_jurusan));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratSurvei->pengajuanSuratPermohonanSurvei->file_rekomendasi_jurusan)[0]);
        return $surat->toJson();
    }

    public function progress(PengajuanSuratPermohonanSurvei $pengajuanSurat){
        $pengajuan = $pengajuanSurat->load(['suratPermohonanSurvei.user','mahasiswa']);
        $data = collect($pengajuan);
        $data->put('status',ucwords($pengajuanSurat->status));

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuanSurat->suratPermohonanSurvei->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalSelesai);
        }else if($pengajuan->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSurat->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalDitolak);
        }else{
            $tanggal = $pengajuanSurat->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggal);
        }

        return $data->toJson();
    }

    public function createSurat(PengajuanSuratPermohonanSurvei $pengajuanSurat)
    {
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-survei');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganPendidikanDanPengajaran();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view($this->segmentUser.'.tambah_surat_permohonan_survei',compact('userList','kodeSurat','nomorSuratBaru','userList','pengajuanSurat'));
    }

    public function storeSurat(Request $request)
    {
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat',
            'nip'=>'required',
        ]);
            
        $pengajuanSuratSurvei = PengajuanSuratPermohonanSurvei::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag pendidikan dan pengajaran')
                      ->first();
                      
        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSuratSurvei->id;

        DB::beginTransaction();
        try{
            SuratPermohonanSurvei::create($input);

            $pengajuanSuratSurvei->update([
                'status'=>'verifikasi kasubag',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>'Verifikasi surat permohonan survei mahasiswa dengan nama '.$pengajuanSuratSurvei->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-permohonan-survei')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan survei gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat permohonan survei berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratPermohonanSurvei $pengajuanSurat){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSurat->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSurat->nim,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>'Pengajuan surat permohonan survei ditolak.',
                'link_notifikasi'=>url('mahasiswa/surat-permohonan-survei')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat permohonan survei gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan survei berhasil ditolak');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-permohonan-survei');
        }

        $suratSurvei = SuratPermohonanSurvei::findOrFail($request->id);
        
        $suratSurvei->pengajuanSuratPermohonanSurvei->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::create([
            'nim'=>$suratSurvei->pengajuanSuratPermohonanSurvei->nim,
            'judul_notifikasi'=>'Surat Permohonan Survei',
            'isi_notifikasi'=>'Surat permohonan survei telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-permohonan-survei')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat permohonan survei berhasil');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function cetak(SuratPermohonanSurvei $suratSurvei){
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratSurvei->pengajuanSuratPermohonanSurvei->nim){
                abort(404);
            }

            if($suratLulus->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat permohonan survei sebanyak 3 kali.');
                return redirect('mahasiswa/surat-permohonan-survei');
            }
        }

        $data = $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->nim.' - '.$suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);
        
        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratSurvei->jumlah_cetak;
                $suratSurvei->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }      
        }
        
        $pdf = PDF::loadview('surat.surat_permohonan_survei',compact('suratSurvei','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-permohonan_survei'.' - '.$suratSurvei->created_at->format('dmY-Him').'.pdf');
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
