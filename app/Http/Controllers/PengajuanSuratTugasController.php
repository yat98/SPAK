<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use Carbon\Carbon;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use App\NotifikasiMahasiswa;
use App\PengajuanSuratTugas;
use Illuminate\Http\Request;
use App\DaftarTugasMahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PengajuanSuratTugasRequest;

class PengajuanSuratTugasController extends Controller
{
    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratTugas::join('daftar_tugas_mahasiswa','daftar_tugas_mahasiswa.id_pengajuan','=','pengajuan_surat_tugas.id')
                                    ->where('daftar_tugas_mahasiswa.nim',Auth::user()->nim)
                                    ->select('pengajuan_surat_tugas.*'))
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
                $pengajuanSurat = PengajuanSuratTugas::whereIn('pengajuan_surat_tugas.status',['diajukan','ditolak'])
                                                            ->where('id_operator',Auth::user()->id);

            }elseif(Auth::user()->bagian == 'subbagian kemahasiswaan'){
                $pengajuanSurat = PengajuanSuratTugas::whereIn('pengajuan_surat_tugas.status',['diajukan','ditolak']);
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
            $pengajuanSurat = PengajuanSuratTugas::join('surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                        ->select('pengajuan_surat_tugas.*')
                                        ->with(['suratTugas.kodeSurat']);
            
            if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_tugas.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_tugas.status','verifikasi kabag');
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

    public function getAllPengajuanByNim(Mahasiswa $mahasiswa){
        return DataTables::of(PengajuanSuratTugas::join('daftar_tugas_mahasiswa','daftar_tugas_mahasiswa.id_pengajuan','=','pengajuan_surat_tugas.id')
                                    ->where('daftar_tugas_mahasiswa.nim',$mahasiswa->nim)
                                    ->select('pengajuan_surat_tugas.*'))
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

    public function create(){
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.tambah_pengajuan_surat_tugas',compact('mahasiswa'));
    }

    public function update(PengajuanSuratTugasRequest $request, PengajuanSuratTugas $pengajuanSurat){
        $input = $request->all();
        DB::beginTransaction();
        try{
            $pengajuanSurat->update($input);
            $pengajuanSurat->mahasiswa()->sync($request->nim);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat tugas gagal diubah.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat tugas berhasil diubah');
        return redirect($this->segmentUser.'/surat-tugas');
    }

    public function edit(PengajuanSuratTugas $pengajuanSurat)
    {   
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_tugas',compact('mahasiswa','pengajuanSurat'));
    }

    public function show(PengajuanSuratTugas $pengajuanSurat){
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

    public function store(PengajuanSuratTugasRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;
        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();

        DB::beginTransaction();
        try{
            $pengajuanSuratTugas = PengajuanSuratTugas::create($input);

            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Tugas',
                'isi_notifikasi'=>'Front office membuat pengajuan surat tugas.',
                'link_notifikasi'=>url('operator/surat-tugas')
            ]);

            $daftarMahasiswa= [];
            $notifMahasiswa= [];
            foreach ($request->nim as $nim) {
                $daftarMahasiswa[] = [
                    'id_pengajuan'=>$pengajuanSuratTugas->id,
                    'nim'=>$nim,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
                $notifMahasiswa[] = [
                    'nim'=>$nim,
                    'judul_notifikasi'=>'Surat Tugas',
                    'isi_notifikasi'=>'Pengajuan surat tugas telah selesai dibuat.',
                    'link_notifikasi'=>url('mahasiswa/surat-tugas'), 
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
            }
            DaftarTugasMahasiswa::insert($daftarMahasiswa);
            NotifikasiMahasiswa::insert($notifMahasiswa);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat tugas gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat tugas berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-tugas');
    }

    public function destroy(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat tugas berhasil dihapus');
        return redirect($this->segmentUser.'/surat-tugas');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratTugas::findOrFail($request->id);
        $user = $pengajuan->suratTugas->user;

        $isiNotifikasi = 'Verifikasi surat tugas';

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratTugas->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat tugas';
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
                'judul_notifikasi'=>'Surat Tugas',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-tugas'),
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat tugas gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat tugas berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-tugas');
    }
}
