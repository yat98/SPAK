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
use App\SuratKeteranganBebasPerpustakaan;
use App\PengajuanSuratKeteranganBebasPerpustakaan;

class SuratKeteranganBebasPerpustakaanController extends Controller
{
    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratKeteranganBebasPerpustakaan::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratKeteranganBebasPerpustakaan::join('pengajuan_surat_keterangan_bebas_perpustakaan','surat_keterangan_bebas_perpustakaan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perpustakaan.id')
                                                 ->whereNotIn('status',['diajukan'])
                                                 ->count();
             
        return view($this->segmentUser.'.surat_keterangan_bebas_perpustakaan',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllSurat = SuratKeteranganBebasPerpustakaan::join('pengajuan_surat_keterangan_bebas_perpustakaan','surat_keterangan_bebas_perpustakaan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perpustakaan.id')
                                            ->where('status','selesai')
                                            ->count();
        
        $countAllTandaTangan = SuratKeteranganBebasPerpustakaan::join('pengajuan_surat_keterangan_bebas_perpustakaan','surat_keterangan_bebas_perpustakaan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perpustakaan.id')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.pimpinan.surat_keterangan_bebas_perpustakaan',compact('perPage','countAllSurat','countAllTandaTangan'));
    }

    public function getAllSurat(){
        $suratPerpustakaan = PengajuanSuratKeteranganBebasPerpustakaan::join('surat_keterangan_bebas_perpustakaan','surat_keterangan_bebas_perpustakaan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perpustakaan.id')
                                    ->select('surat_keterangan_bebas_perpustakaan.nomor_surat','pengajuan_surat_keterangan_bebas_perpustakaan.*')
                                    ->with(['suratKeteranganBebasPerpustakaan','mahasiswa']);

        if(isset(Auth::user()->id)){
            $suratPerpustakaan = $suratPerpustakaan->whereNotIn('status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'operator perpustakaan'){
                $suratPerpustakaan = $suratPerpustakaan->whereIn('status',['selesai','menunggu tanda tangan']);
            } else{
                $suratPerpustakaan = $suratPerpustakaan->where('status','selesai');
            }
        }

        return DataTables::of($suratPerpustakaan)
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
        $suratPerpustakaan =  PengajuanSuratKeteranganBebasPerpustakaan::join('surat_keterangan_bebas_perpustakaan','surat_keterangan_bebas_perpustakaan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perpustakaan.id')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('nip',Auth::user()->nip)
                                    ->select('surat_keterangan_bebas_perpustakaan.nomor_surat','pengajuan_surat_keterangan_bebas_perpustakaan.*')
                                    ->with(['mahasiswa','suratKeteranganBebasPerpustakaan']);
                                   
        return DataTables::of($suratPerpustakaan)
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

    public function show(SuratKeteranganBebasPerpustakaan $suratPerlengkapan){
        $surat = collect($suratPerlengkapan->load(['pengajuanSuratKeteranganBebasPerpustakaan.mahasiswa.prodi.jurusan','user','pengajuanSuratKeteranganBebasPerpustakaan.operator']));
        
        $surat->put('status',ucwords($suratPerlengkapan->pengajuanSuratKeteranganBebasPerpustakaan->status));
        $surat->put('tahun',$suratPerlengkapan->created_at->isoFormat('Y'));
        $surat->put('dibuat',$suratPerlengkapan->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        
        return json_encode($surat->toArray(),JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    public function progress(PengajuanSuratKeteranganBebasPerpustakaan $pengajuanSurat){
        $pengajuan = $pengajuanSurat->load(['suratKeteranganBebasPerpustakaan.user','mahasiswa']);
        $data = collect($pengajuan);
        $data->put('status', ucwords($pengajuanSurat->status));

        if ($pengajuan->status == 'selesai') {
            $tanggalSelesai = $pengajuanSurat->suratKeteranganBebasPerpustakaan->updated_at->isoFormat('D MMMM Y HH:mm:ss');
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

    public function createSurat(PengajuanSuratKeteranganBebasPerpustakaan $pengajuanSurat){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganPerpustakaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view($this->segmentUser.'.tambah_surat_keterangan_bebas_perpustakaan',compact('userList','kodeSurat','nomorSuratBaru','userList','pengajuanSurat'));
    }

    public function storeSurat(Request $request)
    {
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'kode_surat'=>'required|string',
            'nokta'=>'required|string',
            'kewajiban'=>'required|string',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_keterangan_bebas_perpustakaan,nomor_surat',
            'nip'=>'required',
        ]);
            
        $pengajuanSurat = PengajuanSuratKeteranganBebasPerpustakaan::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kepala perpustakaan')
                      ->first();

        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSurat->id;

        DB::beginTransaction();
        try{
            SuratKeteranganBebasPerpustakaan::create($input);

            $pengajuanSurat->update([
                'status'=>'menunggu tanda tangan',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Keterangan Bebas Perpustakaan',
                'isi_notifikasi'=>'Tanda tangan surat keterangan bebas perpustakaan mahasiswa dengan nama '.$pengajuanSurat->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-keterangan-bebas-perpustakaan')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan bebas perpustakaan gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat keterangan bebas perpustakaan berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
        }
        $suratPerpustakaan = SuratKeteranganBebasPerpustakaan::findOrFail($request->id);

        $suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::create([
            'nim'=>$suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->nim,
            'judul_notifikasi'=>'Surat Keterangan Bebas Perpustakaan',
            'isi_notifikasi'=>'Surat keterangan bebas perpustakaan telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-keterangan-bebas-perpustakaan')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat keterangan bebas perpustakaan berhasil');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
    }

    public function cetak(SuratKeteranganBebasPerpustakaan $suratPerpustakaan){
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->nim){
                abort(404);
            }

            if($suratPerpustakaan->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat keterangan bebas perpustakaan sebanyak 3 kali.');
                return redirect('mahasiswa/surat-keterangan-bebas-perpustakaan');
            }
        }
        
        $data = $suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->mahasiswa->nim.' - '.$suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);

        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratPerpustakaan->jumlah_cetak;
                $suratPerpustakaan->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }      
        }
        $pdf = PDF::loadview('surat.surat_keterangan_bebas_perpustakaan',compact('suratPerpustakaan','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-keterangan-bebas_perpustakaan'.' - '.$suratPerpustakaan->created_at->format('dmY-Him').'.pdf');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratKeteranganBebasPerpustakaan $pengajuanSuratPerpustakaan){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSuratPerpustakaan->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratPerpustakaan->nim,
                'judul_notifikasi'=>'Surat Keterangan Bebas Perpustakaan',
                'isi_notifikasi'=>'Pengajuan surat keterangan bebas perpustakaan di tolak.',
                'link_notifikasi'=>url('mahasiswa/surat-keterangan bebas perpustakaan')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat keterangan bebas perpustakaan gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan bebas perpustakaan mahasiswa dengan nama '.$pengajuanSuratPerpustakaan->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
    }
}
