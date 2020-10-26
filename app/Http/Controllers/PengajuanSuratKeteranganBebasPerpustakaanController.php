<?php

namespace App\Http\Controllers;

use Session;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratKeteranganBebasPerpustakaan;
use App\Http\Requests\PengajuanSuratKeteranganBebasPerpustakaanRequest;

class PengajuanSuratKeteranganBebasPerpustakaanController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratKeteranganBebasPerpustakaan::where('nim',Auth::user()->nim)
                                                                        ->count();
        
        return view($this->segmentUser.'.surat_keterangan_bebas_perpustakaan',compact('countAllPengajuan','perPage'));
    }

    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratKeteranganBebasPerpustakaan::where('pengajuan_surat_keterangan_bebas_perpustakaan.nim',Auth::user()->nim)
                                    ->select('mahasiswa.nama','pengajuan_surat_keterangan_bebas_perpustakaan.*','mahasiswa.nim')
                                    ->join('mahasiswa','pengajuan_surat_keterangan_bebas_perpustakaan.nim','=','mahasiswa.nim')
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
                $pengajuanSurat = PengajuanSuratKeteranganBebasPerpustakaan::whereIn('status',['diajukan','ditolak'])
                                                 ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'operator perpustakaan'){
                $pengajuanSurat = PengajuanSuratKeteranganBebasPerpustakaan::whereIn('status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->join('mahasiswa','pengajuan_surat_keterangan_bebas_perpustakaan.nim','=','mahasiswa.nim')
                                             ->select('mahasiswa.nama','pengajuan_surat_keterangan_bebas_perpustakaan.*','mahasiswa.nim')
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
            $pengajuanSurat = PengajuanSuratKeteranganBebasPerpustakaan::join('surat_keterangan_bebas_perlengkapan','surat_keterangan_bebas_perlengkapan.id_pengajuan','=','pengajuan_surat_keterangan_bebas_perlengkapan.id')
                                                        ->select('surat_keterangan_bebas_perlengkapan.nomor_surat','pengajuan_surat_keterangan_bebas_perlengkapan.*')
                                                        ->with(['mahasiswa','suratKeteranganBebasPerlengkapan.kodeSurat']);
                                                        
            if (Auth::user()->jabatan == 'kasubag umum & bmn') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan_bebas_perpustakaan.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan_bebas_perpustakaan.status','verifikasi kabag');
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

    public function createPengajuan(){
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists()){
                return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
            }
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_bebas_perpustakaan');
        }else{
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_bebas_perpustakaan',compact('mahasiswa'));
        }
    }

    public function storePengajuan(PengajuanSuratKeteranganBebasPerpustakaanRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;
        $operator = Operator::where('bagian','operator perpustakaan')->where('status_aktif','aktif')->first();
        
        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan bebas perpustakaan.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat keterangan bebas perpustakaan dengan nama mahasiswa '.$mahasiswa->nama;
        }

        DB::beginTransaction();
        try{ 
            PengajuanSuratKeteranganBebasPerpustakaan::create($input);
            
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Keterangan Bebas Perpustakaan',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-keterangan-bebas-perpustakaan')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat keterangan bebas perpustakaan gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan bebas perpustakaan berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
    }

    public function edit(PengajuanSuratKeteranganBebasPerpustakaan $pengajuanSurat)
    {   
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_keterangan_bebas_perpustakaan',compact('mahasiswa','pengajuanSurat'));
    }

    public function update(PengajuanSuratKeteranganBebasPerpustakaanRequest $request, PengajuanSuratKeteranganBebasPerpustakaan $pengajuanSurat)
    {
        $input = $request->all();
        $pengajuanSurat->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan bebas perpustakaan berhasil diubah.');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
    }

    public function destroy(PengajuanSuratKeteranganBebasPerpustakaan $pengajuanSurat)
    {
        $pengajuanSurat->delete();;
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan bebas perpustakaan berhasil dihapus');
        return redirect($this->segmentUser.'/surat-keterangan-bebas-perpustakaan');
    }

    public function show(PengajuanSuratKeteranganBebasPerpustakaan $pengajuanSurat){
        $pengajuan = collect($pengajuanSurat->load(['mahasiswa.prodi.jurusan','operator']));
        $pengajuan->put('status', ucwords($pengajuanSurat->status));
        $pengajuan->put('dibuat', $pengajuanSurat->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        
        return $pengajuan->toJson();
    }

    private function isSuratDiajukanExists(){
        $suratPerlengkapan = PengajuanSuratKeteranganBebasPerpustakaan::where('status','diajukan')->exists();
        if($suratPerlengkapan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat keterangan bebas perpustakaan sementara diproses!');
            return false;
        }
        return true;
    }
}
