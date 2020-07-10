<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SuratKeteranganBebasPerpustakaan;
use App\PengajuanSuratKeteranganBebasPerpustakaan;

class SuratKeteranganBebasPerpustakaanController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratPerpustakaan(['selesai']);
        $pengajuanSuratPerpustakaanList =  PengajuanSuratKeteranganBebasPerpustakaan::whereNotIn('status',['selesai'])
                                        ->orderByDesc('created_at')
                                        ->orderBy('status')
                                        ->paginate($perPage,['*'],'page_pengajuan');
        $suratPerpustakaanList =  SuratKeteranganBebasPerpustakaan::join('pengajuan_surat_keterangan_bebas_perpustakaan','pengajuan_surat_keterangan_bebas_perpustakaan.id','=','surat_keterangan_bebas_perpustakaan.id_pengajuan')
                            ->where('status','selesai')
                            ->orderBy('status')
                            ->paginate($perPage,['*'],'page');
        $countAllPengajuanSuratPerpustakaan = $pengajuanSuratPerpustakaanList->count();
        $countAllSuratPerpustakaan = $suratPerpustakaanList->count();
        $countPengajuanSuratPerpustakaan = $pengajuanSuratPerpustakaanList->count();
        $countSuratPerpustakaan = $suratPerpustakaanList->count();
        return view('user.'.$this->segmentUser.'.surat_keterangan_bebas_perpustakaan',compact('perPage','mahasiswa','pengajuanSuratPerpustakaanList','suratPerpustakaanList','countAllPengajuanSuratPerpustakaan','countAllSuratPerpustakaan','countPengajuanSuratPerpustakaan','countSuratPerpustakaan','nomorSurat'));
    }

    public function create(){
        return view($this->segmentUser.'.tambah_surat_keterangan_bebas_perpustakaan');
    }

    private function generateNomorSuratPerpustakaan($status){
        $suratPerpustakaanList  =  SuratKeteranganBebasPerpustakaan::join('pengajuan_surat_keterangan_bebas_perpustakaan','pengajuan_surat_keterangan_bebas_perpustakaan.id','=','surat_keterangan_bebas_perpustakaan.id_pengajuan')
                            ->whereIn('status',$status)
                            ->get();
        $nomorSuratList = [];
        foreach ($suratPerpustakaanList as $suratPerpustakaan) {
            $nomorSuratList[$suratPerpustakaan->nomor_surat] = 'B/'.$suratPerpustakaan->nomor_surat.'/'.$suratPerpustakaan->kodeSurat->kode_surat.'/'.$suratLulus->created_at->year;
        }
        return $nomorSuratList;
    }
}
