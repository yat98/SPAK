<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\KodeSurat;
use App\TahunAkademik;
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
        $countAllKodeSurat = $kodeSuratList->count();
        $kodeSuratList = $kodeSuratList->take(5);
        $countKodeSurat = count($kodeSuratList);
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        return view('user.'.$this->segmentUser.'.dashboard',compact('tahunAkademikAktif','countAllKodeSurat','countKodeSurat','kodeSuratList'));
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

    public function logout(){
        Session::flush();
        return redirect('/');
    }
}
