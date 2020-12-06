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
use Illuminate\Support\Facades\Auth;
use App\SuratKeteranganBebasPerlengkapan;
use App\SuratKeteranganBebasPerpustakaan;
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
        if(Auth::user()->bagian == 'operator perpustakaan' || Auth::user()->jabatan == 'kepala perpustakaan'){
            $jenisSurat = [
                'surat keluar'=>'Surat Keluar',
            ];
        }
        if(isset(Auth::user()->id)){
            return view('operator.laporan',compact('tahun','jenisSurat'));
        }else{
            if(Auth::user()->jabatan == 'dekan' || Auth::user()->jabatan == 'wd1' || Auth::user()->jabatan == 'wd2' || Auth::user()->jabatan == 'wd3' || Auth::user()->jabatan == 'kabag tata usaha' || Auth::user()->jabatan == 'kepala perpustakaan'){
                $bagianList = [
                    'Subbagian Kemahasiswaan'=>'Subbagian Kemahasiswaan',
                    'Subbagian Pendidikan Dan Pengajaran'=>'Subbagian Pendidikan Dan Pengajaran',
                    'Subbagian Umum & BMN'=>'Subbagian Umum & BMN',
                    'Perpustakaan'=>'Perpustakaan',
                ];
                return view('user.pimpinan.laporan',compact('tahun','jenisSurat','bagianList'));
            }
            return view('user.pegawai.laporan',compact('tahun','jenisSurat'));
        }
    }

    public function post(Request $request){
        if(Auth::user()->jabatan == 'dekan' || Auth::user()->jabatan == 'wd1' || Auth::user()->jabatan == 'wd2' || Auth::user()->jabatan == 'wd3' || Auth::user()->jabatan == 'kabag tata usaha' || Auth::user()->jabatan == 'kepala perpustakaan'){
            $this->validate($request,[
                'bagian'=>'required',
                'jenis_surat'=>'required',
                'tahun'=>'required'
            ]);
        }else{
            $this->validate($request,[
                'jenis_surat'=>'required',
                'tahun'=>'required'
            ]);
        }
        $bagian = $request->bagian;
        $tahun = $request->tahun;
        if($request->jenis_surat == 'surat keluar'){
            if(Session::get('jabatan') == 'kasubag kemahasiswaan' || Session::get('jabatan') == 'subbagian kemahasiswaan' || $bagian == 'Subbagian Kemahasiswaan'){
                $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                            ->orderByDesc('nomor_surat')
                                            ->where('jenis_surat','surat keterangan aktif kuliah')
                                            ->whereYear('surat_keterangan.created_at',$request->tahun)
                                            ->get();

                $suratKeteranganKelakuanList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                                ->orderByDesc('nomor_surat')
                                                ->where('jenis_surat','surat keterangan kelakuan baik')
                                                ->whereYear('surat_keterangan.created_at',$request->tahun)
                                                ->get();

                $suratDispensasiList = SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                            ->orderByDesc('nomor_surat')                            
                                            ->whereYear('surat_dispensasi.created_at',$request->tahun)
                                            ->get();

                $suratRekomendasiList = SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                            ->orderByDesc('nomor_surat')                                  
                                            ->whereYear('surat_rekomendasi.created_at',$request->tahun)
                                            ->get();

                $suratTugasList = SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                    ->orderByDesc('nomor_surat')                                  
                                    ->whereYear('surat_tugas.created_at',$request->tahun)
                                    ->get();

                $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan')
                                                ->orderByDesc('nomor_surat')
                                                ->whereYear('surat_persetujuan_pindah.created_at',$request->tahun)
                                                ->get();

                $suratCutiList = SuratPengantarCuti::orderByDesc('nomor_surat')
                                    ->orderByDesc('nomor_surat')
                                    ->whereYear('created_at',$request->tahun)
                                    ->get();

                $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')
                                        ->orderByDesc('nomor_surat')
                                        ->whereYear('created_at',$request->tahun)
                                        ->get();

                $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan')
                                        ->orderByDesc('nomor_surat')
                                        ->whereYear('surat_kegiatan_mahasiswa.created_at',$request->tahun)
                                        ->get();

                $pdf = PDF::loadview('laporan.surat_keluar_kemahasiswaan',compact('suratKeteranganAktifList','suratKeteranganKelakuanList','suratDispensasiList','suratRekomendasiList','suratTugasList','suratPersetujuanPindahList','suratCutiList','suratBeasiswaList','suratKegiatanList','tahun'))->setPaper('a4', 'potrait');
                return $pdf->stream('surat-keluar-kemahasiswaan'.' - '.date('Ymd').'.pdf');
            }else if(Session::get('jabatan') == 'kasubag pendidikan dan pengajaran' || Session::get('jabatan') == 'subbagian pendidikan dan pengajaran' || $bagian == 'Subbagian Pendidikan Dan Pengajaran'){
                $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan')
                                    ->orderByDesc('nomor_surat')
                                    ->whereYear('surat_keterangan_lulus.created_at',$request->tahun)
                                    ->get();                         

                $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                        ->orderByDesc('nomor_surat')                                
                                        ->whereYear('surat_permohonan_pengambilan_material.created_at',$request->tahun)
                                        ->get();

                $suratSurveiList =  SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                        ->orderByDesc('nomor_surat')                                
                                        ->whereYear('surat_permohonan_survei.created_at',$request->tahun)
                                        ->get();

                $suratPenelitianList =  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                        ->orderByDesc('nomor_surat')                                
                                        ->whereYear('surat_rekomendasi_penelitian.created_at',$request->tahun)
                                        ->get();

                $suratDataAwalList =  SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                                        ->orderByDesc('nomor_surat')                                
                                        ->whereYear('surat_permohonan_pengambilan_data_awal.created_at',$request->tahun)
                                        ->get();

                $pdf = PDF::loadview('laporan.surat_keluar_pendidikan_dan_pengajaran',compact('suratLulusList','suratMaterialList','suratSurveiList','suratPenelitianList','suratDataAwalList','tahun'))->setPaper('a4', 'potrait');
                return $pdf->stream('surat-keluar-pendidikan-dan-pengajaran'.' - '.date('Ymd').'.pdf');
            }else if(Session::get('jabatan') == 'kasubag umum & bmn' || Session::get('jabatan') == 'subbagian umum & bmn' || $bagian == 'Subbagian Umum & BMN'){
                $suratPerlengkapanList = SuratKeteranganBebasPerlengkapan::join('pengajuan_surat_keterangan_bebas_perlengkapan','pengajuan_surat_keterangan_bebas_perlengkapan.id','=','surat_keterangan_bebas_perlengkapan.id_pengajuan')
                                            ->orderByDesc('nomor_surat')
                                            ->whereYear('surat_keterangan_bebas_perlengkapan.created_at',$request->tahun)
                                            ->get();

                $pdf = PDF::loadview('laporan.surat_keluar_umum_bmn',compact('suratPerlengkapanList','tahun'))->setPaper('a4', 'potrait');
                return $pdf->stream('surat-keluar-umum-bmn'.' - '.date('Ymd').'.pdf');
            }else if(Session::get('jabatan') == 'kepala perpustakaan' || Session::get('jabatan') == 'operator perpustakaan' || $bagian == 'Perpustakaan'){
                $suratPerpustakaanList = SuratKeteranganBebasPerpustakaan::join('pengajuan_surat_keterangan_bebas_perpustakaan','pengajuan_surat_keterangan_bebas_perpustakaan.id','=','surat_keterangan_bebas_perpustakaan.id_pengajuan')
                                            ->orderByDesc('nomor_surat')
                                            ->whereYear('surat_keterangan_bebas_perpustakaan.created_at',$request->tahun)
                                            ->get();

                $pdf = PDF::loadview('laporan.surat_keluar_perpustakaan',compact('suratPerpustakaanList','tahun'))->setPaper('a4', 'potrait');
                return $pdf->stream('surat-keluar-perpustakaan'.' - '.date('Ymd').'.pdf');
            }
        }else{
            $suratMasukList = SuratMasuk::orderByDesc('created_at')->get();
            $pdf = PDF::loadview('laporan.surat_masuk',compact('suratMasukList','tahun'))->setPaper('a4', 'potrait');
            return $pdf->stream('surat-masuk'.' - '.date('Ymd').'.pdf');
        }
    }
}
