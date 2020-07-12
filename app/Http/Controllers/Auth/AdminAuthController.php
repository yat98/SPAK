<?php

namespace App\Http\Controllers\Auth;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminAuthController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 3;
    protected $decayMinutes = 2;

    public function __construct()
    {
        $this->middleware('guest:admin')->except('postLogout');
    }

    public function getLogin()
    {
        return view('login.login_admin');
    }

    public function postLogin(Request $request)
    {
        $validator = $this->validate($request,[
            'username'=>'required|string|max:60',
            'password'=>'required|string|max:60',
        ]);

        if (auth()->guard('admin')->attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            Session::put('jenis_user','admin');
            $this->setFlashData('success-timer','Login Berhasil','Selamat Datang '.$request->username);
            return redirect('admin');
        } else {
            $this->incrementLoginAttempts($request);
            Session::flash('username',$request->username);
            $this->setFlashData('error','Login Gagal','Username atau password salah.');
            return redirect('admin/login');
        }
    }

    public function postLogout()
    {
        auth()->guard('admin')->logout();
        session()->flush();

        return redirect('admin');
    }
}
