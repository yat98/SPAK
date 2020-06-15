<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Admin;
use App\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(){
        if(Session::get('status') == 'mahasiswa'){
            return redirect('mahasiswa');
        }else if(Session::get('status') == 'pegawai'){
            return redirect('pegawai');
        }else if(Session::get('status') == 'admin'){
            return redirect('admin');
        }
        else if(Session::get('status') == 'pimpinan'){
            return redirect('pimpinan');
        }
        return view('login.login');
    }

    public function checkLogin(Request $request){
        $validator = $this->validate($request,[
            'username'=>'required|string|max:60',
            'password'=>'required|string|max:60',
        ]);

        $username = $request->username;
        $password = $request->password;

        if($request->jenis_user == 'pimpinan'){
            $user = User::whereIn('jabatan',['dekan','wd1','wd2','wd3'])->where('nip',$request->username)->first();
        }else if($request->jenis_user == 'pegawai'){
            $user = User::whereIn('jabatan',['kasubag kemahasiswaan','kasubag pendidikan dan pengajaran'])->where('nip',$request->username)->first();
        }else{
            $user = Mahasiswa::where('nim',$request->username)->first();
            if($user != null){
                if($user->tahunAkademik->count() < 1){
                    Session::flash('username',$username);
                    Session::flash('jenis_user',$request->jenis_user);
                    $this->setFlashData('error','Login Gagal','Username atau password salah.');
                    return redirect('/');
                }else{
                    $status = $user->load(['tahunAkademik'=>function($query){
                        $query->orderByDesc('created_at');
                    }])->tahunAkademik->first()->pivot->status;
                    if($status == 'lulus' || $status == 'drop out' || $status == 'keluar'){
                        Session::flash('username',$username);
                        Session::flash('jenis_user',$request->jenis_user);
                        $this->setFlashData('error','Login Gagal','Username atau password salah.');
                        return redirect('/');
                    }
                }
            }
        }
        
        if(!empty($user)){
            if(Hash::check($password,$user->password)){
                $session = [ 
                    'username'=>$user->nama,
                ];

                if($request->jenis_user == 'mahasiswa'){
                    $session['nim'] = $user->nim; 
                    $session['status'] = 'mahasiswa';
                    $session['ipk'] = $user->ipk;
                }else{
                    $session['status'] = ($request->jenis_user == 'pimpinan') ? 'pimpinan':'pegawai';
                    $session['nip'] = $user->nip;
                    $session['jabatan'] = $user->jabatan;
                }
                Session::put($session);
                $this->setFlashData('success-timer','Login Berhasil','Selamat Datang '.$user->nama);
                return redirect($session['status']);
            }
        }
        Session::flash('username',$username);
        Session::flash('jenis_user',$request->jenis_user);
        $this->setFlashData('error','Login Gagal','Username atau password salah.');
        return redirect('/');
    }

    public function loginAdmin()
    {
        if(Session::get('status') == 'mahasiswa'){
            return redirect('mahasiswa');
        }else if(Session::get('status') == 'pegawai'){
            return redirect('pegawai');
        }else if(Session::get('status') == 'admin'){
            return redirect('admin');
        }else if(Session::get('status') == 'pimpinan'){
            return redirect('pimpinan');
        }
        return view('login.login_admin');
    }

    public function checkLoginAdmin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $this->validate($request,[
            'username'=>'required|string|max:60',
            'password'=>'required|string|max:60',
        ]);
        $admin = Admin::where('username',$username)->first(); 
        if(!empty($admin)){
            if(Hash::check($password,$admin->password)){
                $session = [ 
                    'id'=>$admin->id,
                    'username'=>$admin->username,
                    'login'=>true,
                    'status'=>'admin'
                ];
                Session::put($session);
                $this->setFlashData('success-timer','Login Berhasil','Selamat Datang '.$admin->username);
                return redirect('admin');
            }
        }
        Session::flash('username',$username);
        $this->setFlashData('error','Login Gagal','Username atau password salah.');
        return redirect('admin/login');
    }
}
