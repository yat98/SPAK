<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use App\WaktuCuti;
use Carbon\Carbon;
use App\TahunAkademik;
use App\NotifikasiUser;
use App\PendaftaranCuti;
use App\NotifikasiOperator;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PendaftaranCutiRequest;

class PendaftaranCutiController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;

        $countAllVerifikasi = PendaftaranCuti::whereIn('status',['diajukan','ditolak'])
                                               ->count();

        $countAllPendaftaran = PendaftaranCuti::where('status','selesai')
                                                ->count();;

        return view('user.'.$this->segmentUser.'.pendaftaran_cuti',compact('countAllVerifikasi','perPage','countAllPendaftaran'));
    }

    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $countAllPendaftaran = PendaftaranCuti::where('nim',Auth::user()->nim)
                                                ->count();
        return view($this->segmentUser.'.pendaftaran_cuti',compact('perPage','countAllPendaftaran'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PendaftaranCuti::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllPendaftaran = PendaftaranCuti::where('status','selesai')
                                                ->count();
                                                                         
        return view($this->segmentUser.'.pendaftaran_cuti',compact('countAllPengajuan','perPage','countAllPendaftaran'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;
                                            
        $countAllPendaftaran = PendaftaranCuti::where('status','selesai')
                                          ->count();
        
        return view('user.'.$this->segmentUser.'.pendaftaran_cuti',compact('perPage','countAllPendaftaran'));
    }

    public function getAllPengajuanPendaftaran(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PendaftaranCuti::where('pendaftaran_cuti.nim',Auth::user()->nim)
                                    ->join('waktu_cuti','pendaftaran_cuti.id_waktu_cuti','=','waktu_cuti.id')
                                    ->join('tahun_akademik','tahun_akademik.id','=','waktu_cuti.id_tahun_akademik')
                                    ->join('mahasiswa','pendaftaran_cuti.nim','=','mahasiswa.nim')
                                    ->select('mahasiswa.nama','pendaftaran_cuti.*')
                                    ->with(['mahasiswa','operator','waktuCuti.tahunAkademik']))
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
                        ->addColumn("tahun_akademik", function ($data) {
                            return $data->waktuCuti->tahunAkademik->toArray();                            
                        })
                        ->addColumn('tahun', function ($data) {
                            return $data->waktuCuti->tahunAkademik->tahun_akademik.' - '.ucwords($data->waktuCuti->tahunAkademik->semester);
                        })
                        ->make(true);
        } else if(isset(Auth::user()->id)){
            $pendaftaranCuti = PendaftaranCuti::join('waktu_cuti','pendaftaran_cuti.id_waktu_cuti','=','waktu_cuti.id')
                                               ->join('tahun_akademik','tahun_akademik.id','=','waktu_cuti.id_tahun_akademik')
                                               ->join('mahasiswa','pendaftaran_cuti.nim','=','mahasiswa.nim');

            if(Auth::user()->bagian == 'front office'){
                $pendaftaranCuti = $pendaftaranCuti->whereIn('status',['diajukan','ditolak'])
                                                 ->where('id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian kemahasiswaan'){
                $pendaftaranCuti = $pendaftaranCuti->whereIn('status',['diajukan','ditolak']);
            }

            $pendaftaranCuti = $pendaftaranCuti->select('mahasiswa.nama','pendaftaran_cuti.*')
                                             ->with(['mahasiswa','operator','waktuCuti.tahunAkademik']);

            return DataTables::of($pendaftaranCuti)
                        ->addColumn('aksi', function ($data) {
                            return $data->id_pengajuan;
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->addColumn('tahun', function ($data) {
                            return $data->waktuCuti->tahunAkademik->tahun_akademik.' - '.ucwords($data->waktuCuti->tahunAkademik->semester);
                        })
                        ->addColumn("tahun_akademik", function ($data) {
                            return $data->waktuCuti->tahunAkademik->toArray();                            
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
        } else if(isset(Auth::user()->nip)){
            $pendaftaranCuti = PendaftaranCuti::join('waktu_cuti','pendaftaran_cuti.id_waktu_cuti','=','waktu_cuti.id')
                                                        ->join('tahun_akademik','tahun_akademik.id','=','waktu_cuti.id_tahun_akademik')
                                                        ->join('mahasiswa','pendaftaran_cuti.nim','=','mahasiswa.nim')
                                                        ->select('mahasiswa.nama','pendaftaran_cuti.*')
                                                        ->with(['mahasiswa','operator','waktuCuti.tahunAkademik']);
                                                        
            if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
                $pendaftaranCuti = $pendaftaranCuti->whereIn('status',['diajukan','ditolak']);
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pendaftaranCuti = $pendaftaranCuti->where('status','selesai');
            }

            return DataTables::of($pendaftaranCuti)
                            ->addColumn('aksi', function ($data) {
                                return $data->id;
                            })
                            ->addColumn("waktu_pengajuan", function ($data) {
                                return $data->created_at->diffForHumans();                            
                            })
                            ->addColumn('tahun', function ($data) {
                                return $data->waktuCuti->tahunAkademik->tahun_akademik.' - '.ucwords($data->waktuCuti->tahunAkademik->semester);
                            })
                            ->addColumn("tahun_akademik", function ($data) {
                                return $data->waktuCuti->tahunAkademik->toArray();                            
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

    public function getAllPendaftaran(){
        $pendaftaranCuti = PendaftaranCuti::join('waktu_cuti','pendaftaran_cuti.id_waktu_cuti','=','waktu_cuti.id')
                                    ->join('tahun_akademik','tahun_akademik.id','=','waktu_cuti.id_tahun_akademik')
                                    ->join('mahasiswa','pendaftaran_cuti.nim','=','mahasiswa.nim')
                                    ->select('mahasiswa.nama','pendaftaran_cuti.*')
                                    ->with(['mahasiswa','operator','waktuCuti.tahunAkademik']);
        
        $pendaftaranCuti = $pendaftaranCuti->where('status','selesai');

        return DataTables::of($pendaftaranCuti)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();
                        })
                        ->addColumn('tahun', function ($data) {
                            return $data->waktuCuti->tahunAkademik->tahun_akademik.' - '.ucwords($data->waktuCuti->tahunAkademik->semester);
                        })
                        ->addColumn("tahun_akademik", function ($data) {
                            return $data->waktuCuti->tahunAkademik->toArray();                            
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
    }

    public function create()
    {
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $waktuCuti = WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id ?? 0)->first();
        $mahasiswa = $this->generateMahasiswa();

        if(!$this->isPendaftaranCutiMahasiswaExists($tahunAkademikAktif,$waktuCuti)){
            return redirect($this->segmentUser.'/pendaftaran-cuti');
        }

        if(isset(Auth::user()->id)) $waktuCuti = $this->generateWaktuCuti();
        
        return view($this->segmentUser.'.tambah_pendaftaran_cuti',compact('waktuCuti','mahasiswa'));
    }  

    public function show(PendaftaranCuti $pendaftaranCuti)
    {
        $pendaftaran = collect($pendaftaranCuti->load(['mahasiswa','waktuCuti.tahunAkademik','operator']));
        $pendaftaran->put('dibuat',$pendaftaranCuti->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $pendaftaran->put('status',ucwords($pendaftaranCuti->status));
        $pendaftaran->put('semester',ucwords($pendaftaranCuti->waktuCuti->tahunAkademik->semester));
        
        $pendaftaran->put('file_surat_permohonan_cuti',asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_surat_permohonan_cuti));
        $pendaftaran->put('file_krs_sebelumnya',asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_krs_sebelumnya));
        $pendaftaran->put('file_slip_ukt',asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_slip_ukt));
        $pendaftaran->put('nama_file_surat_permohonan_cuti',explode('.',$pendaftaranCuti->file_surat_permohonan_cuti)[0]);
        $pendaftaran->put('nama_file_krs_sebelumnya',explode('.',$pendaftaranCuti->file_krs_sebelumnya)[0]);
        $pendaftaran->put('nama_file_slip_ukt',explode('.',$pendaftaranCuti->file_slip_ukt)[0]);
        return $pendaftaran->toJson();
    }

    public function edit(PendaftaranCuti $pendaftaranCuti){
        $waktuCuti[$pendaftaranCuti->id_waktu_cuti] = $pendaftaranCuti->waktuCuti->tahunAkademik->tahun_akademik.' - '.ucwords($pendaftaranCuti->waktuCuti->tahunAkademik->semester);
        if(isset(Auth::user()->id)){
            $mahasiswa = $this->generateMahasiswa();
            return view($this->segmentUser.'.edit_pendaftaran_cuti',compact('mahasiswa','pendaftaranCuti','waktuCuti'));
        }
        return view($this->segmentUser.'.edit_pendaftaran_cuti',compact('pendaftaranCuti','waktuCuti'));
    }

    public function update(PendaftaranCutiRequest $request,PendaftaranCuti $pendaftaranCuti){
        $input = $request->all();
        if($request->has('file_surat_permohonan_cuti')){
            $imageFieldName = 'file_surat_permohonan_cuti'; 
            $uploadPath = 'upload_permohonan_cuti';
            $this->deleteImage($pendaftaranCuti->file_surat_permohonan_cuti);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_krs_sebelumnya')){
            $imageFieldName = 'file_krs_sebelumnya'; 
            $uploadPath = 'upload_permohonan_cuti';
            $this->deleteImage($pendaftaranCuti->file_krs_sebelumnya);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_slip_ukt')){
            $imageFieldName = 'file_slip_ukt'; 
            $uploadPath = 'upload_permohonan_cuti';
            $this->deleteImage($pendaftaranCuti->file_slip_ukt);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $pendaftaranCuti->update($input);
        $this->setFlashData('success','Berhasil','Pendaftaran cuti berhasil diubah');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    public function store(PendaftaranCutiRequest $request){
        $input = $request->all();
        if($request->has('file_surat_permohonan_cuti')){
            $imageFieldName = 'file_surat_permohonan_cuti'; 
            $uploadPath = 'upload_permohonan_cuti';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_krs_sebelumnya')){
            $imageFieldName = 'file_krs_sebelumnya'; 
            $uploadPath = 'upload_permohonan_cuti';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_slip_ukt')){
            $imageFieldName = 'file_slip_ukt'; 
            $uploadPath = 'upload_permohonan_cuti';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        DB::beginTransaction();
        try{
            $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();
            $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first();

            if(isset(Auth::user()->nim)){
                $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
                $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan pendaftaran cuti.';
            } else if(isset(Auth::user()->id)){
                $input['id_operator'] = Auth::user()->id;
                $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
                $isiNotifikasi = 'Front office membuat pengajuan pendaftaran cuti dengan nama mahasiswa '.$mahasiswa->nama;
            }

            $pendaftaran = PendaftaranCuti::create($input);
            
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Pendaftaran Cuti',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-persetujuan-pindah')
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Pendaftaran Cuti',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pegawai/surat-persetujuan-pindah')
            ]);

        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Pendaftaran cuti gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pendaftaran cuti berhasil ditambahkan');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    public function destroy(PendaftaranCuti $pendaftaranCuti)
    {
        $this->deleteImage($pendaftaranCuti->file_surat_permohonan_cuti);
        $this->deleteImage($pendaftaranCuti->file_krs_sebelumnya);
        $this->deleteImage($pendaftaranCuti->file_slip_ukt);
        $pendaftaranCuti->delete();
        $this->setFlashData('success','Berhasil','Pendaftaran cuti berhasil dihapus');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    public function verification(PendaftaranCuti $pendaftaranCuti){
        $pendaftaranCuti->update([
            'status'=>'selesai'
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pendaftaranCuti->nim,
            'judul_notifikasi'=>'Pendaftaran Cuti',
            'isi_notifikasi'=>'Pendaftaran cuti telah diverifikasi.',
            'link_notifikasi'=>url('mahasiswa/pendaftaran-cuti')
        ]);
        $this->setFlashData('success','Berhasil','Pendaftaran cuti dengan nama mahasiswa telah diverifikasi');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    public function tolakPendaftaran(Request $request,PendaftaranCuti $pendaftaranCuti){
        $keterangan = $request->keterangan ?? '-';
        $pendaftaranCuti->update([
            'status'=>'ditolak',
            'keterangan'=>$keterangan
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pendaftaranCuti->nim,
            'judul_notifikasi'=>'Pendaftaran Cuti',
            'isi_notifikasi'=>'Pendaftaran cuti telah ditolak.',
            'link_notifikasi'=>url('mahasiswa/pendaftaran-cuti')
        ]);
        $this->setFlashData('success','Berhasil','Pendaftaran cuti dengan nama mahasiswa ditolak');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    private function uploadImage($imageFieldName, $request, $uploadPath){
        $image = $request->file($imageFieldName);
        $ext = $image->getClientOriginalExtension();
        if($image->isValid()){
            $imageName = $request->nim.'_'.date('YmdHis').'_'.$imageFieldName.".$ext";
            $image->move($uploadPath,$imageName);            
            return $imageName;
        }
        return false;
    }
    
    private function deleteImage($imageName){
        $exist = Storage::disk('file_permohonan_cuti')->exists($imageName);
        if(isset($imageName) && $exist){
            $delete = Storage::disk('file_permohonan_cuti')->delete($imageName);
            if($delete) return true;
            return false;
        }
    }

    private function isPendaftaranCutiMahasiswaExists($tahunAkademikAktif,$waktuCuti){
        if(!isset($waktuCuti) || !isset($tahunAkademikAktif)){
            if (!isset($waktuCuti)) $this->setFlashData('info','Pendaftaran Cuti','Waktu pendaftaran cuti belum dibuka');
            if (!isset($tahunAkademikAktif)) $this->setFlashData('info','Pendaftaran Cuti','Tahun akademik belum aktif');
            return false;
        }else{
            $tgl = Carbon::now();
            $countPendaftaranCutiSelesai =  PendaftaranCuti::where('id_waktu_cuti',$waktuCuti->id)
                                                             ->where('nim',Auth::user()->nim)
                                                             ->where('status','selesai')->count();

            $countPendaftaranCutiDiajukan =  PendaftaranCuti::where('id_waktu_cuti',$waktuCuti->id)
                                                              ->where('nim',Auth::user()->nim)
                                                              ->where('status','diajukan')->count();

            if($countPendaftaranCutiSelesai >= 1){
                $this->setFlashData('info','Pendaftaran Cuti','Pendaftaran cuti anda telah selesai');
                return false;
            }

            if($countPendaftaranCutiDiajukan >= 1){
                $this->setFlashData('info','Pendaftaran Cuti','Pendaftaran cuti sementara diproses!');
                return false;
            }
            
            if($tgl->lessThanOrEqualTo($waktuCuti->tanggal_awal_cuti)){
                $this->setFlashData('info','Pendaftaran Cuti','Pendaftaran cuti semester ini belum dibuka');
                return false;
            }  
            
            if($tgl->greaterThanOrEqualTo($waktuCuti->tanggal_akhir_cuti)){
                $this->setFlashData('info','Pendaftaran Cuti','Pendaftaran cuti semester ini telah selesai');
                return false;
            }
        }
        return true;
    }

    private function generateWaktuCuti(){
        $waktuCuti = WaktuCuti::all();
        $waktuCutiList = [];
        foreach($waktuCuti as $value){
            $waktuCutiList[$value->id] = $value->tahunAkademik->tahun_akademik.' - '.ucwords($value->tahunAkademik->semester);
        }
        return $waktuCutiList;
    }
}
