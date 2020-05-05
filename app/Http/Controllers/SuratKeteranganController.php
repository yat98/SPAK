<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use Exception;
use App\KodeSurat;
use App\Mahasiswa;
use App\SuratTugas;
use App\StatusMahasiswa;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SuratKeteranganRequest;

class SuratKeteranganController extends Controller
{
    public function show(SuratKeterangan $suratKeterangan){
        $surat = collect($suratKeterangan->load(['kodeSurat','pengajuanSuratKeterangan.mahasiswa.prodi.jurusan','pengajuanSuratKeterangan.tahunAkademik','user']));
        $tanggal = $suratKeterangan->created_at->format('d M Y - H:i:m');
        $kode = explode('/',$suratKeterangan->kodeSurat->kode_surat);
        $surat->put('created',$tanggal);
        $surat->put('kode',$kode[0].'.4/'.$kode[1]);
        return($surat->toJson());
    }

    public function progress(PengajuanSuratKeterangan $pengajuanSuratKeterangan){
        $pengajuan = $pengajuanSuratKeterangan->load(['suratKeterangan.user','mahasiswa']);
        $data = collect($pengajuan);
        $tanggalDiajukan = $pengajuan->created_at->isoFormat('D MMMM Y - HH:m:s');
        $data->put('tanggal_diajukan',$tanggalDiajukan);

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuan->suratKeterangan->created_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_selesai',$tanggalSelesai);
        }else if($pengajuan->status == 'ditolak'){
            $tanggalDitolak = $pengajuan->updated_at->isoFormat('D MMMM Y - HH:m:s');
            $data->put('tanggal_ditolak',$tanggalDitolak);
        }
        return $data->toJson();
    }
}
