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
        return view('login.login');
    }

    public function checkLogin(Request $request){
        $user;
        $username = $request->username;
        $password = $request->password;
        $this->validate($request,[
            'username'=>'required|string|max:60',
            'password'=>'required|string|max:60',
        ]);

        if($request->jenis_user == 'pimpinan'){
            $user = User::where('jabatan','like',"%dekan%")->where('nip',$request->username)->first();
        }else if($request->jenis_user == 'pegawai'){
            $user = User::where('jabatan','like',"%kasubag%")->where('nip',$request->username)->first();
        }else{
            $user = Mahasiswa::where('nim',$request->username)->first();
        }
        
        if(!empty($user)){
            if(Hash::check($password,$user->password)){
                $session = [ 
                    'username'=>$user->nama,
                ];

                if($request->jenis_user == 'mahasiswa'){
                    $session['nim'] = $user->nim; 
                    $session['status'] = 'mahasiswa';
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
