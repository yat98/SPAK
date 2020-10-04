<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use Carbon\Carbon;
use App\TahunAkademik;
use App\NotifikasiUser;
use App\StatusMahasiswa;
use App\SuratKeterangan;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PengajuanSuratKeteranganRequest;

class PengajuanSuratKeteranganController extends Controller
{
    public function indexKeteranganAktifMahasiswa(){
        $perPage = $this->perPage;
        $countAllPengajuan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                ->where('nim',Auth::user()->nim)
                                ->count();
        return view($this->segmentUser.'.surat_keterangan_aktif_kuliah',compact('countAllPengajuan','perPage'));
    }

    public function indexKelakuanBaikMahasiswa(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                ->where('nim',Auth::user()->nim)
                                ->count();
        
        return view($this->segmentUser.'.surat_keterangan_kelakuan_baik',compact('countAllPengajuan','perPage'));
    }

    public function createPengajuanKeteranganAktif(){
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists('surat keterangan aktif kuliah')){
                return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
            }

            $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
            $tahunAkademik[$tahunAkademikAktif->id] = $tahunAkademikAktif->tahun_akademik.' - '.ucwords($tahunAkademikAktif->semester);

            if($tahunAkademikAktif !=  null){
                $status = StatusMahasiswa::where('status','aktif')->where('id_tahun_akademik',$tahunAkademikAktif->id)->where('nim',Auth::user()->nim)->first();
                if($status == null){
                    $this->setFlashData('info','Pengajuan Gagal','Maaf anda tidak dapat membuat pengajuan surat keterangan aktif kuliah karena status anda tidak aktif');
                    return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
                }
            }else{
                $this->setFlashData('info','Pengajuan Gagal','Tahun akademik belum aktif');
                return redirect('mahasiswa/surat-keterangan-aktif-kuliah');   
            }

            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_aktif_kuliah',compact('tahunAkademik'));
        }else{
            $tahunAkademik = $this->generateAllTahunAkademik();
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_aktif_kuliah',compact('tahunAkademik','mahasiswa'));
        }
    }

    public function createPengajuanKelakuanBaik(){
        if(isset(Auth::user()->nim)){
            if(!$this->isSuratDiajukanExists('surat keterangan kelakuan baik')){
                return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
            }
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_kelakuan_baik');
        }else{
            $tahunAkademik = $this->generateAllTahunAkademik();
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_kelakuan_baik',compact('mahasiswa'));
        }
    }
    
    public function storePengajuanKelakuanBaik(Request $request){
        $this->validate($request,[
            'nim'=>'required|numeric'
        ]);

        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;
        $input['jenis_surat'] = 'surat keterangan kelakuan baik';

        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();

        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan aktif kuliah.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat keterangan aktif kuliah dengan nama mahasiswa '.$mahasiswa->nama;
        }

        DB::beginTransaction();
        try{
            PengajuanSuratKeterangan::create($input);
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan kelakuan baik.',
                'link_notifikasi'=>url('operator/surat-keterangan-kelakuan-baik')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat keterangan kelakuan baik gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan kelakuan baik berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
    }

    public function storePengajuanKeteranganAktif(PengajuanSuratKeteranganRequest $request){
        $input = $request->all();
        $input['id_operator'] = Auth::user()->id;
        $input['jenis_surat'] = 'surat keterangan aktif kuliah';
        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();

        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan aktif kuliah.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat keterangan aktif kuliah dengan nama mahasiswa '.$mahasiswa->nama;
        }

        DB::beginTransaction();
        try{ 
            PengajuanSuratKeterangan::create($input);
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Keterangan Aktif Kuliah',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-keterangan-aktif-kuliah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan surat keterangan aktif kuliah gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan aktif kuliah berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function getAllPengajuanAktif(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                    ->where('pengajuan_surat_keterangan.nim',Auth::user()->nim)
                                    ->select('mahasiswa.nama','tahun_akademik.tahun_akademik','tahun_akademik.semester','pengajuan_surat_keterangan.*','mahasiswa.nim')
                                    ->join('mahasiswa','pengajuan_surat_keterangan.nim','=','mahasiswa.nim')
                                    ->join('tahun_akademik','pengajuan_surat_keterangan.id_tahun_akademik','=','tahun_akademik.id')
                                    ->with(['mahasiswa','tahunAkademik']))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn('tahun', function ($data) {
                            return $data->tahunAkademik->tahun_akademik.' - '.ucwords($data->tahunAkademik->semester);
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
            $pengajuanSurat = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah');

            if(Auth::user()->bagian == 'front office'){
                $pengajuanSurat = $pengajuanSurat->whereIn('status',['diajukan','ditolak'])
                                                 ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian kemahasiswaan'){
                $pengajuanSurat = $pengajuanSurat->whereIn('status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->select('mahasiswa.nama','tahun_akademik.tahun_akademik','tahun_akademik.semester','pengajuan_surat_keterangan.*')
                                             ->join('mahasiswa','pengajuan_surat_keterangan.nim','=','mahasiswa.nim')
                                             ->join('tahun_akademik','pengajuan_surat_keterangan.id_tahun_akademik','=','tahun_akademik.id')
                                             ->with(['mahasiswa','tahunAkademik']);

            return DataTables::of($pengajuanSurat)
                        ->addColumn('aksi', function ($data) {
                            return $data->id_pengajuan;
                        })
                        ->addColumn('tahun', function ($data) {
                            return $data->tahunAkademik->tahun_akademik.' - '.ucwords($data->tahunAkademik->semester);
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
            $pengajuanSurat = PengajuanSuratKeterangan::join('surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                                        ->join('tahun_akademik','pengajuan_surat_keterangan.id_tahun_akademik','=','tahun_akademik.id')
                                                        ->where('pengajuan_surat_keterangan.jenis_surat','surat keterangan aktif kuliah')
                                                        ->select('surat_keterangan.nomor_surat','pengajuan_surat_keterangan.*','tahun_akademik.semester')
                                                        ->with(['mahasiswa','suratKeterangan.kodeSurat','tahunAkademik']);
                                                        
            if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan.status','verifikasi kabag');
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
                            ->editColumn("semester", function ($data) {
                                return ucwords($data->semester);
                            })
                            ->make(true);
        }
    }

    public function getAllPengajuanKelakuanBaik(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                    ->where('pengajuan_surat_keterangan.nim',Auth::user()->nim)
                                    ->select('mahasiswa.nama','pengajuan_surat_keterangan.*','mahasiswa.nim')
                                    ->join('mahasiswa','pengajuan_surat_keterangan.nim','=','mahasiswa.nim')
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
            $pengajuanSurat = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik');

            if(Auth::user()->bagian == 'front office'){
                $pengajuanSurat = $pengajuanSurat->whereIn('status',['diajukan','ditolak'])
                                                 ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian kemahasiswaan'){
                $pengajuanSurat = $pengajuanSurat->whereIn('status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->select('mahasiswa.nama','pengajuan_surat_keterangan.*')
                                             ->join('mahasiswa','pengajuan_surat_keterangan.nim','=','mahasiswa.nim')
                                             ->with(['mahasiswa','tahunAkademik']);

            return DataTables::of($pengajuanSurat)
                        ->addColumn('aksi', function ($data) {
                            return $data->id_pengajuan;
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
            $pengajuanSurat = PengajuanSuratKeterangan::join('surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                                        ->where('pengajuan_surat_keterangan.jenis_surat','surat keterangan kelakuan baik')
                                                        ->select('surat_keterangan.nomor_surat','pengajuan_surat_keterangan.*')
                                                        ->with(['mahasiswa','suratKeterangan.kodeSurat']);
            
            if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_keterangan.status','verifikasi kabag');
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
                            ->editColumn("nama", function ($data) {
                                return ucwords($data->nama);
                            })
                            ->make(true);
        }
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $pengajuan = PengajuanSuratKeterangan::findOrFail($request->id);
        $user = $pengajuan->suratKeterangan->user;
        $jenisSurat = $pengajuan->jenis_surat;
        $isiNotifikasi = 'Verifikasi '.$jenisSurat.' mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;

        if(Auth::user()->jabatan == 'kabag tata usaha' || $pengajuan->suratKeterangan->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan '.$jenisSurat.' mahasiswa dengan nama '.$pengajuan->mahasiswa->nama;
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
                'judul_notifikasi'=>ucwords($jenisSurat),
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/'.str_replace(' ', '_', $jenisSurat))
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan '.ucfirst($jenisSurat).' gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil ',ucfirst($jenisSurat).' berhasil diverifikasi');
        return redirect($this->segmentUser.'/'.str_replace(' ', '-', $jenisSurat));
    }

    public function show(PengajuanSuratKeterangan $pengajuanSurat){
        $data = collect($pengajuanSurat->load(['mahasiswa','tahunAkademik','operator']));
        $data->put('created_at',$pengajuanSurat->created_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('updated_at',$pengajuanSurat->updated_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('status',ucwords($pengajuanSurat->status));
        $data->put('jenis_surat',ucwords($pengajuanSurat->jenis_surat));
        
        return $data->toJson();
    }

    public function edit(PengajuanSuratKeterangan $pengajuanSurat)
    {   
        $tahunAkademik = $this->generateAllTahunAkademik();
        $mahasiswa = $this->generateMahasiswa();
        if($pengajuanSurat->jenis_surat == 'surat keterangan aktif kuliah'){
            return view($this->segmentUser.'.edit_pengajuan_surat_keterangan_aktif_kuliah',compact('tahunAkademik','mahasiswa','pengajuanSurat'));
        }else{
            return view($this->segmentUser.'.edit_pengajuan_surat_keterangan_kelakuan_baik',compact('tahunAkademik','mahasiswa','pengajuanSurat'));
        }
    }

    public function update(Request $request, PengajuanSuratKeterangan $pengajuanSurat){
        if($pengajuanSurat->jenis_surat == 'surat keterangan aktif kuliah'){
            $this->validate($request,[
                'nim'=>'required|numeric',
                'id_tahun_akademik'=>'required|numeric',
            ]);
        }else{
            $this->validate($request,[
                'nim'=>'required|numeric',
            ]);
        }
        $input = $request->all();
        $pengajuanSurat->update($input);
        if($pengajuanSurat->jenis_surat == 'surat keterangan aktif kuliah'){
            $this->setFlashData('success','Berhasil','Pengajuan surat keterangan aktif kuliah berhasil diubah');
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan kelakuan baik berhasil diubah');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
    }

    public function destroy(PengajuanSuratKeterangan $pengajuanSurat)
    {
        $pengajuanSurat->delete();
        if($pengajuanSurat->jenis_surat == 'surat keterangan aktif kuliah'){
            $this->setFlashData('success','Berhasil','Pengajuan surat keterangan aktif kuliah berhasil dihapus');
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan kelakuan baik berhasil dihapus');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
    }
    
    private function isSuratDiajukanExists($jenisSurat){
        $suratKeterangan = PengajuanSuratKeterangan::where('jenis_surat',$jenisSurat)
                                                   ->where('nim',Auth::user()->nim)
                                                   ->where('status','diajukan')
                                                   ->exists();
        if($suratKeterangan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan '.$jenisSurat.' sementara diproses!');
            return false;
        }
        return true;
    }
}
