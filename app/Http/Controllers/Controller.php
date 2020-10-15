<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Jurusan;
use App\KodeSurat;
use App\Mahasiswa;
use App\SuratTugas;
use App\ProgramStudi;
use App\TahunAkademik;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\SuratPengantarCuti;
use App\SuratKeteranganLulus;
use App\SuratPermohonanSurvei;
use App\SuratKegiatanMahasiswa;
use App\SuratPengantarBeasiswa;
use App\SuratPersetujuanPindah;
use App\SuratRekomendasiPenelitian;
use Illuminate\Support\Facades\Auth;
use App\SuratPermohonanPengambilanDataAwal;
use App\SuratPermohonanPengambilanMaterial;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $perPage = 25;
    protected $perPageDashboard = 5;

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

    protected function setFlashData($flashType,$titleText,$text){
        Session::flash($flashType,$text);
        Session::flash($flashType.'-title',$titleText);
    }

    protected function isTandaTanganExists(){
        $nip = Auth::user()->nip;
        $user = User::where('nip',$nip)->first();
        if($user->tanda_tangan == null){
            $this->setFlashData('info','Tanda Tangan Kosong','Tambahkan tanda tangan anda terlebih dahulu!');
            return false;
        }
        return true;
    }

    protected function isKodeSuratExists(){
        $kodeSurat = KodeSurat::where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }

    protected function generateNomorSuratBaru(){
        $nomorSurat[] = SuratKeterangan::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratDispensasi::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratRekomendasi::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratTugas::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratPersetujuanPindah::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratPengantarCuti::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratPengantarBeasiswa::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratKegiatanMahasiswa::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratKeteranganLulus::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratPermohonanPengambilanMaterial::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratPermohonanSurvei::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratRekomendasiPenelitian::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratPermohonanPengambilanDataAwal::all()->sortByDesc('nomor_surat')->first()->nomor_surat ?? 0;

        $nomorSuratBaru = max($nomorSurat);
        return ++$nomorSuratBaru;
    }

    protected function generateTandaTanganKemahasiswaan(){
        $user = [];
        $pimpinan = User::whereIn('jabatan',['dekan','wd3','kabag tata usaha'])
                        ->where('status_aktif','aktif')
                        ->orderBy('jabatan')
                        ->get();
        foreach ($pimpinan as $p) {
            $user[$p->nip] = strtoupper($p->jabatan).' - '.$p->nama;
        }
        return $user;
    }

    protected function generateUserDisposisi(){
        $user = [];
        $pimpinan =  User::where('status_aktif','aktif');

        switch(Auth::user()->jabatan){
            case 'dekan':
                $pimpinan->whereIn('jabatan',['wd1','wd2','wd3']);
                break;
            case 'wd1':
                $pimpinan->whereIn('jabatan',['wd2','wd3']);
                break;
            case 'wd2':
                $pimpinan->where('jabatan','wd3');
                break;
        }

        $pimpinan = $pimpinan->get();
        
        foreach ($pimpinan as $p) {
            $user[$p->nip] = strtoupper($p->jabatan).' - '.$p->nama;
        }
        return $user;
    }
}
