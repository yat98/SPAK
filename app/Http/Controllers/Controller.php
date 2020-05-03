<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Jurusan;
use App\Mahasiswa;
use App\ProgramStudi;
use App\TahunAkademik;
use App\SuratDispensasi;
use App\SuratKeterangan;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $perPage = 25;

    protected $segmentUser;

    public function __construct(){
        $this->segmentUser = request()->segment(1); 
    }

    protected function generateAngkatan(){
        $tahun = [];
        for($i = 2000;$i <= 2099;$i++){
            $tahun[$i] = $i;
        }
        return $tahun;
    }

    protected function generateTahunAkademikAktif(){
        $tahunAkademik = TahunAkademik::where('status_aktif','aktif')->get();
        $tahunAkademikList = [];
        foreach($tahunAkademik as $value){
            $tahunAkademikList[$value->id] = $value->tahun_akademik.' - '.ucwords($value->semester);
        }
        return $tahunAkademikList;
    }

    protected function generateAllTahunAkademik(){
        $tahunAkademik = TahunAkademik::all();
        $tahunAkademikList = [];
        foreach($tahunAkademik as $value){
            $tahunAkademikList[$value->id] = $value->tahun_akademik.' - '.ucwords($value->semester);
        }
        return $tahunAkademikList;
    }

    protected function generateProdi(){
        $prodi = ProgramStudi::all();
        $prodiList = [];
        foreach ($prodi as $value) {
            $prodiList[$value->id] = $value->strata.' - '.$value->nama_prodi;
        }
        return $prodiList;
    }

    protected function generateTahunAkademik(){
        $tahun = [];
        for($i = 2019; $i < 2099;$i++){ 
            $tahunAkhir = $i;
            $tahun[$i.'/'.++$tahunAkhir]="$i/".$tahunAkhir;
        }
        return $tahun; 
    }

    protected function generateMahasiswa(){
        $mahasiswa = Mahasiswa::all();
        $mahasiswaList = [];
        foreach ($mahasiswa as $mhs) {
            $mahasiswaList[$mhs->nim] = $mhs->nim.' - '.$mhs->nama;
        }
        return $mahasiswaList;
    }

    protected function generateNomorSurat($jenisSurat){
        $nomorSurat = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                        ->where('jenis_surat',$jenisSurat)
                        ->get();
        $nomorSuratList = [];
        foreach ($nomorSurat as $nmr) {
            $nomor = explode('/',$nmr->kodeSurat->kode_surat);
            $nomorSuratList[$nmr->nomor_surat] = 'B/'.$nmr->nomor_surat.'/'.$nomor[0].'.4/'.$nomor[1].'/'.$nmr->created_at->year;
        }
        return $nomorSuratList;
    }

    protected function setFlashData($flashType,$titleText,$text){
        Session::flash($flashType,$text);
        Session::flash($flashType.'-title',$titleText);
    }

    protected function isTandaTanganExists(){
        $nip = Session::get('nip');
        $tandaTangan = User::where('nip',$nip)->first();
        if($tandaTangan->tanda_tangan == null){
            $this->setFlashData('info','Tanda Tangan Kosong','Tambahkan tanda tangan anda terlebih dahulu!');
            return false;
        }
        return true;
    }
    
    protected function generateNomorSuratDispensasi(){
        $suratDispensasiList = SuratDispensasi::all();
        $nomorSuratList = [];
        foreach ($suratDispensasiList as $suratDispensasi) {
            if($suratDispensasi->user->jabatan == 'dekan'){
                $nomorSuratList[$suratDispensasi->nomor_surat] = $suratDispensasi->nomor_surat.'/'.$suratDispensasi->kodeSurat->kode_surat.'/'.$suratDispensasi->created_at->year;
            }else{
                $kodeSurat = explode('/',$suratDispensasi->kodeSurat->kode_surat);
                $nomorSuratList[$suratDispensasi->nomor_surat] = $suratDispensasi->nomor_surat.'/'.$kodeSurat[0].'.3/'.$kodeSurat[1].'/'.$suratDispensasi->created_at->year;
            }
        }
        return $nomorSuratList;
    }
}
