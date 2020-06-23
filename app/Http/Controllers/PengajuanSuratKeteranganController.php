<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Mahasiswa;
use App\TahunAkademik;
use App\NotifikasiUser;
use App\StatusMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PengajuanSuratKeteranganRequest;

class PengajuanSuratKeteranganController extends Controller
{
    public function createPengajuanKeteranganAktif(){
        if(!$this->isSuratAktifDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
        }

        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $tahunAkademik[$tahunAkademikAktif->id] = $tahunAkademikAktif->tahun_akademik.' - '.ucwords($tahunAkademikAktif->semester);
        if($tahunAkademikAktif !=  null){
            $status = StatusMahasiswa::where('status','aktif')->where('id_tahun_akademik',$tahunAkademikAktif->id)->where('nim',Session::get('nim'))->first();
            if($status == null){
                $this->setFlashData('info','Pengajuan Gagal','Maaf anda tidak dapat membuat pengajuan surat keterangan aktif kuliah karena status anda tidak aktif');
                return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
            }
        }else{
            $this->setFlashData('info','Pengajuan Gagal','Tahun akademik belum aktif');
            return redirect('mahasiswa/pengajuan/surat-keterangan-aktif-kuliah');   
        }

        return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_aktif_kuliah',compact('tahunAkademik'));
    }

    public function createPengajuanKelakuanBaik(){
        if(!$this->isSuratKelakuanDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-keterangan-kelakuan-baik');
        }
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $tahunAkademikTerakhir = ($tahunAkademikAktif != null) ? $tahunAkademikAktif:TahunAkademik::orderByDesc('created_at')->first();
        $tahunAkademik = [];
        if($tahunAkademikTerakhir != null){
            $tahunAkademik[$tahunAkademikTerakhir->id] = $tahunAkademikTerakhir->tahun_akademik.' - '.ucwords($tahunAkademikTerakhir->semester);
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_kelakuan_baik',compact('tahunAkademik'));
    }

    
    public function storePengajuanKelakuanBaik(PengajuanSuratKeteranganRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();

        DB::beginTransaction();
        try{
            $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first();
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan kelakuan baik.',
                'link_notifikasi'=>url('pegawai/surat-keterangan-kelakuan-baik')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan kelakuan baik gagal ditambahkan.');
        }

        try{ 
            PengajuanSuratKeterangan::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan kelakuan baik gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan kelakuan baik berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-kelakuan-baik');
    }

    public function storePengajuanKeteranganAktif(PengajuanSuratKeteranganRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();

        DB::beginTransaction();
        try{
            $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first();
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Keterangan Aktif Kuliah',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan aktif kuliah.',
                'link_notifikasi'=>url('pegawai/surat-keterangan-aktif-kuliah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan aktif kuliah gagal ditambahkan.');
        }

        try{ 
            PengajuanSuratKeterangan::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan aktif kuliah gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan aktif kuliah berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
    }

    
    private function isSuratAktifDiajukanExists(){
        $suratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')->where('nim',Session::get('nim'))->where('status','diajukan')->exists();
        if($suratKeterangan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat keterangan aktif kuliah sementara diproses!');
            return false;
        }
        return true;
    }

    private function isSuratKelakuanDiajukanExists(){
        $suratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')->where('nim',Session::get('nim'))->where('status','diajukan')->exists();
        if($suratKeterangan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat keterangan kelakuan baik sementara diproses!');
            return false;
        }
        return true;
    }
}
