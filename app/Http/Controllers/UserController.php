<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\KodeSurat;
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
use App\SuratKegiatanMahasiswa;
use App\SuratPengantarBeasiswa;
use App\SuratPersetujuanPindah;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $perPage = $this->perPage;
        $userList = User::paginate($perPage);
        $countUser = $userList->count();
        $countAllUser = $countUser;        
        return view('user.'.$this->segmentUser.'.user',compact('countUser','userList','countAllUser','perPage'));
    }

    public function create()
    {
        $formPassword = true;
        return view('user.'.$this->segmentUser.'.tambah_user',compact('formPassword'));
    }

    public function search(Request $request){
        $perPage = $this->perPage;
        $keyword = $request->all();
        if(isset($keyword['keyword'])){
            $nama = $keyword['keyword'] != null ? $keyword['keyword'] : '';
            $userList = User::where('nama','like','%'.$nama.'%')
                            ->paginate($perPage)
                            ->appends($request
                            ->except('page'));
            $countUser = count($userList);
            $countAllUser = User::all()->count();
            if($countUser < 1){
                $this->setFlashData('search','Hasil Pencarian','Data user tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.user',compact('countUser','userList','countAllUser','perPage'));
        }else{
            return redirect($this->segmentUser.'/user');
        }
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
        $this->setFlashData('success','Berhasil','Data user '.strtolower($user->nama).' berhasil dihapus');
        return redirect($this->segmentUser.'/user');
    }

    public function pegawaiDashboard(){
        $tgl = Carbon::now();
        if(Session::get('jabatan') == 'kasubag kemahasiswaan'){
            $kodeSuratList = KodeSurat::whereIn('jenis_surat',['surat keterangan','surat dispensasi','surat pengantar cuti','surat rekomendasi','surat persetujuan pindah','surat tugas','surat pengantar beasiswa','surat kegiatan mahasiswa'])->get();
        }else{
            $kodeSuratList = KodeSurat::whereNotIn('jenis_surat',['surat keterangan','surat dispensasi','surat pengantar cuti','surat rekomendasi','surat persetujuan pindah','surat tugas','surat pengantar beasiswa','surat kegiatan mahasiswa'])->get();
        }
        $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan aktif kuliah')
                                        ->get();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $suratMasukList = SuratMasuk::orderByDesc('created_at')->get();
        $suratKeteranganKelakuanList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan kelakuan baik')
                                        ->get();
        $suratDispensasiList = SuratDispensasi::orderBy('status')->get();
        $suratRekomendasiList = SuratRekomendasi::orderBy('status')->get();
        $suratTugasList = SuratTugas::orderBy('status')->get();
        $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                        ->orderByDesc('surat_persetujuan_pindah.created_at')
                                        ->orderByDesc('nomor_surat')
                                        ->get();
        $suratCutiList = SuratPengantarCuti::orderByDesc('nomor_surat')->get();
        $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->get();
        $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->get();
        $waktuCutiList = WaktuCuti::all();
        $pendaftaranCutiList = PendaftaranCuti::where('status','diterima')->orderByDesc('created_at')->get();
        $waktuCuti = isset($tahunAkademikAktif) ? WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first():null;
        $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->orderBy('status')
                            ->get();

        $countAllKodeSurat = $kodeSuratList->count();
        $countAllSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $countAllSuratKeteranganKelakuan = $suratKeteranganKelakuanList->count();
        $countAllSuratMasuk  = $suratMasukList->count();
        $countAllSuratDispensasi = $suratDispensasiList->count();
        $countAllSuratRekomendasi = $suratRekomendasiList->count();
        $countAllSuratTugas = $suratTugasList->count();
        $countAllSuratPersetujuanPindah = $suratPersetujuanPindahList->count();
        $countAllSuratCuti = $suratCutiList->count();
        $countAllSuratBeasiswa = $suratBeasiswaList->count();
        $countAllSuratKegiatan = $suratKegiatanList->count();
        $countAllWaktuCuti = $waktuCutiList->count();
        $countAllPendaftaranCuti = $pendaftaranCutiList->count();
        $countAllsuratLulus = $suratLulusList->count();
      
        $kodeSuratList = $kodeSuratList->take(5);
        $suratMasukList = $suratMasukList->take(5);
        $suratKeteranganAktifList = $suratKeteranganAktifList->take(5);
        $suratKeteranganKelakuanList = $suratKeteranganKelakuanList->take(5);
        $suratDispensasiList = $suratDispensasiList->take(5);
        $suratRekomendasiList = $suratRekomendasiList->take(5);
        $suratTugasList = $suratTugasList->take(5);
        $suratPersetujuanPindahList = $suratPersetujuanPindahList->take(5);
        $suratCutiList = $suratCutiList->take(5);
        $suratBeasiswaList = $suratBeasiswaList->take(5);
        $suratKegiatanList = $suratKegiatanList->take(5);
        $waktuCutiList = $waktuCutiList->take(5);
        $pendaftaranCutiList = $pendaftaranCutiList->take(5);
        $suratLulusList = $suratLulusList->take(5);
       
        return view('user.'.$this->segmentUser.'.dashboard',compact('tahunAkademikAktif','countAllKodeSurat','kodeSuratList','countAllSuratKeteranganAktif','suratMasukList','countAllSuratMasuk','suratKeteranganAktifList','suratKeteranganKelakuanList','countAllSuratKeteranganKelakuan','suratDispensasiList','countAllSuratDispensasi','waktuCuti','tgl','suratRekomendasiList','countAllSuratRekomendasi','suratTugasList','countAllSuratTugas','suratPersetujuanPindahList','countAllSuratPersetujuanPindah','suratCutiList','countAllSuratCuti','suratBeasiswaList','countAllSuratBeasiswa','suratKegiatanList','countAllSuratKegiatan','waktuCutiList','countAllWaktuCuti','pendaftaranCutiList','countAllPendaftaranCuti','suratLulusList','countAllsuratLulus'));
    }

    public function pimpinanDashboard(){
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan aktif kuliah')
                                        ->get();
        $suratKeteranganKelakuanList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan kelakuan baik')
                                        ->get();
        $suratDispensasiList = SuratDispensasi::orderByDesc('created_at')->where('status','selesai')->get();
        $suratRekomendasiList = SuratRekomendasi::orderByDesc('created_at')->where('status','selesai')->get();
        $suratTugasList = SuratTugas::orderByDesc('created_at')->where('status','selesai')->get();
        $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                        ->orderByDesc('surat_persetujuan_pindah.created_at')
                                        ->orderByDesc('nomor_surat')
                                        ->get();
        $suratCutiList = SuratPengantarCuti::orderByDesc('nomor_surat')->get();
        $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->where('status','selesai')->get();
        $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->get();
        $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                            ->whereIn('status',['selesai'])
                            ->orderBy('status')
                            ->get();

        $countAllSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $countAllSuratKeteranganKelakuan = $suratKeteranganKelakuanList->count();
        $countAllSuratDispensasi = $suratDispensasiList->count();
        $countAllSuratRekomendasi = $suratRekomendasiList->count();
        $countAllSuratTugas = $suratTugasList->count();
        $countAllSuratPersetujuanPindah = $suratPersetujuanPindahList->count();
        $countAllSuratCuti = $suratCutiList->count();
        $countAllSuratBeasiswa = $suratBeasiswaList->count();
        $countAllSuratKegiatan = $suratKegiatanList->count();
        $countAllsuratLulus = $suratLulusList->count();

        $suratKeteranganAktifList = $suratKeteranganAktifList->take(5);
        $suratKeteranganKelakuanList = $suratKeteranganKelakuanList->take(5);
        $suratDispensasiList = $suratDispensasiList->take(5);
        $suratRekomendasiList = $suratRekomendasiList->take(5);
        $suratTugasList = $suratTugasList->take(5);
        $suratPersetujuanPindahList = $suratPersetujuanPindahList->take(5);
        $suratCutiList = $suratCutiList->take(5);
        $suratBeasiswaList = $suratBeasiswaList->take(5);
        $suratKegiatanList = $suratKegiatanList->take(5);
        $suratLulusList = $suratLulusList->take(5);
       
        return view('user.'.$this->segmentUser.'.dashboard',compact('tahunAkademikAktif','suratKeteranganAktifList','suratKeteranganKelakuanList','suratDispensasiList','suratRekomendasiList','suratTugasList','suratPersetujuanPindahList','suratCutiList','suratBeasiswaList','suratKegiatanList','countAllSuratKeteranganAktif','countAllSuratKeteranganKelakuan','countAllSuratDispensasi','countAllSuratRekomendasi','countAllSuratTugas','countAllSuratPersetujuanPindah','countAllSuratCuti','countAllSuratBeasiswa','countAllSuratKegiatan','suratLulusList','countAllsuratLulus'));
    }

    public function indexTandaTangan(){
        $nip = Session::get('nip');
        $user = User::where('nip',$nip)->first();
        return view('user.'.$this->segmentUser.'.tanda_tangan',compact('user'));
    }

    public function updateTandaTangan(Request $request){
        $nip = Session::get('nip');
        $user = User::where('nip',$nip)->first();
        $user->update([
            'tanda_tangan'=>$request->tanda_tangan
        ]);
        $this->setFlashData('success','Berhasil','Data tanda tangan berhasil ditambahkan');
        return redirect($this->segmentUser.'/tanda-tangan');
    }

    public function profil(){
        $nip = Session::get('nip');
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
        $nip = Session::get('nip');
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
        return redirect('/');
    }
}
