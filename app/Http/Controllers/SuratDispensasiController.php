<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use App\KodeSurat;
use Carbon\Carbon;
use App\SuratMasuk;
use App\SuratTugas;
use App\NotifikasiUser;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\DaftarDispensasiMahasiswa;
use App\TahapanKegiatanDispensasi;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SuratDispensasiRequest;

class SuratDispensasiController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratDispensasi();
        $countAllSuratDispensasi = SuratDispensasi::all()->count();
        $suratDispensasiList = SuratDispensasi::orderBy('status')->paginate($perPage);
        $countSuratDispensasi = $suratDispensasiList->count();
        return view('user.'.$this->segmentUser.'.surat_dispensasi',compact('perPage','mahasiswa','nomorSurat','countAllSuratDispensasi','countSuratDispensasi','suratDispensasiList'));
    }

    public function suratDispensasiPimpinan(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratDispensasi();
        $suratDispensasiList = SuratDispensasi::orderByDesc('created_at')->where('status','selesai')->paginate($perPage);
        $pengajuanSuratDispensasiList = SuratDispensasi::whereIn('status',['menunggu tanda tangan'])->where('nip',Session::get('nip'))->paginate($perPage);
        $countAllPengajuanSuratDispensasi = $pengajuanSuratDispensasiList->count();
        $countAllSuratDispensasi = SuratDispensasi::where('status','selesai')->count();
        $countSuratDispensasi = $suratDispensasiList->count();
        return view('user.'.$this->segmentUser.'.surat_dispensasi',compact('perPage','mahasiswa','nomorSurat','countAllSuratDispensasi','countSuratDispensasi','suratDispensasiList','pengajuanSuratDispensasiList','countAllPengajuanSuratDispensasi'));
    }

    public function suratDispensasiMahasiswa(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratDispensasi();
        $suratDispensasiList = SuratDispensasi::join('daftar_dispensasi_mahasiswa','daftar_dispensasi_mahasiswa.id_surat_dispensasi','=','surat_dispensasi.id_surat_masuk')
                                    ->where('nim',Session::get('nim'))
                                    ->orderByDesc('surat_dispensasi.created_at')
                                    ->paginate($perPage);
        $countAllSuratDispensasi = $suratDispensasiList->count();
        $countSuratDispensasi = $suratDispensasiList->count();
        return view($this->segmentUser.'.surat_dispensasi',compact('perPage','mahasiswa','nomorSurat','countAllSuratDispensasi','countSuratDispensasi','suratDispensasiList'));
    }

    public function create(Request $request){
        if(!$this->isKodeSuratDispensasiExists() || !$this->isKodeSuratExists() || !$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-dispensasi');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        $userList = $this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.tambah_surat_dispensasi',compact('userList','kodeSurat','suratMasuk','nomorSuratBaru','mahasiswa'));
    }

    public function show(SuratDispensasi $suratDispensasi){
        $surat = collect($suratDispensasi->load(['mahasiswa.prodi.jurusan','tahapanKegiatanDispensasi','suratMasuk','user','kasubag']));
        $kodeSurat = explode('/',$suratDispensasi->kodeSurat->kode_surat);
        $tanggalKegiatan = [];
        foreach ($suratDispensasi->TahapanKegiatanDispensasi as $key => $tahapan) {
            if($tahapan->tanggal_awal_kegiatan->equalTo($tahapan->tanggal_akhir_kegiatan)){
                $tanggal = $tahapan->tanggal_awal_kegiatan->isoFormat('D MMMM Y');
            }elseif($tahapan->tanggal_awal_kegiatan->isSameMonth($tahapan->tanggal_akhir_kegiatan)){  
                $tanggal = $tahapan->tanggal_awal_kegiatan->isoFormat('D').' s/d '.$tahapan->tanggal_akhir_kegiatan->isoFormat('D').' '.$tahapan->tanggal_awal_kegiatan->isoFormat('MMMM Y');
            }else{
                $tanggal =  $tahapan->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' s/d '.$tahapan->tanggal_akhir_kegiatan->isoFormat('D MMMM Y');
            }
            $tanggalKegiatan[] = $tanggal;
        }
        $surat->put('tanggal_kegiatan',$tanggalKegiatan);
        $surat->put('nama_file',explode('.',$suratDispensasi->suratMasuk->file_surat_masuk)[0]);
        $surat->put('link_file',asset('upload_surat_masuk/'.$suratDispensasi->suratMasuk->file_surat_masuk));
        $surat->put('dibuat',$suratDispensasi->created_at->isoFormat('D MMMM Y'));
        $surat->put('diubah',$suratDispensasi->updated_at->isoFormat('D MMMM Y'));
        if($suratDispensasi->user->jabatan == 'dekan'){
            $surat->put('nomor_surat_dispensasi',$suratDispensasi->nomor_surat.'/'.$suratDispensasi->kodeSurat->kode_surat.'/'.$suratDispensasi->created_at->format('Y'));
        }else{
            $surat->put('nomor_surat_dispensasi',$suratDispensasi->nomor_surat.'/'.$kodeSurat[0].'.3/'.$kodeSurat[1].'/'.$suratDispensasi->created_at->format('Y'));
        }
        return $surat->toJson();
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords'])){
            $nomor = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratDispensasi();
            $countAllSuratDispensasi = SuratDispensasi::all()->count();
            $suratDispensasiList = SuratDispensasi::where('nomor_surat','like',"%$nomor%")->orderByDesc('created_at')->paginate($perPage);
            $countSuratDispensasi = $suratDispensasiList->count();
            if($countSuratDispensasi < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat dispensasi tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_dispensasi',compact('perPage','mahasiswa','nomorSurat','countAllSuratDispensasi','countSuratDispensasi','suratDispensasiList'));
        }else{
            return redirect($this->segmentUser.'/surat-dispensasi');
        }
    }

    public function store(SuratDispensasiRequest $request){
        $input = $request->all();
        $input['nip_kasubag'] = Session::get('nip');

        DB::beginTransaction();
        try{
            $suratDispensasi = SuratDispensasi::create($input);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Dispensasi',
                'isi_notifikasi'=>'Tanda tangan surat dispensasi.',
                'link_notifikasi'=>url('pimpinan/surat-dispensasi')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat dispensasi gagal ditambahkan.');
        }

        try{
             $daftarMahasiswa= [];
             $notifMahasiswa= [];
             $tahapanKegiatan = [];
             foreach ($request->nim as $nim) {
                 $daftarMahasiswa[] = [
                     'id_surat_dispensasi'=>$request->id_surat_masuk,
                     'nim'=>$nim,
                     'created_at'=>Carbon::now(),
                     'updated_at'=>Carbon::now(),
                 ];
                 $notifMahasiswa[] = [
                    'nim'=>$nim,
                    'judul_notifikasi'=>'Surat Dispensasi',
                    'isi_notifikasi'=>'Surat dispensasi telah selesai di buat.',
                    'link_notifikasi'=>url('mahasiswa/surat-dispensasi'), 
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                 ];
             }
             $i = 0;
             while ($i < $request->jumlah) {
                 $tahapanKegiatan[] = [
                     'id_surat_dispensasi'=>$request->id_surat_masuk,
                     'tahapan_kegiatan'=>$request->tahapan_kegiatan[$i],
                     'tempat_kegiatan'=>$request->tempat_kegiatan[$i],
                     'tanggal_awal_kegiatan'=>$request->tanggal_awal_kegiatan[$i],
                     'tanggal_akhir_kegiatan'=>$request->tanggal_akhir_kegiatan[$i],
                     'created_at'=>Carbon::now(),
                     'updated_at'=>Carbon::now(),
                 ];
                 $i++;
             }
             DaftarDispensasiMahasiswa::insert($daftarMahasiswa);
             NotifikasiMahasiswa::insert($notifMahasiswa);
             TahapanKegiatanDispensasi::insert($tahapanKegiatan);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat dispensasi gagal ditambahkan.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat dispensasi berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }

    public function edit(SuratDispensasi $suratDispensasi){
        $mahasiswa = $this->generateMahasiswa();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        $userList = $this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.edit_surat_dispensasi',compact('suratDispensasi','mahasiswa','suratMasuk','userList','kodeSurat'));
    }

    public function update(SuratDispensasiRequest $request, SuratDispensasi $suratDispensasi){
        $input = $request->all();
        $input['nip_kasubag'] = Session::get('nip');

        DB::beginTransaction();
        try{
            $suratDispensasi->update($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat dispensasi gagal diubah.');
        }

        try{
             $suratDispensasi->mahasiswa()->sync($request->nim);
             $i = 0;
             while ($i < $request->jumlah) {
                 $tahapan = [
                     'id_surat_dispensasi'=>$request->id_surat_masuk,
                     'tahapan_kegiatan'=>$request->tahapan_kegiatan[$i],
                     'tempat_kegiatan'=>$request->tempat_kegiatan[$i],
                     'tanggal_awal_kegiatan'=>$request->tanggal_awal_kegiatan[$i],
                     'tanggal_akhir_kegiatan'=>$request->tanggal_akhir_kegiatan[$i],
                 ];
                 $tahapanKegiatan = TahapanKegiatanDispensasi::findOrFail($request->id_tahapan[$i]);
                 $tahapanKegiatan->update($tahapan);
                 $i++;
             }
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat dispensasi gagal ditambahkan.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat dispensasi berhasil diubah');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }

    public function destroy(SuratDispensasi $suratDispensasi){
        $suratDispensasi->delete();
        $this->setFlashData('success','Berhasil','Surat dispensasi berhasil dihapus');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }

    public function cetakSuratDispensasi(SuratDispensasi $suratDispensasi){
        $data = '';
        foreach ($suratDispensasi->mahasiswa as $value) {
            $data .= $value->nim.' - '.$value->nama.' - '.$value->prodi->nama_prodi.' ';
        }

        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",3,3);
        if(Session::has('nim')){
            if($suratDispensasi->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Dispensasi','Anda telah mencetak surat dispensasi sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-dispensasi');
            }
        }
        $jumlahCetak = ++$suratDispensasi->jumlah_cetak;
        $suratDispensasi->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_dispensasi',compact('suratDispensasi','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-dispensasi'.' - '.$suratDispensasi->created_at->format('dmY-Him').'.pdf');
    }

    public function tandaTanganDispensasi(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-dispensasi');
        }
        $notifMahasiswa = [];
        $suratDispensasi = SuratDispensasi::findOrFail($request->id);
        foreach ($suratDispensasi->mahasiswa as $mahasiswa) {
            $notifMahasiswa[] = [
               'nim'=>$mahasiswa->nim,
               'judul_notifikasi'=>'Surat Dispensasi',
               'isi_notifikasi'=>'Surat dispensasi telah di tanda tangani.',
               'link_notifikasi'=>url('mahasiswa/surat-dispensasi'), 
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now(),
            ];
        }
        $suratDispensasi->update([
            'status'=>'selesai',
        ]);
        NotifikasiUser::create([
            'nip'=>$suratDispensasi->nip_kasubag,
            'judul_notifikasi'=>'Surat Dispensasi',
            'isi_notifikasi'=>'Surat dispensasi telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-dispensasi')
        ]);
        NotifikasiMahasiswa::insert($notifMahasiswa);
        $this->setFlashData('success','Berhasil','Tanda tangan surat dispensasi berhasil');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }

    public function progressPengajuanSuratDispensasi(SuratDispensasi $suratDispensasi){
        $surat = collect($suratDispensasi);
        $kodeSurat = explode('/',$suratDispensasi->kodeSurat->kode_surat);
        $tanggalDiajukan = $suratDispensasi->created_at->isoFormat('D MMMM Y - HH:mm:ss');
        $surat->put('tanggal_diajukan',$tanggalDiajukan);

        if($suratDispensasi->status == 'selesai'){
            $tanggalSelesai = $suratDispensasi->updated_at->isoFormat('D MMMM Y - HH:mm:ss');
            $surat->put('tanggal_selesai',$tanggalSelesai);
        }
        return $surat->toJson();
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinanList = User::whereIn('jabatan',['dekan','wd3'])->where('status_aktif','aktif')->get();
        foreach ($pimpinanList as $pimpinan) {
            $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        }
        return $user;
    }

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat dispensasi')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function isKodeSuratDispensasiExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat dispensasi')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }
}
