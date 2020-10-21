<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use DataTables;
use App\KodeSurat;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\SuratKeteranganLulus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratKeteranganLulus;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SuratKeteranganLulusRequest;

class SuratKeteranganLulusController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        
        $countAllSurat = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','surat_keterangan_lulus.id_pengajuan','=','pengajuan_surat_keterangan_lulus.id')
                                                 ->whereIn('pengajuan_surat_keterangan_lulus.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                 ->count();
        
        $countAllVerifikasi = PengajuanSuratKeteranganLulus::where('status','verifikasi kasubag')
                                                             ->count();

        return view('user.'.$this->segmentUser.'.surat_keterangan_lulus',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratKeteranganLulus::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','surat_keterangan_lulus.id_pengajuan','=','pengajuan_surat_keterangan_lulus.id')
                                                 ->whereNotIn('status',['diajukan'])
                                                 ->count();
             
        return view($this->segmentUser.'.surat_keterangan_lulus',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratKeteranganLulus::where('status','verifikasi kabag')
                                            ->count();

        $countAllSurat = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','surat_keterangan_lulus.id_pengajuan','=','pengajuan_surat_keterangan_lulus.id')
                                            ->where('status','selesai')
                                            ->count();
        
        $countAllTandaTangan = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','surat_keterangan_lulus.id_pengajuan','=','pengajuan_surat_keterangan_lulus.id')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.pimpinan.surat_keterangan_lulus',compact('countAllVerifikasi','perPage','countAllSurat','countAllTandaTangan'));
    }

    public function getAllSurat(){
        $suratLulus = PengajuanSuratKeteranganLulus::join('surat_keterangan_lulus','surat_keterangan_lulus.id_pengajuan','=','pengajuan_surat_keterangan_lulus.id')
                                    ->select('surat_keterangan_lulus.nomor_surat','pengajuan_surat_keterangan_lulus.*')
                                    ->with(['suratKeteranganLulus.kodeSurat','mahasiswa']);

        if(isset(Auth::user()->id)){
            $suratLulus = $suratLulus->whereNotIn('pengajuan_surat_keterangan_lulus.status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran'){
                $suratLulus = $suratLulus->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratLulus = $suratLulus->where('status','selesai');
            }
        }

        return DataTables::of($suratLulus)
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
        $suratLulus =  PengajuanSuratKeteranganLulus::join('surat_keterangan_lulus','surat_keterangan_lulus.id_pengajuan','=','pengajuan_surat_keterangan_lulus.id')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('surat_keterangan_lulus.nip',Auth::user()->nip)
                                    ->select('surat_keterangan_lulus.nomor_surat','pengajuan_surat_keterangan_lulus.*')
                                    ->with(['mahasiswa','suratKeteranganLulus.kodeSurat']);
                                    
        return DataTables::of($suratLulus)
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

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-lulus');
        }

        $suratLulus = SuratKeteranganLulus::findOrFail($request->id);
        
        $suratLulus->pengajuanSuratKeteranganLulus->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::create([
            'nim'=>$suratLulus->pengajuanSuratKeteranganLulus->nim,
            'judul_notifikasi'=>'Surat Keterangan Lulus',
            'isi_notifikasi'=>'Surat keterangan lulus telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-keterangan-lulus')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat keterangan lulus berhasil');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratKeteranganLulus $pengajuanSurat){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSurat->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSurat->nim,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>'Pengajuan surat keterangan lulus ditolak.',
                'link_notifikasi'=>url('mahasiswa/surat-keterangan-lulus')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat keterangan kelakuan baik gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan lulus berhasil ditolak');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function show(SuratKeteranganLulus $suratLulus)
    {
        $surat = collect($suratLulus->load(['pengajuanSuratKeteranganLulus.mahasiswa.prodi.jurusan','kodeSurat','user','pengajuanSuratKeteranganLulus.operator']));
        $surat->put('dibuat',$suratLulus->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $surat->put('status',ucwords($suratLulus->pengajuanSuratKeteranganLulus->status));
        $surat->put('tahun',ucwords($suratLulus->pengajuanSuratKeteranganLulus->created_at->isoFormat('Y')));

        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratLulus->pengajuanSuratKeteranganLulus->file_rekomendasi_jurusan));
        $surat->put('file_berita_acara_ujian',asset('upload_berita_acara_ujian/'.$suratLulus->pengajuanSuratKeteranganLulus->file_berita_acara_ujian));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratLulus->pengajuanSuratKeteranganLulus->file_rekomendasi_jurusan)[0]);
        $surat->put('nama_file_berita_acara_ujian',explode('.',$suratLulus->pengajuanSuratKeteranganLulus->file_berita_acara_ujian)[0]);

        return $surat->toJson();
    }

    public function progress(PengajuanSuratKeteranganLulus $pengajuanSurat){
        $pengajuan = $pengajuanSurat->load(['suratKeteranganLulus.user','mahasiswa']);
        $data = collect($pengajuan);
        $data->put('status',ucwords($pengajuanSurat->status));

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuanSurat->suratKeteranganLulus->updated_at->isoFormat('D MMMM Y HH:mm:ss');
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
    
    public function createSurat(PengajuanSuratKeteranganLulus $pengajuanSurat)
    {
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-keterangan-lulus');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganPendidikanDanPengajaran();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view($this->segmentUser.'.tambah_surat_keterangan_lulus',compact('userList','kodeSurat','nomorSuratBaru','userList','pengajuanSurat'));
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

        $pengajuanSuratLulus = PengajuanSuratKeteranganLulus::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag pendidikan dan pengajaran')
                      ->first();

        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSuratLulus->id;

        DB::beginTransaction();
        try{
            SuratKeteranganLulus::create($input);

            $pengajuanSuratLulus->update([
                'status'=>'verifikasi kasubag',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>'Verifikasi surat keterangan lulus mahasiswa dengan nama '.$pengajuanSuratLulus->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-keterangan-lulus')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan lulus gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat keterangan lulus berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function cetak(SuratketeranganLulus $suratLulus){
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratLulus->pengajuanSuratKeteranganLulus->nim){
                abort(404);
            }

            if($suratLulus->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat keterangan lulus sebanyak 3 kali.');
                return redirect('mahasiswa/surat-keterangan-lulus');
            }
        }
        
        $data = $suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->nim.' - '.$suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);

        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratLulus->jumlah_cetak;
                $suratLulus->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }      
        }

        $pdf = PDF::loadview('surat.surat_keterangan_lulus',compact('suratLulus','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-keterangan-lulus'.' - '.$suratLulus->created_at->format('dmY-Him').'.pdf');
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
