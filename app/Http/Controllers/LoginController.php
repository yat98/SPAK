<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;

class LoginController extends Controller
{
    public function loginAdmin()
    {
        if(Session::get('login')){
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
        $admin = Admin::where('username',$username)->get()->first(); 
        if(!empty($admin)){
            if(Hash::check($password,$admin->password)){
                $session = [ 
                    'id'=>$admin->id,
                    'username'=>$admin->username,
                    'login'=>true
                ];
                Session::put($session);
                $this->setFlashData('success-timer','Login Berhasil','Selamat datang '.$admin->username);
                return redirect('admin');
            }
        }
        Session::flash('username',$username);
        $this->setFlashData('error','Login Gagal','Username atau password salah.');
        return redirect('admin/login');
    }
}
