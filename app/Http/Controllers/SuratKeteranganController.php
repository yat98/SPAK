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
        $suratKeterangan = SuratKeterangan::join('pengajuan_surat_keterangan','pengajuan_surat_keterangan.id','=','surat_keterangan.id_pengajuan')
                                    ->join('mahasiswa','pengajuan_surat_keterangan.nim','=','mahasiswa.nim')
                                    ->join('tahun_akademik','pengajuan_surat_keterangan.id_tahun_akademik','=','tahun_akademik.id')
                                    ->join('kode_surat','surat_keterangan.id_kode_surat','=','kode_surat.id')
                                    ->where('pengajuan_surat_keterangan.jenis_surat','surat keterangan aktif kuliah')
                                    ->select('surat_keterangan.*','mahasiswa.nim','mahasiswa.nama','pengajuan_surat_keterangan.status','tahun_akademik.*','kode_surat.*');

        if(isset(Auth::user()->id)){
            $suratKeterangan = $suratKeterangan->whereNotIn('pengajuan_surat_keterangan.status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratKeterangan = $suratKeterangan->whereIn('pengajuan_surat_keterangan.status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } elseif(Auth::user()->jabatan == 'kabag tata usaha'){
                $suratKeterangan = $suratKeterangan->whereIn('pengajuan_surat_keterangan.status',['selesai','menunggu tanda tangan']);
            } else{
                $suratKeterangan = $suratKeterangan->whereIn('pengajuan_surat_keterangan.status',['selesai']);
            }
        }
        
        return DataTables::of($suratKeterangan)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->editColumn("nama", function ($data) {
                            return ucwords($data->nama);
                        })
                        ->editColumn("semester", function ($data) {
                            return ucwords($data->semester);
                        })
                        ->make(true);
    }

    public function getAllTandaTanganKeteranganAktif(){
        $suratKeterangan = SuratKeterangan::join('pengajuan_surat_keterangan','pengajuan_surat_keterangan.id','=','surat_keterangan.id_pengajuan')
                                    ->join('mahasiswa','pengajuan_surat_keterangan.nim','=','mahasiswa.nim')
                                    ->join('tahun_akademik','pengajuan_surat_keterangan.id_tahun_akademik','=','tahun_akademik.id')
                                    ->join('kode_surat','surat_keterangan.id_kode_surat','=','kode_surat.id')
                                    ->where('pengajuan_surat_keterangan.jenis_surat','surat keterangan aktif kuliah')
                                    ->select('surat_keterangan.*','mahasiswa.nim','mahasiswa.nama','pengajuan_surat_keterangan.status','tahun_akademik.*','kode_surat.*')
                                    ->where('pengajuan_surat_keterangan.status','menunggu tanda tangan')
                                    ->where('surat_keterangan.nip',Auth::user()->nip);
                                    
        return DataTables::of($suratKeterangan)
                                    ->addColumn('aksi', function ($data) {
                                        return $data->id;
                                    })
                                    ->editColumn("status", function ($data) {
                                        return ucwords($data->status);
                                    })
                                    ->editColumn("created_at", function ($data) {
                                        return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                                    })
                                    ->editColumn("nama", function ($data) {
                                        return ucwords($data->nama);
                                    })
                                    ->editColumn("semester", function ($data) {
                                        return ucwords($data->semester);
                                    })
                                    ->make(true);                            
    }
}
