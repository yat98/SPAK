<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\SuratMasuk;
use App\SuratTugas;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\SuratPengantarCuti;
use Illuminate\Http\Request;
use App\SuratKeteranganLulus;
use App\SuratPermohonanSurvei;
use App\SuratKegiatanMahasiswa;
use App\SuratPengantarBeasiswa;
use App\SuratPersetujuanPindah;
use App\SuratRekomendasiPenelitian;
use App\SuratPermohonanPengambilanDataAwal;
use App\SuratPermohonanPengambilanMaterial;

class LaporanController extends Controller
{
    public function index(){
        $tahun = $this->generateAngkatan();
        $jenisSurat = [
            'surat masuk'=>'Surat Masuk',
            'surat keluar'=>'Surat Keluar',
        ];
        return view('user.pegawai.laporan',compact('tahun','jenisSurat'));
    }

    public function post(Request $request){
        $this->validate($request,[
            'jenis_surat'=>'required',
            'tahun'=>'required'
        ]);
        $tahun = $request->tahun;
        if($request->jenis_surat == 'surat keluar'){
            if(Session::get('jabatan') == 'kasubag kemahasiswaan'){
                $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                            ->orderByDesc('surat_keterangan.updated_at')
                                            ->where('jenis_surat','surat keterangan aktif kuliah')
                                            ->whereYear('surat_keterangan.created_at',$request->tahun)
                                            ->get();
                $suratKeteranganKelakuanList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                                ->orderByDesc('surat_keterangan.updated_at')
                                                ->where('jenis_surat','surat keterangan kelakuan baik')
                                                ->whereYear('surat_keterangan.created_at',$request->tahun)
                                                ->get();
                $suratDispensasiList = SuratDispensasi::orderBy('status')
                                            ->whereYear('created_at',$request->tahun)
                                            ->get();
                $suratRekomendasiList = SuratRekomendasi::orderBy('status')
                                            ->whereYear('created_at',$request->tahun)
                                            ->get();
                $suratTugasList = SuratTugas::orderBy('status')
                                    ->whereYear('created_at',$request->tahun)
                                    ->get();
                $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                                ->orderByDesc('surat_persetujuan_pindah.created_at')
                                                ->orderByDesc('nomor_surat')
                                                ->whereYear('surat_persetujuan_pindah.created_at',$request->tahun)
                                                ->get();
                $suratCutiList = SuratPengantarCuti::orderByDesc('nomor_surat')
                                    ->whereYear('created_at',$request->tahun)
                                    ->get();
                $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')
                                        ->whereYear('created_at',$request->tahun)
                                        ->get();
                $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                        ->where('status','selesai')
                                        ->whereYear('surat_kegiatan_mahasiswa.created_at',$request->tahun)
                                        ->get();
                $pdf = PDF::loadview('laporan.surat_keluar_kemahasiswaan',compact('suratKeteranganAktifList','suratKeteranganKelakuanList','suratDispensasiList','suratRekomendasiList','suratTugasList','suratPersetujuanPindahList','suratCutiList','suratBeasiswaList','suratKegiatanList','tahun'))->setPaper('a4', 'potrait');
                return $pdf->stream('surat-keluar-kemahasiswaan'.' - '.date('Ymd').'.pdf');
            }else{
                $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                                    ->whereIn('status',['selesai'])
                                    ->whereYear('surat_keterangan_lulus.created_at',$request->tahun)
                                    ->orderBy('status')
                                    ->get();                         
                $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                        ->whereIn('status',['selesai'])
                                        ->whereYear('surat_permohonan_pengambilan_material.created_at',$request->tahun)
                                        ->get();
                $suratSurveiList =  SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                        ->whereIn('status',['selesai'])
                                        ->whereYear('surat_permohonan_survei.created_at',$request->tahun)
                                        ->get();
                $suratPenelitianList =  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                        ->whereIn('status',['selesai'])
                                        ->whereYear('surat_rekomendasi_penelitian.created_at',$request->tahun)
                                        ->get();
                $suratDataAwalList =  SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                                        ->whereIn('status',['selesai'])
                                        ->whereYear('surat_permohonan_pengambilan_data_awal.created_at',$request->tahun)
                                        ->get();
                $pdf = PDF::loadview('laporan.surat_keluar_pendidikan_dan_pengajaran',compact('suratLulusList','suratMaterialList','suratSurveiList','suratPenelitianList','suratDataAwalList','tahun'))->setPaper('a4', 'potrait');
                return $pdf->stream('surat-keluar-pendidikan-dan-pengajaran'.' - '.date('Ymd').'.pdf');
            }
        }else{
            if(Session::get('jabatan') == 'kasubag kemahasiswaan'){
                $suratMasukList = SuratMasuk::where('bagian','subbagian kemahasiswaan')->orderByDesc('created_at')->get();
                $pdf = PDF::loadview('laporan.surat_masuk_kemahasiswaan',compact('suratMasukList','tahun'))->setPaper('a4', 'potrait');
                return $pdf->stream('surat-masuk-kemahasiswaan'.' - '.date('Ymd').'.pdf');
            }else{
                $suratMasukList = SuratMasuk::whereNotIn('bagian',['subbagian kemahasiswaan'])->orderByDesc('created_at')->get();
                $pdf = PDF::loadview('laporan.surat_masuk_pendidikan_dan_pengajaran',compact('suratMasukList','tahun'))->setPaper('a4', 'potrait');
                return $pdf->stream('surat-masuk-pendidikan-dan-pengajaran'.' - '.date('Ymd').'.pdf');
            }
        }
    }
}
