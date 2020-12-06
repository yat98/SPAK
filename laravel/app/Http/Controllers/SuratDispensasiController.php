<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use DataTables;
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
use App\PengajuanSuratDispensasi;
use App\DaftarDispensasiMahasiswa;
use App\TahapanKegiatanDispensasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SuratDispensasiRequest;

class SuratDispensasiController extends Controller
{
    public function index(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratDispensasi::where('status','verifikasi kasubag')
                                                        ->count();

        $countAllSurat = SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                          ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                          ->count();

        return view('user.'.$this->segmentUser.'.surat_dispensasi',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratDispensasi::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                            ->whereNotIn('status',['diajukan'])
                                            ->count();
                                                                         
        return view($this->segmentUser.'.surat_dispensasi',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratDispensasi::where('status','verifikasi kabag')
                                                        ->count();
                                            
        $countAllSurat = SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                          ->where('status','selesai')
                                          ->count();
        
        $countAllTandaTangan = SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.'.$this->segmentUser.'.surat_dispensasi',compact('perPage','countAllVerifikasi','countAllSurat','countAllTandaTangan'));
    }

    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $countAllSurat = PengajuanSuratDispensasi::join('daftar_dispensasi_mahasiswa','daftar_dispensasi_mahasiswa.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                                   ->where('daftar_dispensasi_mahasiswa.nim',Auth::user()->nim)
                                                   ->count();
        return view($this->segmentUser.'.surat_dispensasi',compact('perPage','countAllSurat'));
    }

    public function getAllSuratDispensasi(){
        $suratDispensasi = PengajuanSuratDispensasi::join('surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                    ->select('surat_dispensasi.nomor_surat','pengajuan_surat_dispensasi.*')
                                    ->with(['suratDispensasi.kodeSurat']);

        if(isset(Auth::user()->id)){
            $suratDispensasi = $suratDispensasi->whereNotIn('status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratDispensasi = $suratDispensasi->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratDispensasi = $suratDispensasi->where('status','selesai');
            }
        }

        return DataTables::of($suratDispensasi)
                        ->addColumn('aksi', function ($data) {
                            return $data->id_surat_masuk;
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

    public function createSurat(PengajuanSuratDispensasi $pengajuanSuratDispensasi){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-dispensasi');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view('operator.tambah_surat_dispensasi',compact('userList','kodeSurat','nomorSuratBaru','pengajuanSuratDispensasi'));
    }

    public function show(SuratDispensasi $suratDispensasi){
        $tanggalKegiatan = [];
        $surat = collect($suratDispensasi->load(['pengajuanSuratDispensasi.mahasiswa.prodi.jurusan','pengajuanSuratDispensasi.tahapanKegiatanDispensasi','pengajuanSuratDispensasi.suratMasuk','pengajuanSuratDispensasi.operator','kodeSurat','user']));

        foreach ($suratDispensasi->pengajuanSuratDispensasi->tahapanKegiatanDispensasi as $key => $tahapan) {
            if($tahapan->tanggal_awal_kegiatan->equalTo($tahapan->tanggal_akhir_kegiatan)){
                $tanggal = $tahapan->tanggal_awal_kegiatan->isoFormat('D MMMM Y');
            }elseif($tahapan->tanggal_awal_kegiatan->isSameMonth($tahapan->tanggal_akhir_kegiatan)){  
                $tanggal = $tahapan->tanggal_awal_kegiatan->isoFormat('D').' s/d '.$tahapan->tanggal_akhir_kegiatan->isoFormat('D').' '.$tahapan->tanggal_awal_kegiatan->isoFormat('MMMM Y');
            }else{
                $tanggal =  $tahapan->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' s/d '.$tahapan->tanggal_akhir_kegiatan->isoFormat('D MMMM Y');
            }
            $tanggalKegiatan[] = $tanggal;
        }
        $surat->put('status',ucwords($suratDispensasi->pengajuanSuratDispensasi->status));
        $surat->put('tahun',$suratDispensasi->created_at->isoFormat('Y'));
        $surat->put('tanggal_kegiatan',$tanggalKegiatan);
        $surat->put('nama_file',explode('.',$suratDispensasi->pengajuanSuratDispensasi->suratMasuk->file_surat_masuk)[0]);
        $surat->put('link_file',asset('upload_surat_masuk/'.$suratDispensasi->pengajuanSuratDispensasi->suratMasuk->file_surat_masuk));
        $surat->put('dibuat',$suratDispensasi->pengajuanSuratDispensasi->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        return $surat->toJson();
    }

    public function progress(PengajuanSuratDispensasi $pengajuanSuratDispensasi){
        $data = collect($pengajuanSuratDispensasi);
        $data->put('status',ucwords($pengajuanSuratDispensasi->status));

        if($pengajuanSuratDispensasi->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratDispensasi->suratDispensasi->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalSelesai);
        }else if($pengajuanSuratDispensasi->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratDispensasi->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalDitolak);
        }else{
            $tanggal = $pengajuanSuratDispensasi->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggal);
        }

        return $data->toJson();
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
        $pengajuanSuratDispensasi = PengajuanSuratDispensasi::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag kemahasiswaan')
                      ->first();

        DB::beginTransaction();
        try{
            $suratDispensasi = SuratDispensasi::create($input);

            $pengajuanSuratDispensasi->update([
                'status'=>'verifikasi kasubag'
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Dispensasi',
                'isi_notifikasi'=>'Verifikasi surat dispensasi.',
                'link_notifikasi'=>url('pegawai/surat-dispensasi')
            ]);
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

    public function cetak(SuratDispensasi $suratDispensasi){
        if(isset(Auth::user()->nim)){
            $nim = $suratDispensasi->pengajuanSuratRekomendasi->mahasiswa->map(function ($mahasiswa) {
                return strtoupper($mahasiswa->nim);
            })->toArray();

            if(!in_array(Auth::user()->nim,$nim)){ 
                abort(404);
            }

            if($suratDispensasi->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat dispensasi sebanyak 3 kali.');
                return redirect('mahasiswa/surat-dispensasi');
            }
        }

        $data = '';
        foreach ($suratDispensasi->pengajuanSuratDispensasi->mahasiswa as $value) {
            $data .= $value->nim.' - '.$value->nama.' - '.$value->prodi->nama_prodi.' ';
        }
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",3,3);

        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratDispensasi->jumlah_cetak;
                $suratDispensasi->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }
        }

        $pdf = PDF::loadview('surat.surat_dispensasi',compact('suratDispensasi','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-dispensasi'.' - '.$suratDispensasi->created_at->format('dmY-Him').'.pdf');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-dispensasi');
        }
        $notifMahasiswa = [];
        $pengajuanSuratDispensasi = PengajuanSuratDispensasi::findOrFail($request->id);

        foreach ($pengajuanSuratDispensasi->mahasiswa as $mahasiswa) {
            $notifMahasiswa[] = [
               'nim'=>$mahasiswa->nim,
               'judul_notifikasi'=>'Surat Dispensasi',
               'isi_notifikasi'=>'Surat dispensasi telah di tanda tangani.',
               'link_notifikasi'=>url('mahasiswa/surat-dispensasi'), 
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now(),
            ];
        }

        $pengajuanSuratDispensasi->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::insert($notifMahasiswa);
        $this->setFlashData('success','Berhasil','Tanda tangan surat dispensasi berhasil');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }

    public function getAllTandaTangan(){
        $suratDispensasi =  PengajuanSuratDispensasi::join('surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('surat_dispensasi.nip',Auth::user()->nip)
                                    ->select('surat_dispensasi.nomor_surat','pengajuan_surat_dispensasi.*')
                                    ->with(['suratDispensasi.kodeSurat']);
                                    
        return DataTables::of($suratDispensasi)
                                    ->addColumn('aksi', function ($data) {
                                        return $data->id_surat_masuk;
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
}
