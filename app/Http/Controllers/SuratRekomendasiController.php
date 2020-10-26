<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use DataTables;
use App\KodeSurat;
use Carbon\Carbon;
use App\SuratTugas;
use App\NotifikasiUser;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratRekomendasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SuratRekomendasiRequest;

class SuratRekomendasiController extends Controller
{
    public function index(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratRekomendasi::where('status','verifikasi kasubag')
                                                        ->count();

        $countAllSurat = SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                          ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                          ->count();

        return view('user.'.$this->segmentUser.'.surat_rekomendasi',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratRekomendasi::where('status','verifikasi kabag')
                                                        ->count();
                                            
        $countAllSurat = SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                          ->where('status','selesai')
                                          ->count();
        
        $countAllTandaTangan = SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.'.$this->segmentUser.'.surat_rekomendasi',compact('perPage','countAllVerifikasi','countAllSurat','countAllTandaTangan'));
    }

    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $countAllSurat = PengajuanSuratRekomendasi::join('daftar_rekomendasi_mahasiswa','daftar_rekomendasi_mahasiswa.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                                   ->where('daftar_rekomendasi_mahasiswa.nim',Auth::user()->nim)
                                                   ->count();
        return view($this->segmentUser.'.surat_rekomendasi',compact('perPage','countAllSurat'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratRekomendasi::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                            ->whereNotIn('status',['diajukan'])
                                            ->count();

        return view($this->segmentUser.'.surat_rekomendasi',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function progress(PengajuanSuratRekomendasi $pengajuanSuratRekomendasi){
        $data = collect($pengajuanSuratRekomendasi);
        $data->put('status',ucwords($pengajuanSuratRekomendasi->status));

        if($pengajuanSuratRekomendasi->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratRekomendasi->suratRekomendasi->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalSelesai);
        }else if($pengajuanSuratRekomendasi->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratRekomendasi->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalDitolak);
        }else{
            $tanggal = $pengajuanSuratRekomendasi->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggal);
        }

        return $data->toJson();
    }

    public function createSurat(PengajuanSuratRekomendasi $pengajuanSuratRekomendasi){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-dispensasi');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view('operator.tambah_surat_rekomendasi',compact('userList','kodeSurat','nomorSuratBaru','pengajuanSuratRekomendasi'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat|unique:surat_keterangan_bebas_perlengkapan,nomor_surat',
            'nip'=>'required',
        ]);
        
        $input = $request->all();
        $pengajuanSuratRekomendasi = PengajuanSuratRekomendasi::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag kemahasiswaan')
                      ->first();

        DB::beginTransaction();
        try{
            $suratDispensasi = SuratRekomendasi::create($input);

            $pengajuanSuratRekomendasi->update([
                'status'=>'verifikasi kasubag'
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Rekomendasi',
                'isi_notifikasi'=>'Verifikasi surat rekomendasi.',
                'link_notifikasi'=>url('pegawai/surat-rekomendasi')
            ]);
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
        $surat = collect($suratRekomendasi->load(['pengajuanSuratRekomendasi.operator','pengajuanSuratRekomendasi.mahasiswa.prodi.jurusan','user','kodeSurat']));
        $kodeSurat = explode('/',$suratRekomendasi->kodeSurat->kode_surat);
        if($suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->equalTo($suratRekomendasi->pengajuanSuratRekomendasi->tanggal_akhir_kegiatan)){
            $tanggal = $suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isoFormat('D MMMM Y');
        }elseif($suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isSameMonth($suratRekomendasi->pengajuanSuratRekomendasi->tanggal_akhir_kegiatan)){  
            $tanggal = $suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isoFormat('D').' - '.$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_akhir_kegiatan->isoFormat('D').' '.$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isoFormat('MMMM Y');
        }else{
            $tanggal =  $suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' - '.$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_akhir_kegiatan->isoFormat('D MMMM Y');
        }
        $surat->put('tanggal',$tanggal);
        $surat->put('status',ucwords($suratRekomendasi->pengajuanSuratRekomendasi->status));
        $surat->put('tahun',$suratRekomendasi->created_at->isoFormat('Y'));
        $surat->put('dibuat',$suratRekomendasi->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        return $surat->toJson();
    }

    public function getAllSuratRekomendasi(){
        $suratRekomendasi = PengajuanSuratRekomendasi::join('surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                    ->select('surat_rekomendasi.nomor_surat','pengajuan_surat_rekomendasi.*')
                                    ->with(['suratRekomendasi.kodeSurat']);

        if(isset(Auth::user()->id)){
            $suratRekomendasi = $suratRekomendasi->whereNotIn('status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratRekomendasi = $suratRekomendasi->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratRekomendasi = $suratRekomendasi->where('status','selesai');
            }
        }

        return DataTables::of($suratRekomendasi)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
    }

    public function getAllTandaTangan(){
        $suratRekomendasi =  PengajuanSuratRekomendasi::join('surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('surat_rekomendasi.nip',Auth::user()->nip)
                                    ->select('surat_rekomendasi.nomor_surat','pengajuan_surat_rekomendasi.*')
                                    ->with(['suratRekomendasi.kodeSurat']);
                                    
        return DataTables::of($suratRekomendasi)
                                    ->addColumn('aksi', function ($data) {
                                        return $data->id;
                                    })
                                      ->addColumn('waktu_pengajuan', function ($data) {
                                        return $data->created_at->diffForHumans();
                                    })
                                    ->editColumn("status", function ($data) {
                                        return ucwords($data->status);
                                    })
                                    ->editColumn("created_at", function ($data) {
                                        return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                                    })
                                    ->make(true);                            
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

    public function cetak(SuratRekomendasi $suratRekomendasi){
        if(isset(Auth::user()->nim)){
            $nim = $suratRekomendasi->pengajuanSuratRekomendasi->mahasiswa->map(function ($mahasiswa) {
                return strtoupper($mahasiswa->nim);
            })->toArray();

            if(!in_array(Auth::user()->nim,$nim)){ 
                abort(404);
            }

            if($suratRekomendasi->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat rekomendasi sebanyak 3 kali.');
                return redirect('mahasiswa/surat-rekomendasi');
            }
        }

        $data = '';
        foreach ($suratRekomendasi->pengajuanSuratRekomendasi->mahasiswa as $value) {
            $data .= $value->nim.' - '.$value->nama.' - '.$value->prodi->nama_prodi.' ';
        }
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",3,3);
        
        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratRekomendasi->jumlah_cetak;
                $suratRekomendasi->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }
        }
        $pdf = PDF::loadview('surat.surat_rekomendasi',compact('suratRekomendasi','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-rekomendasi'.' - '.$suratRekomendasi->created_at->format('dmY-Him').'.pdf');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-rekomendasi');
        }
        $notifMahasiswa = [];
        $pengajuanSuratRekomendasi = PengajuanSuratRekomendasi::findOrFail($request->id);

        foreach ($pengajuanSuratRekomendasi->mahasiswa as $mahasiswa) {
            $notifMahasiswa[] = [
               'nim'=>$mahasiswa->nim,
               'judul_notifikasi'=>'Surat Rekomendasi',
               'isi_notifikasi'=>'Surat rekomendasi telah di tanda tangani.',
               'link_notifikasi'=>url('mahasiswa/surat-rekomendasi'), 
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now(),
            ];
        }

        $pengajuanSuratRekomendasi->update([
            'status'=>'selesai',
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
