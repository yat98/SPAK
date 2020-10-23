<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use Carbon\Carbon;
use App\SuratMasuk;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratDispensasi;
use App\DaftarDispensasiMahasiswa;
use App\TahapanKegiatanDispensasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PengajuanSuratDispensasiRequest;

class PengajuanSuratDispensasiController extends Controller
{
    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratDispensasi::join('daftar_dispensasi_mahasiswa','daftar_dispensasi_mahasiswa.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                    ->where('daftar_dispensasi_mahasiswa.nim',Auth::user()->nim)
                                    ->select('pengajuan_surat_dispensasi.*','daftar_dispensasi_mahasiswa.nim'))
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
                $pengajuanSurat = PengajuanSuratDispensasi::whereIn('status',['diajukan','ditolak'])
                                                            ->where('id_operator',Auth::user()->id);

            }elseif(Auth::user()->bagian == 'subbagian kemahasiswaan'){
                $pengajuanSurat = PengajuanSuratDispensasi::whereIn('status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->with(['mahasiswa','suratMasuk','operator','tahapanKegiatanDispensasi']);

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
            $pengajuanSurat = PengajuanSuratDispensasi::join('surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                        ->select('pengajuan_surat_dispensasi.*')
                                        ->with(['suratDispensasi.kodeSurat']);
            
            if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_dispensasi.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_dispensasi.status','verifikasi kabag');
            }

            return DataTables::of($pengajuanSurat)
                            ->addColumn('aksi', function ($data) {
                                return $data->id_pengajuan;
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

    public function getAllPengajuanByNim(Mahasiswa $mahasiswa){
        return DataTables::of(PengajuanSuratDispensasi::join('daftar_dispensasi_mahasiswa','daftar_dispensasi_mahasiswa.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                    ->where('daftar_dispensasi_mahasiswa.nim',$mahasiswa->nim)
                                    ->select('pengajuan_surat_dispensasi.*','daftar_dispensasi_mahasiswa.nim'))
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
    }

    public function show(PengajuanSuratDispensasi $pengajuanSurat){
        $tanggalKegiatan = [];
        $surat = collect($pengajuanSurat->load(['mahasiswa.prodi.jurusan','tahapanKegiatanDispensasi','suratMasuk','operator']));
        foreach ($pengajuanSurat->tahapanKegiatanDispensasi as $key => $tahapan) {
            if($tahapan->tanggal_awal_kegiatan->equalTo($tahapan->tanggal_akhir_kegiatan)){
                $tanggal = $tahapan->tanggal_awal_kegiatan->isoFormat('D MMMM Y');
            }elseif($tahapan->tanggal_awal_kegiatan->isSameMonth($tahapan->tanggal_akhir_kegiatan)){  
                $tanggal = $tahapan->tanggal_awal_kegiatan->isoFormat('D').' s/d '.$tahapan->tanggal_akhir_kegiatan->isoFormat('D').' '.$tahapan->tanggal_awal_kegiatan->isoFormat('MMMM Y');
            }else{
                $tanggal =  $tahapan->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' s/d '.$tahapan->tanggal_akhir_kegiatan->isoFormat('D MMMM Y');
            }
            $tanggalKegiatan[] = $tanggal;
        }
        $surat->put('status',ucwords($pengajuanSurat->status));
        $surat->put('tanggal_kegiatan',$tanggalKegiatan);
        $surat->put('nama_file',explode('.',$pengajuanSurat->suratMasuk->file_surat_masuk)[0]);
        $surat->put('link_file',asset('upload_surat_masuk/'.$pengajuanSurat->suratMasuk->file_surat_masuk));
        $surat->put('dibuat',$pengajuanSurat->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        return $surat->toJson();
    }

    public function create(){
        $mahasiswa = $this->generateMahasiswa();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        return view($this->segmentUser.'.tambah_pengajuan_surat_dispensasi',compact('mahasiswa','suratMasuk'));
    }

    public function store(PengajuanSuratDispensasiRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;
        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();

        DB::beginTransaction();
        try{
            PengajuanSuratDispensasi::create($input);
            
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Dispensasi',
                'isi_notifikasi'=>'Front office membuat pengajuan surat dispensasi.',
                'link_notifikasi'=>url('operator/surat-dispensasi')
            ]);

            $daftarMahasiswa= [];
            $notifMahasiswa= [];
            $tahapanKegiatan = [];
            foreach ($request->nim as $nim) {
                $daftarMahasiswa[] = [
                    'id_pengajuan'=>$request->id_surat_masuk,
                    'nim'=>$nim,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
                $notifMahasiswa[] = [
                    'nim'=>$nim,
                    'judul_notifikasi'=>'Surat Dispensasi',
                    'isi_notifikasi'=>'Pengajuan surat dispensasi telah selesai dibuat.',
                    'link_notifikasi'=>url('mahasiswa/surat-dispensasi'), 
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
            }
            $i = 0;
            while ($i < $request->jumlah) {
                $tahapanKegiatan[] = [
                    'id_pengajuan'=>$request->id_surat_masuk,
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
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat dispensasi gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat dispensasi berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }

    public function edit(PengajuanSuratDispensasi $pengajuanSurat)
    {   
        $mahasiswa = $this->generateMahasiswa();
        $suratMasuk = SuratMasuk::pluck('nomor_surat','id');
        return view($this->segmentUser.'.edit_pengajuan_surat_dispensasi',compact('mahasiswa','pengajuanSurat','suratMasuk'));
    }

    public function update(PengajuanSuratDispensasiRequest $request, PengajuanSuratDispensasi $pengajuanSurat){
        $input = $request->all();
        DB::beginTransaction();
        try{
            $pengajuanSurat->update($input);
            $pengajuanSurat->mahasiswa()->sync($request->nim);
            $i = 0;
            if($request->jumlah == count($request->id_tahapan)){
                while ($i < $request->jumlah) {
                    $tahapan = [
                        'id_pengajuan'=>$request->id_surat_masuk,
                        'tahapan_kegiatan'=>$request->tahapan_kegiatan[$i],
                        'tempat_kegiatan'=>$request->tempat_kegiatan[$i],
                        'tanggal_awal_kegiatan'=>$request->tanggal_awal_kegiatan[$i],
                        'tanggal_akhir_kegiatan'=>$request->tanggal_akhir_kegiatan[$i],
                    ];
                    $tahapanKegiatan = TahapanKegiatanDispensasi::findOrFail($request->id_tahapan[$i]);
                    $tahapanKegiatan->update($tahapan);
                    $i++;
                }
            }else{
                while ($i < count($request->id_tahapan)) {
                    $tahapan = [
                        'id_pengajuan'=>$request->id_surat_masuk,
                        'tahapan_kegiatan'=>$request->tahapan_kegiatan[$i],
                        'tempat_kegiatan'=>$request->tempat_kegiatan[$i],
                        'tanggal_awal_kegiatan'=>$request->tanggal_awal_kegiatan[$i],
                        'tanggal_akhir_kegiatan'=>$request->tanggal_akhir_kegiatan[$i],
                    ];
                    $tahapanKegiatan = TahapanKegiatanDispensasi::findOrFail($request->id_tahapan[$i]);
                    $tahapanKegiatan->update($tahapan);
                    $i++;
                }
                TahapanKegiatanDispensasi::create([
                    'id_pengajuan'=>$request->id_surat_masuk,
                    'tahapan_kegiatan'=>$request->tahapan_kegiatan[count($request->id_tahapan)-1],
                    'tempat_kegiatan'=>$request->tempat_kegiatan[count($request->id_tahapan)-1],
                    'tanggal_awal_kegiatan'=>$request->tanggal_awal_kegiatan[count($request->id_tahapan)-1],
                    'tanggal_akhir_kegiatan'=>$request->tanggal_akhir_kegiatan[count($request->id_tahapan)-1],
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat dispensasi gagal diubah.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat dispensasi berhasil diubah');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }

    public function destroy(PengajuanSuratDispensasi $pengajuanSurat)
    {
        $pengajuanSurat->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat dispensasi berhasil dihapus');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratDispensasi::findOrFail($request->id);
        $user = $pengajuan->suratDispensasi->user;

        $isiNotifikasi = 'Verifikasi surat dispensasi';

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratDispensasi->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat dispensasi';
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
                'judul_notifikasi'=>'Surat Dispensasi',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-dispensasi'),
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat dispensasi gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat dispensasi berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-dispensasi');
    }
}
