<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use DataTables;
use App\KodeSurat;
use App\Mahasiswa;
use App\WaktuCuti;
use Carbon\Carbon;
use App\SuratMasuk;
use App\SuratTugas;
use App\TahunAkademik;
use App\PendaftaranCuti;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\SuratPengantarCuti;
use Illuminate\Http\Request;
use App\SuratKeteranganLulus;
use App\SuratPermohonanSurvei;
use App\SuratKegiatanMahasiswa;
use App\SuratPengantarBeasiswa;
use App\SuratPersetujuanPindah;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\SuratRekomendasiPenelitian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\PengajuanSuratKegiatanMahasiswa;
use App\SuratPermohonanPengambilanDataAwal;
use App\SuratPermohonanPengambilanMaterial;

class UserController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $countAllUser = User::count();        
        return view('user.'.$this->segmentUser.'.user',compact('countAllUser','perPage'));
    }

    public function getAllUser(){
        return DataTables::of(User::select(['*']))
                ->editColumn("jabatan", function ($data) {
                    switch ($data->jabatan) {
                        case 'wd1':
                            return ucwords('Wakil Dekan 1');
                            break;
                        case 'wd2':
                            return ucwords('Wakil Dekan 2');
                            break;
                        case 'wd3':
                            return ucwords('Wakil Dekan 3');
                            break; 
                        default:
                            return ucwords($data->jabatan);
                            break; 
                    }
                })
                ->editColumn("status_aktif", function ($data) {
                    return ucwords($data->status_aktif);
                })
                ->addColumn('aksi', function ($data) {
                    return $data->nip;
                })
                ->make(true);
    }

    public function getLimitUser(){
        return DataTables::collection(User::all()->take(5)->sortByDesc('updated_at'))
                    ->editColumn("jabatan", function ($data) {
                        switch ($data->jabatan) {
                            case 'wd1':
                                return ucwords('Wakil Dekan 1');
                                break;
                            case 'wd2':
                                return ucwords('Wakil Dekan 2');
                                break;
                            case 'wd3':
                                return ucwords('Wakil Dekan 3');
                                break; 
                            default:
                                return ucwords($data->jabatan);
                                break; 
                        }
                    })
                    ->editColumn("status_aktif", function ($data) {
                        return ucwords($data->status_aktif);
                    })
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->diffForHumans();
                    })
                    ->editColumn("updated_at", function ($data) {
                        return $data->updated_at->diffForHumans();
                    })
                    ->toJson();
    }

    public function show(User $user){
        $data = collect($user->makeHidden('tanda_tangan'));
        $data->put('created_at',$user->created_at->isoFormat('D MMMM Y H:mm:ss'));
        $data->put('updated_at',$user->updated_at->isoFormat('D MMMM Y H:mm:ss'));
        switch ($user->jabatan) {
            case 'wd1':
                $data->put('jabatan','Wakil Dekan 1');
                break;
            case 'wd2':
                $data->put('jabatan','Wakil Dekan 2');
                break;
            case 'wd3':
                $data->put('jabatan','Wakil Dekan 3');
                break; 
            default:
                $data->put('jabatan',ucwords($user->jabatan));
                break; 
        }
        $data->put('pangkat',ucwords($user->pangkat));
        $data->put('status_aktif',ucwords($user->status_aktif));

        return $data->toJson();
    }

    public function create()
    {
        $formPassword = true;
        return view('user.'.$this->segmentUser.'.tambah_user',compact('formPassword'));
    }

    public function store(UserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $input['tanda_tangan'] = null;
        $this->setFlashData('success','Berhasil','Data user dengan nama '.strtolower($input['nama']).' berhasil ditambahkan');
        User::create($input);
        return redirect($this->segmentUser.'/user');
    }

    public function edit(User $user)
    {
        $formPassword = false;
        return view('user.'.$this->segmentUser.'.edit_user',compact('user','formPassword'));
    }

    public function update(UserRequest $request,User $user)
    {
        $input = $request->all();
        if(isset($input['password'])){
            $input['password'] = Hash::make($request->password);
        }else{
            $input['password'] = $user->password;
        }
        $input['tanda_tangan'] = null;
        $user->update($input);
        $this->setFlashData('success','Berhasil','Data user '.strtolower($user->nama).' berhasil diubah');
        return redirect($this->segmentUser.'/user');
    }

    public function destroy(User $user)
    {
        $user->delete();
        $this->setFlashData('success','Berhasil','Data user '.$user->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/user');
    }

    public function indexPegawai(){
        $perPageDashboard = $this->perPageDashboard;

        $tgl = Carbon::now();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $waktuCuti = isset($tahunAkademikAktif) ? WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first():null;
        
        $kodeSuratAktif = KodeSurat::where('status_aktif','aktif')->first();

        $countAllSuratMasuk  = SuratMasuk::count();

        $countAllSuratAktif = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                               ->where('jenis_surat','surat keterangan aktif kuliah')
                                               ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                               ->count();

        $countAllSuratBaik =  SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                               ->where('jenis_surat','surat keterangan kelakuan baik')
                                               ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                               ->count();
        
        $countAllSuratDispensasi = SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                                    ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                    ->count();

        $countAllSuratRekomendasi = SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                                      ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                      ->count();

        $countAllSuratTugas = SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                          ->whereIn('pengajuan_surat_tugas.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                          ->count();
        
        $countAllSuratPindah = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                                       ->whereIn('pengajuan_surat_persetujuan_pindah.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                       ->count();

        $countAllSuratCuti = SuratPengantarCuti::whereIn('status',['verifikasi kabag','selesai','menunggu tanda tangan'])
                                                 ->count();

        $countAllSuratBeasiswa = SuratPengantarBeasiswa::whereIn('status',['verifikasi kabag','selesai','menunggu tanda tangan'])
                                                         ->count();

        $countAllSuratKegiatan = PengajuanSuratKegiatanMahasiswa::join('surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                                                  ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                                  ->count();

        $countAllWaktuCuti = WaktuCuti::count();

        $countAllPendaftaran = PendaftaranCuti::where('status','selesai')
                                                ->count();
        
        $countAllSuratLulus = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','surat_keterangan_lulus.id_pengajuan','=','pengajuan_surat_keterangan_lulus.id')
                                                    ->whereIn('pengajuan_surat_keterangan_lulus.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                    ->count();

        $countAllSuratMaterial = SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','surat_permohonan_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                                                    ->whereIn('pengajuan_surat_permohonan_pengambilan_material.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                                    ->count();

        $countAllSuratSurvei = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','surat_permohonan_survei.id_pengajuan','=','pengajuan_surat_permohonan_survei.id')
                                                      ->whereIn('pengajuan_surat_permohonan_survei.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                      ->count();

        $countAllSuratPenelitian = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','surat_rekomendasi_penelitian.id_pengajuan','=','pengajuan_surat_rekomendasi_penelitian.id')
                                                               ->whereIn('pengajuan_surat_rekomendasi_penelitian.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                               ->count();

        $countAllSuratDataAwal = SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','surat_permohonan_pengambilan_data_awal.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_data_awal.id')
                                                                     ->whereIn('pengajuan_surat_permohonan_pengambilan_data_awal.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                                     ->count();

        return view('user.'.$this->segmentUser.'.dashboard',compact('perPageDashboard','tgl','tahunAkademikAktif','waktuCuti','kodeSuratAktif','countAllSuratMasuk','countAllSuratAktif','countAllSuratBaik','countAllSuratDispensasi','countAllSuratRekomendasi','countAllSuratTugas','countAllSuratPindah','countAllSuratKegiatan','countAllSuratCuti','countAllSuratBeasiswa','countAllPendaftaran','countAllWaktuCuti','countAllSuratLulus','countAllSuratMaterial','countAllSuratSurvei','countAllSuratPenelitian','countAllSuratDataAwal'));
    }

    public function indexPimpinan(){
        $perPageDashboard = $this->perPageDashboard;
        $bulanList = $this->getMonth();
        $tahunList = $this->generateAngkatan();
        $bulan =date('m');
        $tahun = date('Y');
        $tgl = Carbon::now();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $waktuCuti = isset($tahunAkademikAktif) ? WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first():null;
        
        $kodeSuratAktif = KodeSurat::where('status_aktif','aktif')->first();

        $countAllKodeSurat = KodeSurat::count();

        $countAllSuratMasuk  = SuratMasuk::count();

        $countAllSuratAktif = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                               ->where('jenis_surat','surat keterangan aktif kuliah')
                                               ->where('status','selesai')
                                               ->count();

        $countAllSuratBaik =  SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                               ->where('jenis_surat','surat keterangan kelakuan baik')
                                               ->where('status','selesai')
                                               ->count();
        
        $countAllSuratDispensasi = SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                                    ->where('status','selesai')
                                                    ->count();

        $countAllSuratRekomendasi = SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                                      ->where('status','selesai')
                                                      ->count();

        $countAllSuratTugas = SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                          ->where('pengajuan_surat_tugas.status','selesai')
                                          ->count();
        
        $countAllSuratPindah = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                                       ->where('pengajuan_surat_persetujuan_pindah.status','selesai')
                                                       ->count();

        $countAllSuratCuti = SuratPengantarCuti::where('status','selesai')
                                                 ->count();

        $countAllSuratBeasiswa = SuratPengantarBeasiswa::where('status','selesai')
                                                         ->count();

        $countAllSuratKegiatan = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                                         ->where('status','selesai')
                                                         ->count();

        $countAllSuratLulus = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','surat_keterangan_lulus.id_pengajuan','=','pengajuan_surat_keterangan_lulus.id')
                                                    ->where('pengajuan_surat_keterangan_lulus.status','selesai')
                                                    ->count();
     
        $countAllSuratMaterial = SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','surat_permohonan_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                                                     ->where('pengajuan_surat_permohonan_pengambilan_material.status','selesai')
                                                                     ->count();

        $countAllSuratSurvei = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','surat_permohonan_survei.id_pengajuan','=','pengajuan_surat_permohonan_survei.id')
                                                      ->where('pengajuan_surat_permohonan_survei.status','selesai')
                                                      ->count();

        $countAllSuratPenelitian = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','surat_rekomendasi_penelitian.id_pengajuan','=','pengajuan_surat_rekomendasi_penelitian.id')
                                                               ->where('pengajuan_surat_rekomendasi_penelitian.status','selesai')
                                                               ->count();

        $countAllSuratDataAwal = SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','surat_permohonan_pengambilan_data_awal.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_data_awal.id')
                                                                     ->where('pengajuan_surat_permohonan_pengambilan_data_awal.status','selesai')
                                                                     ->count();

        $countAllWaktuCuti = WaktuCuti::count();

        $countAllPendaftaran = PendaftaranCuti::where('status','selesai')
                                                ->count();

        $chartKemahasiswaan = $this->getChartKemahasiswaan($bulan,$tahun);

        $chartPendidikanPengajaran = $this->getChartPendidikanDanPengajaran($bulan,$tahun); 
        
        $countAllMahasiswa = Mahasiswa::count();

        return view('user.'.$this->segmentUser.'.dashboard',compact('perPageDashboard','bulanList','tahunList','tgl','tahunAkademikAktif','waktuCuti','kodeSuratAktif','countAllKodeSurat','countAllSuratMasuk','countAllSuratAktif','countAllSuratBaik','countAllSuratDispensasi','countAllSuratRekomendasi','countAllSuratTugas','countAllSuratPindah','countAllSuratKegiatan','countAllSuratCuti','countAllSuratBeasiswa','countAllPendaftaran','countAllWaktuCuti','chartKemahasiswaan','chartPendidikanPengajaran','countAllSuratLulus','countAllSuratMaterial','countAllSuratSurvei','countAllSuratPenelitian','countAllSuratDataAwal','countAllMahasiswa'));
    }

    public function searchChartKemahasiswaan(Request $request){
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $chartKemahasiswaan = $this->getChartKemahasiswaan($bulan,$tahun);
        return $chartKemahasiswaan;
    }

    public function searchChartPendidikanPengajaran(Request $request){
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $chartPendidikanPengajaran = $this->getChartPendidikanDanPengajaran($bulan,$tahun);
        return $chartPendidikanPengajaran;
    }

    public function indexTandaTangan(){
        $nip = Auth::user()->nip;
        $user = User::where('nip',$nip)->first();
        return view('user.'.$this->segmentUser.'.tanda_tangan',compact('user'));
    }

    public function updateTandaTangan(Request $request){
        $nip =  Auth::user()->nip;
        $user = User::where('nip',$nip)->first();
        $user->update([
            'tanda_tangan'=>$request->tanda_tangan
        ]);
        $this->setFlashData('success','Berhasil','Data tanda tangan berhasil ditambahkan');
        return redirect($this->segmentUser.'/tanda-tangan');
    }

    public function profil(){
        $nip = Auth::user()->nip;
        $user = User::where('nip',$nip)->get()->first();
        $status = $user->status_aktif;
        return view('user.'.$this->segmentUser.'.profil',compact('user','status'));
    }

    public function profilPassword(){
        return view('user.'.$this->segmentUser.'.profil_password');
    }
    
    public function updateProfil(Request $request,User $user){
        $this->validate($request,[
            'nip'=>'required|unique:user,nip,'.$request->nip.',nip',
            'nama'=>'required|string|alpha_spaces'
        ]);
        $user->update($request->all());
        Session::forget(['nip','username']);
        Session::put([
            'nip'=>$request->nip,
            'username'=>$request->nama,
        ]);
        $this->setFlashData('success','Berhasil','Profil berhasil diubah');
        return redirect($this->segmentUser);
    }

    public function updatePassword(Request $request){
        $nip =  Auth::user()->nip;
        $user = User::where('nip',$nip)->first();
        $this->validate($request,[
            'password_lama'=>function($attr,$val,$fail) use($user){
                if (!Hash::check($val, $user->password)) {
                    $fail('password lama tidak sesuai.');
                }
            },
            'password'=>'required|string|max:60|confirmed',
            'password_confirmation'=>'required|string|max:60'
       ]);
       $user->update([
           'password'=>Hash::make($request->password)
       ]);
       Session::flush();
       $this->setFlashData('success','Berhasil','Password  berhasil diubah');
       return redirect($this->segmentUser);
    }

    public function logout(){
        Session::flush();
        Session::regenerate(true);
        return redirect('/');
    }

    private function getMonth(){
        return [
            '1'=>'Januari',
            '2'=>'Februari',
            '3'=>'Maret',
            '4'=>'April',
            '5'=>'Mei',
            '6'=>'Juni',
            '7'=>'Juli',
            '8'=>'Agustus',
            '9'=>'September',
            '10'=>'Oktober',
            '11'=>'November',
            '12'=>'Desember',
        ];
    }

    private function getChartKemahasiswaan($bulan,$tahun){
        return [
            'Surat Keterangan Aktif Kuliah'=>SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                                              ->where('jenis_surat','surat keterangan aktif kuliah')
                                                              ->where('status','selesai')
                                                              ->whereYear('surat_keterangan.created_at',$tahun)
                                                              ->whereMonth('surat_keterangan.created_at',$bulan)
                                                              ->count(),

            'Surat Keterangan Kelakuan Baik'=>SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                                               ->where('jenis_surat','surat keterangan kelakuan baik')
                                                               ->where('status','selesai')
                                                               ->whereYear('surat_keterangan.created_at',$tahun)
                                                               ->whereMonth('surat_keterangan.created_at',$bulan)
                                                               ->count(),

            'Surat Dispensasi'=>SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                                  ->where('status','selesai')
                                                  ->whereYear('surat_dispensasi.created_at',$tahun)
                                                  ->whereMonth('surat_dispensasi.created_at',$bulan)                                     
                                                  ->count(),


            'Surat Rekomendasi'=>SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                                ->where('status','selesai')
                                                ->whereYear('surat_rekomendasi.created_at',$tahun)
                                                ->whereMonth('surat_rekomendasi.created_at',$bulan)
                                                ->count(),

            'Surat Tugas'=> SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                                ->where('pengajuan_surat_tugas.status','selesai')
                                                ->whereYear('surat_tugas.created_at',$tahun)
                                                ->whereMonth('surat_tugas.created_at',$bulan)
                                                ->count(),
            
            'Surat Persetujuan Pindah'=>SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan')
                                                            ->where('status','selesai')
                                                            ->whereYear('surat_persetujuan_pindah.created_at',$tahun)
                                                            ->whereMonth('surat_persetujuan_pindah.created_at',$bulan)
                                                            ->count(),

            'Surat Pengantar Cuti'=>SuratPengantarCuti::where('status','selesai')
                                                        ->whereYear('created_at',$tahun)
                                                        ->whereMonth('created_at',$bulan)
                                                        ->count(),
            
            'Surat Pengantar Beasiswa'=>SuratPengantarBeasiswa::where('status','selesai')
                                            ->whereYear('created_at',$tahun)
                                            ->whereMonth('created_at',$bulan)
                                            ->count(),

            'Surat Kegiatan Mahasiswa'=> SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan')
                                            ->where('status','selesai')
                                            ->whereYear('surat_kegiatan_mahasiswa.created_at',$tahun)
                                            ->whereMonth('surat_kegiatan_mahasiswa.created_at',$bulan)
                                            ->count(),
        ];
    }

    private function getChartPendidikanDanPengajaran($bln,$thn){
        return [
            'Surat Keterangan Lulus'=> SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan')
                                            ->whereIn('status',['selesai'])
                                            ->whereYear('surat_keterangan_lulus.created_at',$thn)
                                            ->whereMonth('surat_keterangan_lulus.created_at',$bln)
                                            ->count(),
            'Surat Permohonan Pengambilan Material'=> SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                            ->whereIn('status',['selesai'])
                                            ->whereYear('surat_permohonan_pengambilan_material.created_at',$thn)
                                            ->whereMonth('surat_permohonan_pengambilan_material.created_at',$bln)
                                            ->count(),
            'Surat Permohonan Survei'=> SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                            ->where('status','selesai')
                                            ->whereYear('surat_permohonan_survei.created_at',$thn)
                                            ->whereMonth('surat_permohonan_survei.created_at',$bln)
                                            ->count(),
            'Surat Rekomendasi Penelitian'=>  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                                ->where('status','selesai')
                                                ->whereYear('surat_rekomendasi_penelitian.created_at',$thn)
                                                ->whereMonth('surat_rekomendasi_penelitian.created_at',$bln)
                                                ->count(),
            'Surat Permohonan Pengambilan Data Awal'=>SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                                                        ->whereIn('status',['selesai','menunggu tanda tangan'])
                                                        ->whereYear('surat_permohonan_pengambilan_data_awal.created_at',$thn)
                                                        ->whereMonth('surat_permohonan_pengambilan_data_awal.created_at',$bln)
                                                        ->count()
        ];
    }
}
