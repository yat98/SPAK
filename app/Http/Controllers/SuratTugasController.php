<?php

namespace App\Http\Controllers;

use DB;
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
use App\NotifikasiMahasiswa;
use App\PengajuanSuratTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SuratTugasRequest;

class SuratTugasController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratTugas::where('status','verifikasi kasubag')
                                                        ->count();

        $countAllSurat = SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                          ->whereIn('pengajuan_surat_tugas.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                          ->count();

        return view('user.'.$this->segmentUser.'.surat_tugas',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratTugas::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                            ->whereNotIn('pengajuan_surat_tugas.status',['diajukan'])
                                            ->count();
        
        return view($this->segmentUser.'.surat_tugas',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratTugas::where('status','verifikasi kabag')
                                                        ->count();
                                            
        $countAllSurat = SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                          ->where('pengajuan_surat_tugas.status','selesai')
                                          ->count();
        
        $countAllTandaTangan = SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                            ->where('pengajuan_surat_tugas.status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.'.$this->segmentUser.'.surat_tugas',compact('perPage','countAllVerifikasi','countAllSurat','countAllTandaTangan'));
    }

    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $countAllSurat = PengajuanSuratTugas::join('daftar_tugas_mahasiswa','daftar_tugas_mahasiswa.id_pengajuan','=','pengajuan_surat_tugas.id')
                                              ->where('daftar_tugas_mahasiswa.nim',Auth::user()->nim)
                                              ->count();

        return view($this->segmentUser.'.surat_tugas',compact('perPage','countAllSurat'));
    }

    public function getAllSuratTugas(){
        $suratTugas = PengajuanSuratTugas::join('surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                    ->select('surat_tugas.nomor_surat','pengajuan_surat_tugas.*')
                                    ->with(['suratTugas.kodeSurat']);

        if(isset(Auth::user()->id)){
            $suratTugas = $suratTugas->whereNotIn('pengajuan_surat_tugas.status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratTugas = $suratTugas->whereIn('pengajuan_surat_tugas.status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratTugas = $suratTugas->where('pengajuan_surat_tugas.status','selesai');
            }
        }

        return DataTables::of($suratTugas)
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
        $suratTugas =  PengajuanSuratTugas::join('surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                    ->where('pengajuan_surat_tugas.status','menunggu tanda tangan')
                                    ->where('surat_tugas.nip',Auth::user()->nip)
                                    ->select('surat_tugas.nomor_surat','pengajuan_surat_tugas.*')
                                    ->with(['suratTugas.kodeSurat']);
                                    
        return DataTables::of($suratTugas)
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

    public function createSurat(PengajuanSuratTugas $pengajuanSuratTugas){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-tugas');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view('operator.tambah_surat_tugas',compact('userList','kodeSurat','nomorSuratBaru','pengajuanSuratTugas'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat',
            'nip'=>'required',
        ]);
        
        $input = $request->all();
        $pengajuanSuratTugas = PengajuanSuratTugas::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag kemahasiswaan')
                      ->first();

        DB::beginTransaction();
        try{
            $suratTugas = SuratTugas::create($input);

            $pengajuanSuratTugas->update([
                'status'=>'verifikasi kasubag'
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Tugas',
                'isi_notifikasi'=>'Verifikasi surat tugas.',
                'link_notifikasi'=>url('pegawai/surat-tugas')
            ]);
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
        $surat = collect($suratTugas->load(['pengajuanSuratTugas.operator','pengajuanSuratTugas.mahasiswa.prodi.jurusan','user','kodeSurat']));
        $kodeSurat = explode('/',$suratTugas->kodeSurat->kode_surat);
        if($suratTugas->pengajuanSuratTugas->tanggal_awal_kegiatan->equalTo($suratTugas->pengajuanSuratTugas->tanggal_akhir_kegiatan)){
            $tanggal = $suratTugas->pengajuanSuratTugas->tanggal_awal_kegiatan->isoFormat('D MMMM Y');
        }elseif($suratTugas->pengajuanSuratTugas->tanggal_awal_kegiatan->isSameMonth($suratTugas->pengajuanSuratTugas->tanggal_akhir_kegiatan)){  
            $tanggal = $suratTugas->pengajuanSuratTugas->tanggal_awal_kegiatan->isoFormat('D').' - '.$suratTugas->pengajuanSuratTugas->tanggal_akhir_kegiatan->isoFormat('D').' '.$suratTugas->pengajuanSuratTugas->tanggal_awal_kegiatan->isoFormat('MMMM Y');
        }else{
            $tanggal =  $suratTugas->pengajuanSuratTugas->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' - '.$suratTugas->pengajuanSuratTugas->tanggal_akhir_kegiatan->isoFormat('D MMMM Y');
        }
        $surat->put('status',ucwords($suratTugas->pengajuanSuratTugas->status));
        $surat->put('tanggal',$tanggal);
        $surat->put('status',ucwords($suratTugas->pengajuanSuratTugas->status));
        $surat->put('tahun',$suratTugas->created_at->isoFormat('Y'));
        $surat->put('dibuat',$suratTugas->created_at->isoFormat('D MMMM Y HH:mm:ss'));
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

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-tugas');
        }
        $notifMahasiswa = [];
        $pengajuanSuratTugas = PengajuanSuratTugas::findOrFail($request->id);

        foreach ($pengajuanSuratTugas->mahasiswa as $mahasiswa) {
            $notifMahasiswa[] = [
               'nim'=>$mahasiswa->nim,
               'judul_notifikasi'=>'Surat Tugas',
               'isi_notifikasi'=>'Surat tugas telah di tanda tangani.',
               'link_notifikasi'=>url('mahasiswa/surat-tugas'), 
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now(),
            ];
        }

        $pengajuanSuratTugas->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::insert($notifMahasiswa);
        $this->setFlashData('success','Berhasil','Tanda tangan surat tugas berhasil');
        return redirect($this->segmentUser.'/surat-tugas');
    }

    public function cetak(SuratTugas $suratTugas){
        if(isset(Auth::user()->nim)){
            $nim = $suratTugas->pengajuanSuratTugas->mahasiswa->map(function ($mahasiswa) {
                return strtoupper($mahasiswa->nim);
            })->toArray();

            if(!in_array(Auth::user()->nim,$nim)){ 
                abort(404);
            }

            if($suratTugas->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat tugas sebanyak 3 kali.');
                return redirect('mahasiswa/surat-tugas');
            }
        }

        $data = '';
        foreach ($suratTugas->pengajuanSuratTugas->mahasiswa as $value) {
            $data .= $value->nim.' - '.$value->nama.' - '.$value->prodi->nama_prodi.' ';
        }

        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",3,3);
        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratTugas->jumlah_cetak;
                $suratTugas->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }
        }
        
        $pdf = PDF::loadview('surat.surat_tugas',compact('suratTugas','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-tugas'.' - '.$suratTugas->created_at->format('dmY-Him').'.pdf');
    }

    public function progress(PengajuanSuratTugas $pengajuanSuratTugas){
        $data = collect($pengajuanSuratTugas);
        $data->put('status',ucwords($pengajuanSuratTugas->status));

        if($pengajuanSuratTugas->status == 'selesai'){
            $tanggalSelesai = $pengajuanSuratTugas->suratTugas->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalSelesai);
        }else if($pengajuanSuratTugas->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSuratTugas->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalDitolak);
        }else{
            $tanggal = $pengajuanSuratTugas->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggal);
        }

        return $data->toJson();
    }
}
