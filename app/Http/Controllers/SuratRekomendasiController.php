<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use App\KodeSurat;
use Carbon\Carbon;
use App\SuratTugas;
use App\NotifikasiUser;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SuratRekomendasiRequest;

class SuratRekomendasiController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratRekomendasi();
        $countAllSuratRekomendasi = SuratRekomendasi::all()->count();
        $suratRekomendasiList = SuratRekomendasi::orderBy('status')->paginate($perPage);
        $countSuratRekomendasi = $suratRekomendasiList->count();
        return view('user.'.$this->segmentUser.'.surat_rekomendasi',compact('perPage','mahasiswa','nomorSurat','suratRekomendasiList','countAllSuratRekomendasi','countSuratRekomendasi'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratRekomendasi();
        $suratRekomendasiList = SuratRekomendasi::orderByDesc('created_at')->where('status','selesai')->paginate($perPage);
        $pengajuanSuratRekomendasiList = SuratRekomendasi::whereIn('status',['menunggu tanda tangan'])->where('nip',Session::get('nip'))->paginate($perPage);
        $countAllPengajuanSuratRekomendasi = $pengajuanSuratRekomendasiList->count();
        $countAllSuratRekomendasi = SuratRekomendasi::where('status','selesai')->count();
        $countSuratRekomendasi = $suratRekomendasiList->count();
        return view('user.'.$this->segmentUser.'.surat_rekomendasi',compact('perPage','mahasiswa','nomorSurat','countAllSuratRekomendasi','countSuratRekomendasi','suratRekomendasiList','pengajuanSuratRekomendasiList','countAllPengajuanSuratRekomendasi'));
    }

    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratRekomendasi();
        $suratRekomendasiList = SuratRekomendasi::join('daftar_rekomendasi_mahasiswa','daftar_rekomendasi_mahasiswa.id_surat_rekomendasi','=','surat_rekomendasi.id')
                                    ->where('nim',Session::get('nim'))
                                    ->orderByDesc('surat_rekomendasi.created_at')
                                    ->paginate($perPage);
        $countAllSuratRekomendasi = $suratRekomendasiList->count();
        $countSuratRekomendasi = $suratRekomendasiList->count();
        return view($this->segmentUser.'.surat_rekomendasi',compact('perPage','mahasiswa','nomorSurat','countAllSuratRekomendasi','countSuratRekomendasi','suratRekomendasiList'));
    }

    public function progressPengajuanSuratRekomendasi(SuratRekomendasi $suratRekomendasi){
        $surat = collect($suratRekomendasi);
        $kodeSurat = explode('/',$suratRekomendasi->kodeSurat->kode_surat);
        $tanggalDiajukan = $suratRekomendasi->created_at->isoFormat('D MMMM Y - HH:mm:ss');
        $surat->put('tanggal_diajukan',$tanggalDiajukan);

        if($suratRekomendasi->status == 'selesai'){
            $tanggalSelesai = $suratRekomendasi->updated_at->isoFormat('D MMMM Y - HH:mm:ss');
            $surat->put('tanggal_selesai',$tanggalSelesai);
        }
        return $surat->toJson();
    }

    public function create()
    {
        if(!$this->isKodeSuratRekomendasiExists() || !$this->isKodeSuratExists() || !$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-rekomendasi');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList = $this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.tambah_surat_rekomendasi',compact('userList','kodeSurat','nomorSuratBaru','mahasiswa'));
    }

    public function store(SuratRekomendasiRequest $request)
    {
        $input = $request->all();
        $input['nip_kasubag'] = Session::get('nip');
        if($input['nip'] == $input['nip_kasubag']){
            if(!$this->isTandaTanganExists()){
                return redirect($this->segmentUser.'/surat-rekomendasi/create')->withInput();
            }
            $input['status'] = 'selesai';
        }

        DB::beginTransaction();
        try{
            $suratRekomendasi = SuratRekomendasi::create($input);
            if($input['nip'] != $input['nip_kasubag']){
                NotifikasiUser::create([
                    'nip'=>$request->nip,
                    'judul_notifikasi'=>'Surat Rekomendasi',
                    'isi_notifikasi'=>'Tanda tangan surat rekomendasi.',
                    'link_notifikasi'=>url('pimpinan/surat-rekomendasi')
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat rekomendasi gagal ditambahkan.');
        }

        try{
             $notifMahasiswa= [];
             $suratRekomendasi->mahasiswa()->attach($request->nim);
             foreach ($request->nim as $nim) {
                 $notifMahasiswa[] = [
                    'nim'=>$nim,
                    'judul_notifikasi'=>'Surat Rekomendasi',
                    'isi_notifikasi'=>'Surat rekomendasi telah selesai di buat.',
                    'link_notifikasi'=>url('mahasiswa/surat-rekomendasi'), 
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                 ];
             }
             NotifikasiMahasiswa::insert($notifMahasiswa);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat rekomendasi gagal ditambahkan.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat rekomendasi berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-rekomendasi');
    }

    public function show(SuratRekomendasi $suratRekomendasi)
    {
        $surat = collect($suratRekomendasi->load(['mahasiswa.prodi.jurusan','user','kasubag']));
        $kodeSurat = explode('/',$suratRekomendasi->kodeSurat->kode_surat);
        if($suratRekomendasi->tanggal_awal_kegiatan->equalTo($suratRekomendasi->tanggal_akhir_kegiatan)){
            $tanggal = $suratRekomendasi->tanggal_awal_kegiatan->isoFormat('D MMMM Y');
        }elseif($suratRekomendasi->tanggal_awal_kegiatan->isSameMonth($suratRekomendasi->tanggal_akhir_kegiatan)){  
            $tanggal = $suratRekomendasi->tanggal_awal_kegiatan->isoFormat('D').' - '.$suratRekomendasi->tanggal_akhir_kegiatan->isoFormat('D').' '.$suratRekomendasi->tanggal_awal_kegiatan->isoFormat('MMMM Y');
        }else{
            $tanggal =  $suratRekomendasi->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' - '.$suratRekomendasi->tanggal_akhir_kegiatan->isoFormat('D MMMM Y');
        }
        $surat->put('tanggal',$tanggal);
        $surat->put('dibuat',$suratRekomendasi->created_at->isoFormat('D MMMM Y'));
        $surat->put('diubah',$suratRekomendasi->updated_at->isoFormat('D MMMM Y'));
        if($suratRekomendasi->user->jabatan == 'wd3'){
            $surat->put('nomor_surat_rekomendasi',$suratRekomendasi->nomor_surat.'/'.$kodeSurat[0].'.3/'.$kodeSurat[1].'/'.$suratRekomendasi->created_at->format('Y'));
        }else{
            $surat->put('nomor_surat_rekomendasi',$suratRekomendasi->nomor_surat.'/'.$kodeSurat[0].'.4/'.$kodeSurat[1].'/'.$suratRekomendasi->created_at->format('Y'));
        }
        return $surat->toJson();
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords'])){
            $nomor = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratRekomendasi();
            $countAllSuratRekomendasi = SuratRekomendasi::all()->count();
            $suratRekomendasiList = SuratRekomendasi::where('nomor_surat','like',"%$nomor%")->orderByDesc('created_at')->paginate($perPage);
            $countSuratRekomendasi = $suratRekomendasiList->count();
            if($countSuratRekomendasi < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat rekomendasi tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_rekomendasi',compact('perPage','mahasiswa','nomorSurat','countAllSuratRekomendasi','countSuratRekomendasi','suratRekomendasiList'));
        }else{
            return redirect($this->segmentUser.'/surat-rekomendasi');
        }
    }

    public function edit(SuratRekomendasi $suratRekomendasi)
    {
        $mahasiswa = $this->generateMahasiswa();
        $userList = $this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.edit_surat_rekomendasi',compact('suratRekomendasi','mahasiswa','userList','kodeSurat'));
    }

    public function update(SuratRekomendasiRequest $request, SuratRekomendasi $suratRekomendasi)
    {
        $input = $request->all();
        $input['nip_kasubag'] = Session::get('nip');
        if($input['nip'] == $input['nip_kasubag']){
            if(!$this->isTandaTanganExists()){
                return redirect($this->segmentUser.'/surat-rekomendasi/create')->withInput();
            }
        }

        DB::beginTransaction();
        try{
            $suratRekomendasi->update($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat rekomendasi gagal diubah.');
        }

        try{
             $suratRekomendasi->mahasiswa()->sync($request->nim);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat rekomendasi gagal diubah.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat rekomendasi berhasil diubah');
        return redirect($this->segmentUser.'/surat-rekomendasi');
    }

    public function destroy(SuratRekomendasi $suratRekomendasi)
    {
        $suratRekomendasi->delete();
        $this->setFlashData('success','Berhasil','Surat rekomendasi berhasil dihapus');
        return redirect($this->segmentUser.'/surat-rekomendasi');
    }

    public function cetakSuratRekomendasi(SuratRekomendasi $suratRekomendasi){
        $data = '';
        foreach ($suratRekomendasi->mahasiswa as $value) {
            $data .= $value->nim.' - '.$value->nama.' - '.$value->prodi->nama_prodi.' ';
        }
        
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",3,3);
        if(Session::has('nim')){
            if($suratRekomendasi->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Rekomendasi','Anda telah mencetak surat rekomendasi sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-rekomendasi');
            }
        }
        $jumlahCetak = ++$suratRekomendasi->jumlah_cetak;
        $suratRekomendasi->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_rekomendasi',compact('suratRekomendasi','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-rekomendasi'.' - '.$suratRekomendasi->created_at->format('dmY-Him').'.pdf');
    }

    public function tandaTanganRekomendasi(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-rekomendasi');
        }
        $notifMahasiswa = [];
        $suratRekomendasi = SuratRekomendasi::findOrFail($request->id);
        foreach ($suratRekomendasi->mahasiswa as $mahasiswa) {
            $notifMahasiswa[] = [
               'nim'=>$mahasiswa->nim,
               'judul_notifikasi'=>'Surat Rekomendasi',
               'isi_notifikasi'=>'Surat rekomendasi telah di tanda tangani.',
               'link_notifikasi'=>url('mahasiswa/surat-rekomendasi'), 
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now(),
            ];
        }
        $suratRekomendasi->update([
            'status'=>'selesai',
        ]);
        NotifikasiUser::create([
            'nip'=>$suratRekomendasi->nip_kasubag,
            'judul_notifikasi'=>'Surat Rekomendasi',
            'isi_notifikasi'=>'Surat rekomendasi telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-rekomendasi')
        ]);
        NotifikasiMahasiswa::insert($notifMahasiswa);
        $this->setFlashData('success','Berhasil','Tanda tangan surat rekomendasi berhasil');
        return redirect($this->segmentUser.'/surat-rekomendasi');
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinanList = User::whereIn('jabatan',['wd3','kasubag kemahasiswaan'])->orderBy('jabatan')->where('status_aktif','aktif')->get();
        foreach ($pimpinanList as $pimpinan) {
            $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        }
        return $user;
    }

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat rekomendasi')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generateNomorSuratRekomendasi(){
        $suratRekomendasiList = SuratRekomendasi::all();
        $nomorSuratList = [];
        foreach ($suratRekomendasiList as $suratRekomendasi) {
            if($suratRekomendasi->user->jabatan == 'dekan'){
                $nomorSuratList[$suratRekomendasi->nomor_surat] = $suratRekomendasi->nomor_surat.'/'.$suratRekomendasi->kodeSurat->kode_surat.'/'.$suratRekomendasi->created_at->year;
            }else{
                $kodeSurat = explode('/',$suratRekomendasi->kodeSurat->kode_surat);
                $nomorSuratList[$suratRekomendasi->nomor_surat] = $suratRekomendasi->nomor_surat.'/'.$kodeSurat[0].'.3/'.$kodeSurat[1].'/'.$suratRekomendasi->created_at->year;
            }
        }
        return $nomorSuratList;
    }

    private function isKodeSuratRekomendasiExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat rekomendasi')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }
}
