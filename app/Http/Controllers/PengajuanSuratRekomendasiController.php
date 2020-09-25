<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use App\Operator;
use Carbon\Carbon;
use App\SuratMasuk;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratRekomendasi;
use Illuminate\Support\Facades\DB;
use App\DaftarRekomendasiMahasiswa;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PengajuanSuratRekomendasiRequest;

class PengajuanSuratRekomendasiController extends Controller
{
    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratRekomendasi::join('daftar_rekomendasi_mahasiswa','daftar_rekomendasi_mahasiswa.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                    ->where('daftar_rekomendasi_mahasiswa.nim',Auth::user()->nim)
                                    ->select('pengajuan_surat_rekomendasi.*'))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
        } else if(isset(Auth::user()->id)){
            $pengajuanSurat;

            if(Auth::user()->bagian == 'front office'){
                $pengajuanSurat = PengajuanSuratRekomendasi::whereIn('status',['diajukan','ditolak'])
                                                            ->where('id_operator',Auth::user()->id);

            }elseif(Auth::user()->bagian == 'subbagian kemahasiswaan'){
                $pengajuanSurat = PengajuanSuratRekomendasi::whereIn('status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->with(['mahasiswa','operator']);

            return DataTables::of($pengajuanSurat)
                        ->addColumn('aksi', function ($data) {
                            return $data->id_surat_masuk;
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
        } else if(isset(Auth::user()->nip)){
            $pengajuanSurat = PengajuanSuratRekomendasi::join('surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                        ->select('pengajuan_surat_rekomendasi.*')
                                        ->with(['suratRekomendasi.kodeSurat']);
            
            if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
                $pengajuanSurat = $pengajuanSurat->where('status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('status','verifikasi kabag');
            }

            return DataTables::of($pengajuanSurat)
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
    }

    public function show(PengajuanSuratRekomendasi $pengajuanSurat){
        $surat = collect($pengajuanSurat->load(['mahasiswa.prodi.jurusan','operator']));
        if($pengajuanSurat->tanggal_awal_kegiatan->equalTo($pengajuanSurat->tanggal_akhir_kegiatan)){
            $tanggal = $pengajuanSurat->tanggal_awal_kegiatan->isoFormat('D MMMM Y');
        }elseif($pengajuanSurat->tanggal_awal_kegiatan->isSameMonth($pengajuanSurat->tanggal_akhir_kegiatan)){  
            $tanggal = $pengajuanSurat->tanggal_awal_kegiatan->isoFormat('D').' - '.$pengajuanSurat->tanggal_akhir_kegiatan->isoFormat('D').' '.$pengajuanSurat->tanggal_awal_kegiatan->isoFormat('MMMM Y');
        }else{
            $tanggal =  $pengajuanSurat->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' - '.$pengajuanSurat->tanggal_akhir_kegiatan->isoFormat('D MMMM Y');
        }
        $surat->put('tanggal',$tanggal); 
        $surat->put('status',ucwords($pengajuanSurat->status)); 
        $surat->put('dibuat',$pengajuanSurat->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        return $surat->toJson();
    }

    public function create(){
        $mahasiswa = $this->generateMahasiswa();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        return view($this->segmentUser.'.tambah_pengajuan_surat_rekomendasi',compact('mahasiswa','suratMasuk'));
    }

    public function edit(PengajuanSuratRekomendasi $pengajuanSurat)
    {   
        $mahasiswa = $this->generateMahasiswa();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        return view($this->segmentUser.'.edit_pengajuan_surat_rekomendasi',compact('mahasiswa','pengajuanSurat','suratMasuk'));
    }

    public function update(PengajuanSuratRekomendasiRequest $request, PengajuanSuratRekomendasi $pengajuanSurat){
        $input = $request->all();
        DB::beginTransaction();
        try{
            $pengajuanSurat->update($input);
            $pengajuanSurat->mahasiswa()->sync($request->nim);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat rekomendasi gagal diubah.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi berhasil diubah');
        return redirect($this->segmentUser.'/surat-rekomendasi');
    }

    public function destroy(PengajuanSuratRekomendasi $pengajuanSurat)
    {
        $pengajuanSurat->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi berhasil dihapus');
        return redirect($this->segmentUser.'/surat-rekomendasi');
    }

    public function store(PengajuanSuratRekomendasiRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;
        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();

        DB::beginTransaction();
        try{
            $pengajuanSuratRekomendasi = PengajuanSuratRekomendasi::create($input);

            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Rekomendasi',
                'isi_notifikasi'=>'Front office membuat pengajuan surat rekomendasi.',
                'link_notifikasi'=>url('operator/surat-rekomendasi')
            ]);

            $daftarMahasiswa= [];
            $notifMahasiswa= [];
            foreach ($request->nim as $nim) {
                $daftarMahasiswa[] = [
                    'id_pengajuan'=>$pengajuanSuratRekomendasi->id,
                    'nim'=>$nim,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
                $notifMahasiswa[] = [
                    'nim'=>$nim,
                    'judul_notifikasi'=>'Surat Rekomendasi',
                    'isi_notifikasi'=>'Pengajuan surat rekomendasi telah selesai dibuat.',
                    'link_notifikasi'=>url('mahasiswa/surat-rekomendasi'), 
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
            }
            DaftarRekomendasiMahasiswa::insert($daftarMahasiswa);
            NotifikasiMahasiswa::insert($notifMahasiswa);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat rekomendasi gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-rekomendasi');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratRekomendasi::findOrFail($request->id);
        $user = $pengajuan->suratRekomendasi->user;

        $isiNotifikasi = 'Verifikasi surat rekomendasi';

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratRekomendasi->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat rekomendasi';
        }

        DB::beginTransaction();
        try{
            $pengajuan->update([
                'status'=>$status,
            ]);
            
            if($status == 'verifikasi kabag'){
                $user = User::where('jabatan','kabag tata usaha')->where('status_aktif','aktif')->first();
            }

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Rekomendasi',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-rekomendasi'),
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat rekomendasi gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat rekomendasi berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-rekomendasi');
    }
}
