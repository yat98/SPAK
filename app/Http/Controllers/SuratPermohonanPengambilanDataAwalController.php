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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\SuratPermohonanPengambilanDataAwal;
use App\PengajuanSuratPermohonanPengambilanDataAwal;
use App\Http\Requests\SuratPermohonanPengambilanDataAwalRequest;

class SuratPermohonanPengambilanDataAwalController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        
        $countAllSurat = SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','surat_permohonan_pengambilan_data_awal.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_data_awal.id')
                                                     ->whereIn('pengajuan_surat_permohonan_pengambilan_data_awal.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                     ->count();
        
        $countAllVerifikasi = PengajuanSuratPermohonanPengambilanDataAwal::where('status','verifikasi kasubag')
                                                                   ->count();

        return view('user.'.$this->segmentUser.'.surat_permohonan_pengambilan_data_awal',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratPermohonanPengambilanDataAwal::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','surat_permohonan_pengambilan_data_awal.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_data_awal.id')
                                                 ->whereNotIn('status',['diajukan'])
                                                 ->count();

        return view($this->segmentUser.'.surat_permohonan_pengambilan_data_awal',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratPermohonanPengambilanDataAwal::where('status','verifikasi kabag')
                                            ->count();

        $countAllSurat = SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','surat_permohonan_pengambilan_data_awal.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_data_awal.id')
                                            ->where('status','selesai')
                                            ->count();
        
        $countAllTandaTangan = SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','surat_permohonan_pengambilan_data_awal.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_data_awal.id')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.pimpinan.surat_permohonan_pengambilan_data_awal',compact('countAllVerifikasi','perPage','countAllSurat','countAllTandaTangan'));
    }

    public function getAllSurat(){
        $suratDataAwal = PengajuanSuratPermohonanPengambilanDataAwal::join('surat_permohonan_pengambilan_data_awal','surat_permohonan_pengambilan_data_awal.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_data_awal.id')
                                    ->select('surat_permohonan_pengambilan_data_awal.nomor_surat','pengajuan_surat_permohonan_pengambilan_data_awal.*')
                                    ->with(['suratPermohonanPengambilanDataAwal.kodeSurat','mahasiswa']);

        if(isset(Auth::user()->id)){
            $suratDataAwal = $suratDataAwal->whereNotIn('status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran'){
                $suratDataAwal = $suratDataAwal->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratDataAwal = $suratDataAwal->where('status','selesai');
            }
        }

        return DataTables::of($suratDataAwal)
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
        $suratDataAwal =  PengajuanSuratPermohonanPengambilanDataAwal::join('surat_permohonan_pengambilan_data_awal','surat_permohonan_pengambilan_data_awal.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_data_awal.id')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('nip',Auth::user()->nip)
                                    ->select('surat_permohonan_pengambilan_data_awal.nomor_surat','pengajuan_surat_permohonan_pengambilan_data_awal.*')
                                    ->with(['mahasiswa','suratPermohonanPengambilanDataAwal.kodeSurat']);
                                   
        return DataTables::of($suratDataAwal)
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

    public function show(SuratPermohonanPengambilanDataAwal $suratDataAwal)
    {
        $surat = collect($suratDataAwal->load(['pengajuanSuratPermohonanPengambilanDataAwal.mahasiswa.prodi.jurusan','pengajuanSuratPermohonanPengambilanDataAwal.operator','kodeSurat','user']));

        $surat->put('status',ucwords($suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->status));
        $surat->put('dibuat',$suratDataAwal->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $surat->put('tahun',$suratDataAwal->created_at->isoFormat('Y'));
        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->file_rekomendasi_jurusan));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->file_rekomendasi_jurusan)[0]);
    
        return $surat->toJson();
    }

    public function progress(PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSurat){
        $pengajuan = $pengajuanSurat->load(['suratPermohonanPengambilanDataAwal.user','mahasiswa']);
        $data = collect($pengajuan);
        $data->put('status',ucwords($pengajuanSurat->status));

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuanSurat->suratPermohonanPengambilanDataAwal->updated_at->isoFormat('D MMMM Y HH:mm:ss');
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

    public function createSurat(PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSurat)
    {
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganPendidikanDanPengajaran();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view($this->segmentUser.'.tambah_surat_permohonan_pengambilan_data_awal',compact('userList','kodeSurat','nomorSuratBaru','userList','pengajuanSurat'));
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
            
        $pengajuanSuratDataAwal = PengajuanSuratPermohonanPengambilanDataAwal::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag pendidikan dan pengajaran')
                      ->first();
                      
        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSuratDataAwal->id;

        DB::beginTransaction();
        try{
            SuratPermohonanPengambilanDataAwal::create($input);

            $pengajuanSuratDataAwal->update([
                'status'=>'verifikasi kasubag',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>'Verifikasi surat permohonan pengambilan data awal mahasiswa dengan nama '.$pengajuanSuratDataAwal->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-permohonan-pengambilan-data-awal')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan pengambilan data awal gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan data awal berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSurat){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSurat->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSurat->nim,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>'Pengajuan surat permohonan pengambilan data awal ditolak.',
                'link_notifikasi'=>url('mahasiswa/surat-permohonan-pengambilan-data-awal')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat permohonan pengambilan data awal gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan data awal berhasil ditolak');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
        }
        $suratDataAwal = SuratPermohonanPengambilanDataAwal::findOrFail($request->id);

        $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::create([
            'nim'=>$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->nim,
            'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
            'isi_notifikasi'=>'Surat permohonan pengambilan data awal telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-permohonan-pengambilan-data-awal')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat permohonan pengambilan data awal berhasil');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function cetak(SuratPermohonanPengambilanDataAwal $suratDataAwal){
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->nim){
                abort(404);
            }

            if($suratDataAwal->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat permohonan pengambilan data awal sebanyak 3 kali.');
                return redirect('mahasiswa/surat-permohonan-pengambilan-data-awal');
            }
        }

        $data = $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->nim.' - '.$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);
        
        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratDataAwal->jumlah_cetak;
                $suratDataAwal->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }      
        }

        $pdf = PDF::loadview('surat.surat_permohonan_pengambilan_data_awal',compact('suratDataAwal','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-permohonan-pengambilan-data-awal'.' - '.$suratDataAwal->created_at->format('dmY-Him').'.pdf');
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
