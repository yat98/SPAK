<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use App\Ormawa;
use DataTables;
use App\Operator;
use App\KodeSurat;
use App\Mahasiswa;
use Carbon\Carbon;
use App\NotifikasiUser;
use App\NotifikasiOperator;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\SuratKegiatanMahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\DisposisiSuratKegiatanMahasiswa;
use App\PengajuanSuratKegiatanMahasiswa;
use App\DaftarDisposisiSuratKegiatanMahasiswa;
use App\Http\Requests\PengajuanSuratKegiatanMahasiswaRequest;

class PengajuanSuratKegiatanMahasiswaController extends Controller
{
    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();

        $countAllPengajuan = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                ->join('pimpinan_ormawa','pimpinan_ormawa.id_ormawa','=','ormawa.id')
                                ->where('pimpinan_ormawa.nim',Auth::user()->nim)
                                ->count();

        return view($this->segmentUser.'.surat_kegiatan_mahasiswa',compact('countAllPengajuan','perPage'));
    }

    public function createPengajuan(){
        $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
        if(Auth::user()->nim){ 
            $ormawa[$mahasiswa->pimpinanOrmawa->ormawa->id] = ucwords($mahasiswa->pimpinanOrmawa->ormawa->nama);
        }else{
            $ormawa = Ormawa::pluck('nama','id');
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_kegiatan_mahasiswa',compact('ormawa'));
    }

    public function getAllPengajuan(){
        if(isset(Auth::user()->nim)){
            return DataTables::of(PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                    ->join('pimpinan_ormawa','pimpinan_ormawa.id_ormawa','=','ormawa.id')
                                    ->where('pimpinan_ormawa.nim',Auth::user()->nim)
                                    ->select(['pengajuan_surat_kegiatan_mahasiswa.*','pimpinan_ormawa.nim','ormawa.nama']) 
                                    ->with(['mahasiswa','ormawa','operator']))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            switch($data->status){
                                case 'disposisi wd1':
                                    return 'Disposisi WD1';
                                    break;
                                case 'disposisi wd2':
                                    return 'Disposisi WD2';
                                    break;
                                case 'disposisi wd3':
                                    return 'Disposisi WD3';
                                    break;
                                default:
                                    return ucwords($data->status);
                                    break;
                            }
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
        } else if(isset(Auth::user()->id)){
            $pengajuanSurat;
            
            if(Auth::user()->bagian == 'front office'){
                $pengajuanSurat = PengajuanSuratKegiatanMahasiswa::whereIn('pengajuan_surat_kegiatan_mahasiswa.status',['diajukan','ditolak','disposisi dekan','disposisi wd1','disposisi wd2','disposisi wd3','disposisi kabag','disposisi kasubag','disposisi selesai'])
                                                 ->where('pengajuan_surat_kegiatan_mahasiswa.id_operator',Auth::user()->id);
            }elseif(Auth::user()->bagian == 'subbagian kemahasiswaan'){
                $pengajuanSurat = PengajuanSuratKegiatanMahasiswa::whereIn('pengajuan_surat_kegiatan_mahasiswa.status',['diajukan','ditolak']);
            }

            $pengajuanSurat = $pengajuanSurat->join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                             ->select(['pengajuan_surat_kegiatan_mahasiswa.*','ormawa.nama']) 
                                             ->with(['mahasiswa','ormawa','operator']);

            return DataTables::of($pengajuanSurat)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            switch($data->status){
                                case 'disposisi wd1':
                                    return 'Disposisi WD1';
                                    break;
                                case 'disposisi wd2':
                                    return 'Disposisi WD2';
                                    break;
                                case 'disposisi wd3':
                                    return 'Disposisi WD3';
                                    break;
                                default:
                                    return ucwords($data->status);
                                    break;
                            }
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
        } else if(isset(Auth::user()->nip)){
            $pengajuanSurat = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                ->join('surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                ->select(['pengajuan_surat_kegiatan_mahasiswa.*','ormawa.nama']) 
                                ->with(['mahasiswa','ormawa','operator','suratKegiatanMahasiswa.kodeSurat']);
            
            if (Auth::user()->jabatan == 'kasubag kemahasiswaan') {
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_kegiatan_mahasiswa.status','verifikasi kasubag');
            }else if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = $pengajuanSurat->where('pengajuan_surat_kegiatan_mahasiswa.status','verifikasi kabag');
            }

            return DataTables::of($pengajuanSurat)
                            ->addColumn('aksi', function ($data) {
                                return $data->id;
                            })
                            ->addColumn("waktu_pengajuan", function ($data) {
                                return $data->created_at->diffForHumans();                            
                            })
                            ->editColumn("status", function ($data) {
                                switch($data->status){
                                    case 'disposisi wd1':
                                        return 'Disposisi WD1';
                                        break;
                                    case 'disposisi wd2':
                                        return 'Disposisi WD2';
                                        break;
                                    case 'disposisi wd3':
                                        return 'Disposisi WD3';
                                        break;
                                    default:
                                        return ucwords($data->status);
                                        break;
                                }
                            })
                            ->make(true);
        }
    }

    public function getAllPengajuanByNim(Mahasiswa $mahasiswa){
        return DataTables::of(PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                    ->join('pimpinan_ormawa','pimpinan_ormawa.id_ormawa','=','ormawa.id')
                                    ->where('pimpinan_ormawa.nim',$mahasiswa->nim)
                                    ->select(['pengajuan_surat_kegiatan_mahasiswa.*','pimpinan_ormawa.nim','ormawa.nama']) 
                                    ->with(['mahasiswa','ormawa','operator']))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->editColumn("status", function ($data) {
                            switch($data->status){
                                case 'disposisi wd1':
                                    return 'Disposisi WD1';
                                    break;
                                case 'disposisi wd2':
                                    return 'Disposisi WD2';
                                    break;
                                case 'disposisi wd3':
                                    return 'Disposisi WD3';
                                    break;
                                default:
                                    return ucwords($data->status);
                                    break;
                            }
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
    }

    public function getAllDisposisiPegawai(){
        $pengajuanSurat = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                            ->whereIn('pengajuan_surat_kegiatan_mahasiswa.status',['disposisi kasubag','disposisi selesai'])
                            ->select(['pengajuan_surat_kegiatan_mahasiswa.*','ormawa.nama']) 
                            ->with(['mahasiswa','ormawa','operator']);

return DataTables::of($pengajuanSurat)
        ->addColumn('aksi', function ($data) {
            return $data->id;
        })
        ->addColumn("waktu_pengajuan", function ($data) {
            return $data->created_at->diffForHumans();                            
        })
        ->editColumn("status", function ($data) {
            switch($data->status){
                case 'disposisi wd1':
                    return 'Disposisi WD1';
                    break;
                case 'disposisi wd2':
                    return 'Disposisi WD2';
                    break;
                case 'disposisi wd3':
                    return 'Disposisi WD3';
                    break;
                default:
                    return ucwords($data->status);
                    break;
            }
        })
        ->make(true);
    }

    public function getAllDisposisiPimpinan(){        
        if(isset(Auth::user()->id)){
            $pengajuanSurat = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                    ->whereIn('pengajuan_surat_kegiatan_mahasiswa.status',['disposisi dekan','disposisi wd1','disposisi wd2','disposisi wd3','disposisi kabag','disposisi kasubag','disposisi selesai'])
                                    ->select(['pengajuan_surat_kegiatan_mahasiswa.*','ormawa.nama']) 
                                    ->with(['mahasiswa','ormawa','operator']);
        }elseif(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kabag tata usaha'){
                $pengajuanSurat = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                    ->whereIn('pengajuan_surat_kegiatan_mahasiswa.status',['disposisi dekan','disposisi wd1','disposisi wd2','disposisi wd3','disposisi kabag','disposisi kasubag','disposisi selesai'])
                                    ->select(['pengajuan_surat_kegiatan_mahasiswa.*','ormawa.nama']) 
                                    ->with(['mahasiswa','ormawa','operator']);
            }else{
                $pengajuanSurat = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                        ->whereIn('pengajuan_surat_kegiatan_mahasiswa.status',['disposisi dekan','disposisi wd1','disposisi wd2','disposisi wd3','disposisi kabag','disposisi kasubag','disposisi selesai','verifikasi kasubag','verifikasi kabag'])
                                        ->select(['pengajuan_surat_kegiatan_mahasiswa.*','ormawa.nama']) 
                                        ->with(['mahasiswa','ormawa','operator']);
            }
        }

        return DataTables::of($pengajuanSurat)
                                ->addColumn('aksi', function ($data) {
                                    return $data->id;
                                })
                                ->addColumn("waktu_pengajuan", function ($data) {
                                    return $data->created_at->diffForHumans();                            
                                })
                                ->editColumn("status", function ($data) {
                                    switch($data->status){
                                        case 'disposisi wd1':
                                            return 'Disposisi WD1';
                                            break;
                                        case 'disposisi wd2':
                                            return 'Disposisi WD2';
                                            break;
                                        case 'disposisi wd3':
                                            return 'Disposisi WD3';
                                            break;
                                        default:
                                            return ucwords($data->status);
                                            break;
                                    }
                                })
                                ->make(true);
    }

    public function getAllTandaTangan(){
        $suratKegiatan =  PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                                  ->join('surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                                  ->where('pengajuan_surat_kegiatan_mahasiswa.status','menunggu tanda tangan')
                                                  ->where('surat_kegiatan_mahasiswa.nip',Auth::user()->nip)
                                                  ->select(['pengajuan_surat_kegiatan_mahasiswa.*','ormawa.nama']) 
                                                  ->with(['mahasiswa','ormawa','operator','suratKegiatanMahasiswa.kodeSurat']);
                                                  
        return DataTables::of($suratKegiatan)
                    ->addColumn('aksi', function ($data) {
                        return $data->id;
                    })
                    ->editColumn("status", function ($data) {
                        return ucwords($data->status);
                    })
                    ->addColumn("waktu_pengajuan", function ($data) {
                        return $data->created_at->diffForHumans();                            
                    })
                    ->make(true);                        
    }

    public function verification(Request $request){
        $status='verifikasi kabag';
        $suratKegiatan = SuratKegiatanMahasiswa::findOrFail($request->id);
        $user = $suratKegiatan->user;

        $isiNotifikasi = 'Verifikasi surat kegiatan mahasiswa';

        if(Auth::user()->jabatan == 'kabag tata usaha' || $suratKegiatan->user->jabatan == 'kabag tata usaha'){
            $status='menunggu tanda tangan';
            $isiNotifikasi = 'Tanda tangan surat kegiatan mahasiswa';
        }

        DB::beginTransaction();
        try{
            $suratKegiatan->pengajuanSuratKegiatanMahasiswa->update([
                'status'=>$status,
            ]);
            
            if($status == 'verifikasi kabag'){
                $user = User::where('jabatan','kabag tata usaha')->where('status_aktif','aktif')->first();
            }

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('pimpinan/surat-kegiatan-mahasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Surat kegiatan mahasiswa gagal diverifikasi.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil ','Surat kegiatan mahasiswa berhasil diverifikasi');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function show(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        if(isset(Auth::user()->nip)) return view('user.'.$this->segmentUser.'.detail_pengajuan_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan'));
        return view($this->segmentUser.'.detail_pengajuan_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan'));
    }

    public function edit(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        if(Auth::user()->nim){ 
            $ormawa[$mahasiswa->pimpinanOrmawa->ormawa->id] = ucwords($mahasiswa->pimpinanOrmawa->ormawa->nama);
        }else{
            $ormawa = Ormawa::pluck('nama','id');
        }
        return view($this->segmentUser.'.edit_pengajuan_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan','ormawa'));
    }

    public function update(PengajuanSuratKegiatanMahasiswaRequest $request, PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $input = $request->all();
        if ($request->has('file_surat_permohonan_kegiatan')) {
            $imageFieldName = 'file_surat_permohonan_kegiatan';
            $uploadPath = 'upload_surat_permohonan_kegiatan';
            $this->delete($imageFieldName,$pengajuanKegiatan->file_surat_permohonan_kegiatan);
            $input[$imageFieldName] = $this->upload($imageFieldName, $request, $uploadPath);
        }
        if ($request->has('file_proposal_kegiatan')) {
            $imageFieldName = 'file_proposal_kegiatan';
            $uploadPath = 'upload_proposal_kegiatan';
            $this->delete($imageFieldName,$pengajuanKegiatan->file_proposal_kegiatan);
            $input[$imageFieldName] = $this->upload($imageFieldName, $request, $uploadPath);
        }
        $pengajuanKegiatan->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa berhasil diubah');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function storePengajuan(PengajuanSuratKegiatanMahasiswaRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();

        if(isset(Auth::user()->nim)){
            $input['nim'] = Auth::user()->nim;
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat kegiatan mahasiswa.';
        } else if(isset(Auth::user()->id)){
            $input['id_operator'] = Auth::user()->id;
            $isiNotifikasi = 'Front office membuat pengajuan surat kegiatan mahasiswa dengan nama kegiatan '.$request->nama_kegiatan;
        }

        DB::beginTransaction();
        try {
            if ($request->has('file_surat_permohonan_kegiatan')) {
                $imageFieldName = 'file_surat_permohonan_kegiatan';
                $uploadPath = 'upload_surat_permohonan_kegiatan';
                $input[$imageFieldName] = $this->upload($imageFieldName, $request, $uploadPath);
            }
            if ($request->has('file_proposal_kegiatan')) {
                $imageFieldName = 'file_proposal_kegiatan';
                $uploadPath = 'upload_proposal_kegiatan';
                $input[$imageFieldName] = $this->upload($imageFieldName, $request, $uploadPath);
            }
            PengajuanSuratKegiatanMahasiswa::create($input);
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-kegiatan-mahasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Pengajuan Gagal','Pengajuan kegiatan mahasiswa gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function destroy(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $this->delete('file_surat_permohonan_kegiatan',$pengajuanKegiatan->file_surat_permohonan_kegiatan);
        $this->delete('file_proposal_kegiatan',$pengajuanKegiatan->file_proposal_kegiatan);
        $pengajuanKegiatan->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa berhasil dihapus');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function createDisposisi(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $userDisposisi = $this->generateUserDisposisi();
        return view('user.'.$this->segmentUser.'.tambah_disposisi_surat_kegiatan_mahasiswa',compact('userDisposisi','pengajuanKegiatan'));
    }

    public function storeDisposisi(Request $request){
        if(Auth::user()->jabatan != 'kasubag kemahasiswaan'){
            $this->validate($request,[
                'id'=>'required|numeric',
                'nip_disposisi'=>'required',
                'catatan'=>'required|string'
            ]);
        }else{
            $this->validate($request,[
                'id'=>'required|numeric',
                'catatan'=>'required|string'
            ]);
        }

        $input = $request->all();
        $pengajuanKegiatan = PengajuanSuratKegiatanMahasiswa::findOrfail($request->id);
        $status = 'disposisi selesai';
        $userDisposisi = User::where('nip',$request->nip_disposisi)->first();
        $input['nip'] = Auth::user()->nip;
        $input['id_disposisi'] = $pengajuanKegiatan->id;

        DB::beginTransaction();
        try {
            if($userDisposisi != null){
                switch($userDisposisi->jabatan){
                    case 'wd1':
                        $status = 'disposisi wd1';
                        break;
                    case 'wd2':
                        $status = 'disposisi wd2';
                        break;
                    case 'wd3':
                        $status = 'disposisi wd3';
                        break;
                    case 'kabag tata usaha':
                        $status = 'disposisi kabag';
                        break;
                    case 'kasubag kemahasiswaan':
                        $status = 'disposisi kasubag';
                        break;
                }
            }

            if (Auth::user()->jabatan != 'kasubag kemahasiswaan') {
                NotifikasiUser::create([
                    'nip'=>$userDisposisi->nip,
                    'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                    'isi_notifikasi'=>'Disposisi surat kegiatan mahasiswa.',
                    'link_notifikasi'=>url('pimpinan/surat-kegiatan-mahasiswa')
                ]);
            }else{
                $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();
                NotifikasiOperator::create([
                    'id_operator'=>$operator->id,
                    'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                    'isi_notifikasi'=>'Disposisi surat kegiatan mahasiswa telah selesai di disposisi.',
                    'link_notifikasi'=>url('operator/surat-kegiatan-mahasiswa')
                ]);
            }

            DaftarDisposisiSuratKegiatanMahasiswa::create($input);
            $pengajuanKegiatan->update([
                'status'=>$status,
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Disposisi Gagal','Disposisi surat kegiatan mahasiswa gagal.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa telah di disposisi');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function showDisposisi(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $disposisiUser = DaftarDisposisiSuratKegiatanMahasiswa::where('id_disposisi',$pengajuanKegiatan->id)
                                                                ->orderBy('created_at')
                                                                ->get();
        $data = collect($disposisiUser->load('user','userDisposisi'));
        $dibuat = [];
        foreach($disposisiUser as $disposisi){
            $dibuat[] = $disposisi->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
        }
        $data->put('dibuat',$dibuat);
        return json_encode($data->toArray(),JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    public function showDisposisiPengajuan(DisposisiSuratKegiatanMahasiswa $disposisi){
        $data = collect($disposisi->load(['pengajuanSuratKegiatanMahasiswa.ormawa']));
        $data->put('dibuat',$disposisi->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $data->put('tanggal_surat',$disposisi->tanggal_surat->isoFormat('D MMMM Y'));
        $data->put('tanggal_terima',$disposisi->tanggal_terima->isoFormat('D MMMM Y'));
        return json_encode($data->toArray(),JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    public function createDisposisiPengajuan(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        return view('operator.tambah_disposisi_pengajuan_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan'));
    }

    public function storeDisposisiPengajuan(Request $request,PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $this->validate($request,[
            'id_pengajuan'=>'required|numeric',
            'nomor_agenda'=>'required|numeric',
            'hal'=>'required|string',
            'tanggal_terima'=>'required|date',
            'tanggal_surat'=>'required|date',
        ]);

        $input = $request->all();
        $user = User::where('jabatan','dekan')->where('status_aktif','aktif')->first();

        DB::beginTransaction();
        try {
            DisposisiSuratKegiatanMahasiswa::create($input);

            $pengajuanKegiatan->update([
                'status'=>'disposisi dekan',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Disposisi surat kegiatan mahasiswa.',
                'link_notifikasi'=>url('pimpinan/surat-kegiatan-mahasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Disposisi Gagal','Disposisi surat kegiatan mahasiswa gagal.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Disposisi pengajuan surat kegiatan mahasiswa telah ditambahkan');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function disposisi(Request $request){
        $pengajuanKegiatan = PengajuanSuratKegiatanMahasiswa::findOrFail($request->id);
        $link = url('pimpinan/surat-kegiatan-mahasiswa');
        $isiNotifikasi = 'Disposisi surat kegiatan mahasiswa.';
        DB::beginTransaction();
        try {
            DisposisiSuratKegiatanMahasiswa::create([
                'id_pengajuan'=>$pengajuanKegiatan->id,
                'nip'=>Session::get('nip'),
                'catatan'=>$request->catatan,
            ]);
            if(Session::get('jabatan') == 'dekan'){
                $user = User::where('jabatan','wd1')->where('status_aktif','aktif')->first(); 
                $pengajuanKegiatan->update([
                    'status'=>'disposisi dekan'
                ]);
            }else if(Session::get('jabatan') == 'wd1'){
                $user = User::where('jabatan','wd2')->where('status_aktif','aktif')->first(); 
                $pengajuanKegiatan->update([
                    'status'=>'disposisi wakil dekan I'
                ]);
            }else if(Session::get('jabatan') == 'wd2'){
                $user = User::where('jabatan','wd3')->where('status_aktif','aktif')->first(); 
                $pengajuanKegiatan->update([
                    'status'=>'disposisi wakil dekan II'
                ]);
            }else if(Session::get('jabatan') == 'wd3'){
                $isiNotifikasi = 'Surat kegiatan mahasiswa telah di disposisi.';
                $link = url('pegawai/surat-kegiatan-mahasiswa');
                $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first(); 
                $pengajuanKegiatan->update([
                    'status'=>'disposisi wakil dekan III'
                ]);
            }
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>$link
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanKegiatan->nim,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Surat Kegiatan Mahasiswa telah di disposisi.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan kegiatan mahasiswa gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat kegiatan mahasiswa didisposisi');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }
    
    private function upload($imageFieldName, $request, $uploadPath){
        $image = $request->file($imageFieldName);
        $ext = $image->getClientOriginalExtension();
        if($image->isValid()){
            $imageName = date('YmdHis').".$ext";
            $image->move($uploadPath,$imageName);            
            return $imageName;
        }
        return false;
    }

    private function delete($imageFieldName,$imageName){
        $exist = Storage::disk($imageFieldName)->exists($imageName);
        if(isset($imageName) && $exist){
            $delete = Storage::disk($imageFieldName)->delete($imageName);
            if($delete) return true;
            return false;
        }
    }
}
