<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\KodeSurat;
use App\SuratMasuk;
use App\TahunAkademik;
use App\SuratDispensasi;
use App\SuratKeterangan;
use Illuminate\Http\Request;
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
        $kodeSuratList = KodeSurat::all();
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
        
        $countAllKodeSurat = $kodeSuratList->count();
        $countAllSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $countAllSuratKeteranganKelakuan = $suratKeteranganKelakuanList->count();
        $countAllSuratMasuk  = $suratMasukList->count();
        $countAllSuratDispensasi = $suratDispensasiList->count();
        
        
        $countKodeSurat = count($kodeSuratList);
        $countSuratKeteranganAktif = count($suratKeteranganAktifList);
        $countSuratMasuk = count($suratMasukList);
        $countSuratKeteranganKelakuan = count($suratKeteranganKelakuanList);
        $countSuratDispensasi = count($suratDispensasiList);
        
        $kodeSuratList = $kodeSuratList->take(5);
        $suratMasukList = $suratMasukList->take(5);
        $suratKeteranganAktifList = $suratKeteranganAktifList->take(5);
        $suratKeteranganKelakuanList = $suratKeteranganKelakuanList->take(5);
        $suratDispensasiList = $suratDispensasiList->take(5);

       
        return view('user.'.$this->segmentUser.'.dashboard',compact('tahunAkademikAktif','countAllKodeSurat','countKodeSurat','kodeSuratList','countAllSuratKeteranganAktif','suratMasukList','countAllSuratMasuk','countSuratMasuk','countSuratKeteranganAktif','suratKeteranganAktifList','suratKeteranganKelakuanList','countAllSuratKeteranganKelakuan','countSuratKeteranganKelakuan','suratDispensasiList','countAllSuratDispensasi','countSuratDispensasi'));
    }

    public function pimpinanDashboard(){
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        return view('user.'.$this->segmentUser.'.dashboard',compact('tahunAkademikAktif'));
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
