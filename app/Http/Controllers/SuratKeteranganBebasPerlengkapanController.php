<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use DataTables;
use App\KodeSurat;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\SuratKeteranganBebasPerlengkapan;
use App\PengajuanSuratKeteranganBebasPerlengkapan;

class SuratKeteranganBebasPerlengkapanController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        
        $countAllSurat = SuratKeteranganBebasPerlengkapan::join('pengajuan_surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                                 ->whereIn('pengajuan_surat_keterangan_bebas_perlengkapan.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                 ->count();
        
        $countAllVerifikasi = PengajuanSuratKeteranganBebasPerlengkapan::where('status','verifikasi kasubag')
                                                             ->count();

        $countAllTandaTangan = SuratKeteranganBebasPerlengkapan::join('pengajuan_surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                                             ->where('status','menunggu tanda tangan')
                                                             ->where('nip',Auth::user()->nip)
                                                             ->count();

        return view('user.'.$this->segmentUser.'.surat_keterangan_bebas_perlengkapan',compact('perPage','countAllSurat','countAllVerifikasi','countAllTandaTangan'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratKeteranganBebasPerlengkapan::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratKeteranganBebasPerlengkapan::join('pengajuan_surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                                 ->whereNotIn('status',['diajukan'])
                                                 ->count();

        return view($this->segmentUser.'.surat_keterangan_bebas_perlengkapan',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratKeteranganBebasPerlengkapan::where('status','verifikasi kabag')
                                            ->count();

        $countAllSurat = SuratKeteranganBebasPerlengkapan::join('pengajuan_surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                            ->where('status','selesai')
                                            ->count();
        
        $countAllTandaTangan = SuratKeteranganBebasPerlengkapan::join('pengajuan_surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.pimpinan.surat_keterangan_bebas_perlengkapan',compact('countAllVerifikasi','perPage','countAllSurat','countAllTandaTangan'));
    }

    public function getAllSurat(){
        $suratPerlengkapan = PengajuanSuratKeteranganBebasPerlengkapan::join('surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                    ->select('surat_keterangan_bebas_perlengkapan.nomor_surat','pengajuan_surat_keterangan_bebas_perlengkapan.*')
                                    ->with(['suratKeteranganBebasPerlengkapan.kodeSurat','mahasiswa']);

        if(isset(Auth::user()->id)){
            $suratPerlengkapan = $suratPerlengkapan->whereNotIn('status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag umum & bmn'){
                $suratPerlengkapan = $suratPerlengkapan->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratPerlengkapan = $suratPerlengkapan->where('status','selesai');
            }
        }

        return DataTables::of($suratPerlengkapan)
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
        $suratPerlengkapan =  PengajuanSuratKeteranganBebasPerlengkapan::join('surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('nip',Auth::user()->nip)
                                    ->select('surat_keterangan_bebas_perlengkapan.nomor_surat','pengajuan_surat_keterangan_bebas_perlengkapan.*')
                                    ->with(['mahasiswa','suratKeteranganBebasPerlengkapan.kodeSurat']);
                                   
        return DataTables::of($suratPerlengkapan)
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

    public function createSurat(PengajuanSuratKeteranganBebasPerlengkapan $pengajuanSurat){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganUmumDanBMN();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view($this->segmentUser.'.tambah_surat_keterangan_bebas_perlengkapan',compact('userList','kodeSurat','nomorSuratBaru','userList','pengajuanSurat'));
    }

    public function storeSurat(Request $request)
    {
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat|unique:surat_keterangan_bebas_perlengkapan,nomor_surat',
            'nip'=>'required',
        ]);
            
        $pengajuanSurat = PengajuanSuratKeteranganBebasPerlengkapan::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag umum & bmn')
                      ->first();

        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSurat->id;

        DB::beginTransaction();
        try{
            SuratKeteranganBebasPerlengkapan::create($input);

            $pengajuanSurat->update([
                'status'=>'verifikasi kasubag',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Keterangan Bebas Perlengkapan',
                'isi_notifikasi'=>'Verifikasi surat keterangan bebas perlengkapan mahasiswa dengan nama '.$pengajuanSurat->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-keterangan-bebas-perlengkapan')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan bebas perlengkapan gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat keterangan bebas perlengkapan berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
    }

    public function show(SuratKeteranganBebasPerlengkapan $suratPerlengkapan){
        $surat = collect($suratPerlengkapan->load(['pengajuanSuratKeteranganBebasPerlengkapan.mahasiswa.prodi.jurusan','kodeSurat','user','pengajuanSuratKeteranganBebasPerlengkapan.operator']));
        
        $surat->put('status',ucwords($suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->status));
        $surat->put('tahun',$suratPerlengkapan->created_at->isoFormat('Y'));
        $surat->put('dibuat',$suratPerlengkapan->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        
        return $surat->toJson();
    }

    public function cetak(SuratKeteranganBebasPerlengkapan $suratPerlengkapan){
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->nim){
                abort(404);
            }

            if($suratPerlengkapan->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat keterangan bebas perlengkapan sebanyak 3 kali.');
                return redirect('mahasiswa/surat-keterangan-bebas-perlengkapan');
            }
        }

        $data = $suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->mahasiswa->nim.' - '.$suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);
        
        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratPerlengkapan->jumlah_cetak;
                $suratPerlengkapan->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }      
        }

        $pdf = PDF::loadview('surat.surat_keterangan_bebas_perlengkapan',compact('suratPerlengkapan','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-keterangan-bebas-perlengkapan'.' - '.$suratPerlengkapan->created_at->format('dmY-Him').'.pdf');
    }

    public function progress(PengajuanSuratKeteranganBebasPerlengkapan $pengajuanSurat){
        $pengajuan = $pengajuanSurat->load(['suratKeteranganBebasPerlengkapan.user','mahasiswa']);
        $data = collect($pengajuan);
        $data->put('status', ucwords($pengajuanSurat->status));

        if ($pengajuan->status == 'selesai') {
            $tanggalSelesai = $pengajuanSurat->suratKeteranganBebasPerlengkapan->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal', $tanggalSelesai);
        } elseif ($pengajuan->status == 'ditolak') {
            $tanggalDitolak = $pengajuanSurat->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal', $tanggalDitolak);
        } else {
            $tanggal = $pengajuanSurat->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal', $tanggal);
        }

        return $data->toJson();
    }

    public function tolakPengajuan(Request $request, PengajuanSuratKeteranganBebasPerlengkapan $pengajuanSurat){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSurat->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSurat->nim,
                'judul_notifikasi'=>'Surat Keterangan Bebas Perlengkapan',
                'isi_notifikasi'=>'Pengajuan surat keterangan bebas perlengkapan data awal ditolak.',
                'link_notifikasi'=>url('mahasiswa/surat-keterangan-bebas-perlengkapan')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat keterangan bebas perlengkapan gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan bebas perlengkapan berhasil ditolak');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
        }
        $suratPerlengkapan = SuratKeteranganBebasPerlengkapan::findOrFail($request->id);

        $suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::create([
            'nim'=>$suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->nim,
            'judul_notifikasi'=>'Surat Keterangan Bebas Perlengkapan',
            'isi_notifikasi'=>'Surat keterangan bebas perlengkapan telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-keterangan-bebas-perlengkapan')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat keterangan bebas perlengkapan berhasil');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
    }
}
