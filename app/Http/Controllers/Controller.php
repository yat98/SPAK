<?php

namespace App\Http\Controllers;

use Session;
use App\Jurusan;
use App\Mahasiswa;
use App\ProgramStudi;
use App\TahunAkademik;
use App\SuratKeterangan;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $perPage = 20;

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

    protected function generateTahunAkademikSemester(){
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

    protected function generateNomorSurat(){
        $nomorSurat = SuratKeterangan::all();
        $nomorSuratList = [];
        foreach ($nomorSurat as $nmr) {
            $nomorSuratList[$nmr->nomor_surat] = 'B/'.$nmr->nomor_surat.'/'.$nmr->kodeSurat->kode_surat.'/'.$nmr->created_at->year;
        }
        return $nomorSuratList;
    }

    protected function setFlashData($flashType,$titleText,$text){
        Session::flash($flashType,$text);
        Session::flash($flashType.'-title',$titleText);
    }
}
