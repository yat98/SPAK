<?php

namespace App\Http\Controllers;

use Session;
use DataTables;
use App\SuratKeterangan;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\Auth;

class SuratKeteranganController extends Controller
{
    public function show(SuratKeterangan $suratKeterangan){
        $surat = collect($suratKeterangan->load(['kodeSurat','pengajuanSuratKeterangan.mahasiswa.prodi.jurusan','pengajuanSuratKeterangan.operator','pengajuanSuratKeterangan.tahunAkademik','user','operator']));
        $tanggal = $suratKeterangan->created_at->isoFormat('D MMMM Y HH:mm:ss');
        $surat->put('created_at',$tanggal);
        $surat->put('tahun',$suratKeterangan->created_at->isoFormat('Y'));
        $surat->put('status',ucwords($suratKeterangan->pengajuanSuratKeterangan->status));
        $surat->put('jenis_surat',ucwords($suratKeterangan->pengajuanSuratKeterangan->jenis_surat));
        return($surat->toJson());
    }

    public function progress(PengajuanSuratKeterangan $pengajuanSuratKeterangan){
        $pengajuan = $pengajuanSuratKeterangan->load(['suratKeterangan.user','mahasiswa']);
        $data = collect($pengajuan);
        $data->put('status',ucwords($pengajuanSuratKeterangan->status));

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuan->suratKeterangan->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalSelesai);
        }else if($pengajuan->status == 'ditolak'){
            $tanggalDitolak = $pengajuan->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalDitolak);
        }else{
            $tanggal = $pengajuan->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggal);
        }

        return $data->toJson();
    }

    public function getAllSuratKeteranganAktif(){
        $suratKeterangan = PengajuanSuratKeterangan::join('surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                    ->join('tahun_akademik','pengajuan_surat_keterangan.id_tahun_akademik','=','tahun_akademik.id')
                                    ->where('pengajuan_surat_keterangan.jenis_surat','surat keterangan aktif kuliah')
                                    ->select('surat_keterangan.nomor_surat','pengajuan_surat_keterangan.*','tahun_akademik.semester')
                                    ->with(['mahasiswa','suratKeterangan.kodeSurat','tahunAkademik']);
                                    
        if(isset(Auth::user()->id)){
            $suratKeterangan = $suratKeterangan->whereNotIn('pengajuan_surat_keterangan.status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratKeterangan = $suratKeterangan->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratKeterangan = $suratKeterangan->where('status','selesai');
            }
        }
        
        return DataTables::of($suratKeterangan)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("semester", function ($data) {
                            return ucwords($data->semester);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
    }

    public function getAllSuratKelakuanBaik(){
        $suratKeterangan = PengajuanSuratKeterangan::join('surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                    ->where('pengajuan_surat_keterangan.jenis_surat','surat keterangan kelakuan baik')
                                    ->select('surat_keterangan.nomor_surat','pengajuan_surat_keterangan.*')
                                    ->with(['mahasiswa','suratKeterangan.kodeSurat']);

        if(isset(Auth::user()->id)){
            $suratKeterangan = $suratKeterangan->whereNotIn('pengajuan_surat_keterangan.status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratKeterangan = $suratKeterangan->whereIn('pengajuan_surat_keterangan.status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratKeterangan = $suratKeterangan->where('pengajuan_surat_keterangan.status','selesai');
            }
        }
        
        return DataTables::of($suratKeterangan)
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

    public function getAllTandaTanganKeteranganAktif(){
        $suratKeterangan =  PengajuanSuratKeterangan::join('surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                    ->join('tahun_akademik','pengajuan_surat_keterangan.id_tahun_akademik','=','tahun_akademik.id')
                                    ->where('jenis_surat','surat keterangan aktif kuliah')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('surat_keterangan.nip',Auth::user()->nip)
                                    ->select('surat_keterangan.nomor_surat','pengajuan_surat_keterangan.*','tahun_akademik.semester')
                                    ->with(['mahasiswa','suratKeterangan.kodeSurat','tahunAkademik']);
                                    
        return DataTables::of($suratKeterangan)
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
                                    ->editColumn("semester", function ($data) {
                                        return ucwords($data->semester);
                                    })
                                    ->make(true);                            
    }

    public function getAllTandaTanganKelakuanBaik(){
        $suratKeterangan =  PengajuanSuratKeterangan::join('surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                    ->where('pengajuan_surat_keterangan.jenis_surat','surat keterangan kelakuan baik')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('surat_keterangan.nip',Auth::user()->nip)
                                    ->select('surat_keterangan.nomor_surat','pengajuan_surat_keterangan.*')
                                    ->with(['mahasiswa','suratKeterangan.kodeSurat']);
                                    
        return DataTables::of($suratKeterangan)
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
}
