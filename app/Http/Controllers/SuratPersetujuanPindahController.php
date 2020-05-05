<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SuratPersetujuanPindah;
use App\PengajuanSuratPersetujuanPindah;

class SuratPersetujuanPindahController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $suratPersetujuanPindahList = SuratPersetujuanPindah::orderByDesc('created_at')
                                        ->orderBy('status')
                                        ->paginate($perPage,['*'],'page');

        $pengajuanSuratPersetujuanList = PengajuanSuratPersetujuanPindah::whereNotIn('status',['selesai'])
                                                ->orderByDesc('created_at')
                                                ->orderBy('status')
                                                ->paginate($perPage,['*'],'page_pengajuan');
        
        $countAllSuratPersetujuanPindah = $suratPersetujuanPindahList->count();
        $countSuratPersetujuanPindah = $suratPersetujuanPindahList->count();

        $countPengajuanSuratPersetujuanPindah = $pengajuanSuratPersetujuanList->count();
        $countAllPengajuanSuratPersetujuanPindah = $pengajuanSuratPersetujuanList->count();

        $nomorSurat = [];

        return view('user.'.$this->segmentUser.'.surat_persetujuan_pindah',compact('perPage','nomorSurat','suratPersetujuanPindahList','pengajuanSuratPersetujuanList','countAllSuratPersetujuanPindah','countSuratPersetujuanPindah','countPengajuanSuratPersetujuanPindah','countAllPengajuanSuratPersetujuanPindah'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
