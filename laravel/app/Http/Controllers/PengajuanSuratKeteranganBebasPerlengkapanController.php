<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratKeteranganBebasPerlengkapan;
use App\Http\Requests\PengajuanSuratKeteranganBebasPerlengkapanRequest;

class PengajuanSuratKeteranganBebasPerlengkapanController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratKeteranganBebasPerlengkapan::where('nim',Auth::user()->nim)
                                                                        ->count();
        
        return view($this->segmentUser.'.surat_keterangan_bebas_perlengkapan',compact('countAllPengajuan','perPage'));
    }

    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratKeteranganBebasPerlengkapan::where('pengajuan_surat_keterangan_bebas_perlengkapan.nim',Auth::user()->nim)
                                    ->select('mahasiswa.nama','pengajuan_surat_keterangan_bebas_perlengkapan.*','mahasiswa.nim')
                                    ->join('mahasiswa','pengajuan_surat_keterangan_bebas_perlengkapan.nim','=','mahasiswa.nim')
                                    ->with(['mahasiswa']))
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
                $pengajuanSurat = PengajuanSuratKeteranganBebasPerlengkapan::whereIn('status',['diajukan','ditolak'])
                                                 ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian umum & bmn'){
                $pengajuanSurat = PengajuanSuratKeteranganBebasPerlengkapan::whereIn('status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->join('mahasiswa','pengajuan_surat_keterangan_bebas_perlengkapan.nim','=','mahasiswa.nim')
                                             ->select('mahasiswa.nama','pengajuan_surat_keterangan_bebas_perlengkapan.*','mahasiswa.nim')
                                             ->with(['mahasiswa']);

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
        } else if(isset(Auth::user()->nip)){
            $pengajuanSurat = PengajuanSuratKeteranganBebasPerlengkapan::join('surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                                        ->select('surat_keterangan_bebas_perlengkapan.nomor_surat','pengajuan_surat_keterangan_bebas_perlengkapan.*')
                                                        ->with(['mahasiswa','suratKeteranganBebasPerlengkapan.kodeSurat']);
                                                        
            if (Auth::user()->jabatan == 'kasubag umum & bmn') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan_bebas_perlengkapan.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan_bebas_perlengkapan.status','verifikasi kabag');
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
        return DataTables::of(PengajuanSuratKeteranganBebasPerlengkapan::where('pengajuan_surat_keterangan_bebas_perlengkapan.nim',$mahasiswa->nim)
                                    ->select('mahasiswa.nama','pengajuan_surat_keterangan_bebas_perlengkapan.*','mahasiswa.nim')
                                    ->join('mahasiswa','pengajuan_surat_keterangan_bebas_perlengkapan.nim','=','mahasiswa.nim')
                                    ->with(['mahasiswa']))
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

    public function createPengajuan(){
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists()){
                return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
            }
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_bebas_perlengkapan');
        }else{
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_bebas_perlengkapan',compact('mahasiswa'));
        }
    }

    public function edit(PengajuanSuratKeteranganBebasPerlengkapan $pengajuanSurat)
    {   
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_keterangan_bebas_perlengkapan',compact('mahasiswa','pengajuanSurat'));
    }

    public function update(PengajuanSuratKeteranganBebasPerlengkapanRequest $request, PengajuanSuratKeteranganBebasPerlengkapan $pengajuanSurat)
    {
        $input = $request->all();
        $pengajuanSurat->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan bebas perlengkapan berhasil diubah.');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
    }

    public function destroy(PengajuanSuratKeteranganBebasPerlengkapan $pengajuanSurat)
    {
        $pengajuanSurat->delete();;
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan bebas perlengkapan berhasil dihapus');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratKeteranganBebasPerlengkapan::findOrFail($request->id);
        $user = $pengajuan->suratKeteranganBebasPerlengkapan->user;

        $isiNotifikasi = 'Verifikasi surat keterangan bebas perlengkapan mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratKeteranganBebasPerlengkapan->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat keterangan bebas perlengkapan mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;
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
                'judul_notifikasi'=>'Surat Keterangan Bebas Perlengkapan',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-keterangan-bebas-perlengkapan')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat keterangan bebas perlengkapan gagal diverifikasi.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat keterangan bebas perlengkapan berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
    }

    public function show(PengajuanSuratKeteranganBebasPerlengkapan $pengajuanSurat){
        $pengajuan = collect($pengajuanSurat->load(['mahasiswa.prodi.jurusan','operator']));
        $pengajuan->put('status', ucwords($pengajuanSurat->status));
        $pengajuan->put('dibuat', $pengajuanSurat->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        
        return $pengajuan->toJson();
    }

    public function progress(){

    }

    public function storePengajuan(PengajuanSuratKeteranganBebasPerlengkapanRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;
        $operator = Operator::where('bagian','subbagian umum & bmn')->where('status_aktif','aktif')->first();
        
        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan bebas perlengkapan.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat keterangan bebas perlengkapan dengan nama mahasiswa '.$mahasiswa->nama;
        }

        DB::beginTransaction();
        try{ 
            PengajuanSuratKeteranganBebasPerlengkapan::create($input);
            
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Keterangan Bebas Perlengkapan',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-keterangan-bebas-perlengkapan')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat keterangan bebas perlengkapan gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan bebas perlengkapan berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perlengkapan');
    }

    private function isSuratDiajukanExists(){
        $suratPerlengkapan = PengajuanSuratKeteranganBebasPerlengkapan::where('nim',Auth::user()->nim)
                                ->where('status','diajukan')
                                ->exists();
        if($suratPerlengkapan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat keterangan bebas perlengkapan sementara diproses!');
            return false;
        }
        return true;
    }
}
