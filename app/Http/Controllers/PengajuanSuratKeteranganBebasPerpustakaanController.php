<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\PengajuanSuratKeteranganBebasPerpustakaan;

class PengajuanSuratKeteranganBebasPerpustakaanController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $pengajuanSuratPerpustakaanList = PengajuanSuratKeteranganBebasPerpustakaan::where('nim',Session::get('nim'))->paginate($perPage);
        $countAllPengajuan = $pengajuanSuratPerpustakaanList->count();
        $countPengajuanSuratPerpustakaan = $pengajuanSuratPerpustakaanList->count();
        return view($this->segmentUser.'.pengajuan_surat_keterangan_bebas_perpustakaan',compact('perPage','pengajuanSuratPerpustakaanList','countAllPengajuan','countPengajuanSuratPerpustakaan'));
    }
}
