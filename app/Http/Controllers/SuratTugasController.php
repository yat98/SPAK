<?php

namespace App\Http\Controllers;

use PDF;
use DB;
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
use App\Http\Requests\SuratTugasRequest;

class SuratTugasController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSurattugas();
        $countAllSuratTugas = SuratTugas::all()->count();
        $suratTugasList = SuratTugas::orderBy('status')->paginate($perPage);
        $countSuratTugas = $suratTugasList->count();
        return view('user.'.$this->segmentUser.'.surat_tugas',compact('perPage','mahasiswa','nomorSurat','suratTugasList','countAllSuratTugas','countSuratTugas'));
    }

    public function suratTugasPimpinan(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratTugas();
        $suratTugasList = SuratTugas::orderByDesc('created_at')->where('status','selesai')->paginate($perPage);
        $pengajuanSuratTugasList = SuratTugas::whereIn('status',['menunggu tanda tangan'])->where('nip',Session::get('nip'))->paginate($perPage);
        $countAllPengajuanSuratTugas = $pengajuanSuratTugasList->count();
        $countAllSuratTugas = SuratTugas::where('status','selesai')->count();
        $countSuratTugas = $suratTugasList->count();
        return view('user.'.$this->segmentUser.'.surat_tugas',compact('perPage','mahasiswa','nomorSurat','countAllSuratTugas','countSuratTugas','suratTugasList','pengajuanSuratTugasList','countAllPengajuanSuratTugas'));
    }

    public function suratTugasMahasiswa(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratTugas();
        $suratTugasList = SuratTugas::select('*','surat_tugas.created_at','surat_tugas.updated_at')
                                    ->join('daftar_tugas_mahasiswa','daftar_tugas_mahasiswa.id_surat_tugas','=','surat_tugas.id')
                                    ->where('nim',Session::get('nim'))
                                    ->orderByDesc('surat_tugas.created_at')
                                    ->paginate($perPage);
        $countAllSuratTugas = $suratTugasList->count();
        $countSuratTugas = $suratTugasList->count();
        return view($this->segmentUser.'.surat_tugas',compact('perPage','mahasiswa','nomorSurat','countAllSuratTugas','countSuratTugas','suratTugasList'));
    }

    public function create()
    {
        if(!$this->isKodeSuratTugasExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-tugas');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat[] = SuratKeterangan::orderByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratDispensasi::orderByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratRekomendasi::orderByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSurat[] = SuratTugas::orderByDesc('nomor_surat')->first()->nomor_surat ?? 0;
        $nomorSuratBaru = max($nomorSurat);
        ++$nomorSuratBaru;
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.tambah_surat_tugas',compact('userList','kodeSurat','nomorSuratBaru','mahasiswa','userList'));
    }

    public function store(SuratTugasRequest $request)
    {
        $input = $request->all();
        $input['nip_kasubag'] = Session::get('nip');

        DB::beginTransaction();
        try{
            $suratTugas = SuratTugas::create($input);
            if($input['nip'] != $input['nip_kasubag']){
                NotifikasiUser::create([
                    'nip'=>$request->nip,
                    'judul_notifikasi'=>'Surat Tugas',
                    'isi_notifikasi'=>'Tanda tangan surat tugas.',
                    'link_notifikasi'=>url('pimpinan/surat-tugas')
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat tugas gagal ditambahkan.');
        }

        try{
             $notifMahasiswa= [];
             $suratTugas->mahasiswa()->attach($request->nim);
             foreach ($request->nim as $nim) {
                 $notifMahasiswa[] = [
                    'nim'=>$nim,
                    'judul_notifikasi'=>'Surat Tugas',
                    'isi_notifikasi'=>'Surat tugas telah selesai di buat.',
                    'link_notifikasi'=>url('mahasiswa/surat-tugas'), 
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                 ];
             }
             NotifikasiMahasiswa::insert($notifMahasiswa);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat tugas gagal ditambahkan.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat tugas berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-tugas');
    }

    public function show(SuratTugas $suratTugas)
    {
        $surat = collect($suratTugas->load(['mahasiswa.prodi.jurusan','user','kasubag']));
        $kodeSurat = explode('/',$suratTugas->kodeSurat->kode_surat);
        if($suratTugas->tanggal_awal_kegiatan->equalTo($suratTugas->tanggal_akhir_kegiatan)){
            $tanggal = $suratTugas->tanggal_awal_kegiatan->isoFormat('D MMMM Y');
        }elseif($suratTugas->tanggal_awal_kegiatan->isSameMonth($suratTugas->tanggal_akhir_kegiatan)){  
            $tanggal = $suratTugas->tanggal_awal_kegiatan->isoFormat('D').' - '.$suratTugas->tanggal_akhir_kegiatan->isoFormat('D').' '.$suratTugas->tanggal_awal_kegiatan->isoFormat('MMMM Y');
        }else{
            $tanggal =  $suratTugas->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' - '.$suratTugas->tanggal_akhir_kegiatan->isoFormat('D MMMM Y');
        }
        $surat->put('tanggal',$tanggal);
        $surat->put('dibuat',$suratTugas->created_at->isoFormat('D MMMM Y'));
        $surat->put('diubah',$suratTugas->updated_at->isoFormat('D MMMM Y'));
        $surat->put('jenis',strtolower($suratTugas->jenis_kegiatan));
        $surat->put('nomor_surat_tugas','B/'.$suratTugas->nomor_surat.'/'.$suratTugas->kodeSurat->kode_surat.'/'.$suratTugas->created_at->format('Y'));
        
        return $surat->toJson();
    }

    public function edit(SuratTugas $suratTugas)
    {
        $mahasiswa = $this->generateMahasiswa();
        $userList = $this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.edit_surat_tugas',compact('suratTugas','mahasiswa','userList','kodeSurat'));
    }

    public function update(SuratTugasRequest $request, SuratTugas $suratTugas)
    {
        $input = $request->all();
        $input['nip_kasubag'] = Session::get('nip');

        DB::beginTransaction();
        try{
            $suratTugas->update($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat tugas gagal diubah.');
        }

        try{
             $suratTugas->mahasiswa()->sync($request->nim);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat tugas gagal diubah.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat tugas berhasil diubah');
        return redirect($this->segmentUser.'/surat-tugas');
    }

    public function destroy(SuratTugas $suratTugas)
    {
        $suratTugas->delete();
        $this->setFlashData('success','Berhasil','Surat tugas berhasil dihapus');
        return redirect($this->segmentUser.'/surat-tugas');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords'])){
            $nomor = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratTugas();
            $countAllSuratTugas = SuratTugas::all()->count();
            $suratTugasList = SuratTugas::where('nomor_surat','like',"%$nomor%")->orderByDesc('created_at')->paginate($perPage);
            $countSuratTugas = $suratTugasList->count();
            if($countSuratTugas < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat tugas tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_tugas',compact('perPage','mahasiswa','nomorSurat','countAllSuratTugas','countSuratTugas','suratTugasList'));
        }else{
            return redirect($this->segmentUser.'/surat-tugas');
        }
    }

    public function tandaTanganTugas(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-tugas');
        }
        $notifMahasiswa = [];
        $suratTugas = SuratTugas::findOrFail($request->id);
        foreach ($suratTugas->mahasiswa as $mahasiswa) {
            $notifMahasiswa[] = [
               'nim'=>$mahasiswa->nim,
               'judul_notifikasi'=>'Surat Tugas',
               'isi_notifikasi'=>'Surat tugas telah di tanda tangani.',
               'link_notifikasi'=>url('mahasiswa/surat-tugas'), 
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now(),
            ];
        }
        $suratTugas->update([
            'status'=>'selesai',
        ]);
        NotifikasiUser::create([
            'nip'=>$suratTugas->nip_kasubag,
            'judul_notifikasi'=>'Surat Tugas',
            'isi_notifikasi'=>'Surat tugas telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-tugas')
        ]);
        NotifikasiMahasiswa::insert($notifMahasiswa);
        $this->setFlashData('success','Berhasil','Tanda tangan surat tugas berhasil');
        return redirect($this->segmentUser.'/surat-tugas');
    }

    public function cetakSuratTugas(SuratTugas $suratTugas){
        if(Session::has('nim')){
            if($suratTugas->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Tugas','Anda telah mencetak surat tugas sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-tugas');
            }
        }
        $jumlahCetak = ++$suratTugas->jumlah_cetak;
        $suratTugas->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_tugas',compact('suratTugas'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-tugas'.' - '.$suratTugas->created_at->format('dmY-Him').'.pdf');
    }

    public function progressPengajuanSuratTugas(SuratTugas $suratTugas){
        $surat = collect($suratTugas);
        $kodeSurat = explode('/',$suratTugas->kodeSurat->kode_surat);
        $tanggalDiajukan = $suratTugas->created_at->isoFormat('D MMMM Y - HH:mm:ss');
        $surat->put('tanggal_diajukan',$tanggalDiajukan);

        if($suratTugas->status == 'selesai'){
            $tanggalSelesai = $suratTugas->updated_at->isoFormat('D MMMM Y - HH:mm:ss');
            $surat->put('tanggal_selesai',$tanggalSelesai);
        }
        return $surat->toJson();
    }

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat tugas')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generateNomorSuratTugas(){
        $suratTugasList = SuratTugas::all();
        $nomorSuratList = [];
        foreach ($suratTugasList as $suratTugas) {
            $nomorSuratList[$suratTugas->nomor_surat] = 'B/'.$suratTugas->nomor_surat.'/'.$suratTugas->kodeSurat->kode_surat.'/'.$suratTugas->created_at->year;
        }
        return $nomorSuratList;
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinan = User::where('jabatan','dekan')->where('status_aktif','aktif')->first();
        $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        return $user;
    }

    private function isKodeSuratTugasExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat tugas')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Tugas Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }
}
