<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\User;
use App\Operator;
use App\Mahasiswa;
use App\PimpinanOrmawa;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 3;
    protected $decayMinutes = 2;

    public function getLogin()
    {
        $jenisUser = Session::get('jenis_user');

        if($jenisUser != null){
            return redirect($jenisUser);
        }
        return view('login.login');
    }

    public function postLogin(Request $request)
    {
        $validator = $this->validate($request,[
            'username'=>'required|string|max:60',
            'password'=>'required|string|max:60',
        ]);
        
        $jenisUser = $request->jenis_user;
        $guard = $jenisUser;

        switch ($jenisUser) {
            case 'mahasiswa':
                $input = [
                    'nim'=>$request->username,
                    'password'=>$request->password
                ];
                $user = Mahasiswa::find($request->username);
                break;
            case 'operator':
                $user = Operator::find($request->username);
                $input = [
                    'username'=>$request->username,
                    'password'=>$request->password
                ];
                break;
            default:
                $guard = 'user';
                $user = User::find($request->username);
                $input = [
                    'nip'=>$request->username,
                    'password'=>$request->password
                ];
                if($user->jabatan == 'dekan' || $user->jabatan == 'wd1' || $user->jabatan == 'wd2' || $user->jabatan == 'wd3' || $user->jabatan == 'kabag tata usaha'){
                    $jenisUser = 'pimpinan';
                }else{
                    $jenisUser = 'pegawai';
                }
                break;
        }
    
        if (auth()->guard($guard)->attempt($input)) {
            if($guard == 'mahasiswa'){
                if($user != null){
                    if($user->tahunAkademik->count() < 1){
                        $this->incrementLoginAttempts($request);
                        Session::flash('username',$request->username);
                        $this->setFlashData('error','Login Gagal','Username atau password salah.');
                        return redirect('/');
                    }else{
                        $status = $user->load(['tahunAkademik'=>function($query){
                            $query->orderByDesc('created_at');
                        }])->tahunAkademik->first()->pivot->status;
                        if($status == 'lulus' || $status == 'drop out' || $status == 'keluar'){
                            $this->incrementLoginAttempts($request);
                            Session::flash('username',$request->username);
                            $this->setFlashData('error','Login Gagal','Username atau password salah.');
                            return redirect('/');
                        }
                    }
                }
            }else if($guard == 'user'){
                if($user->status_aktif != 'aktif'){
                    $this->incrementLoginAttempts($request);
                    Session::flash('username',$request->username);
                    $this->setFlashData('error','Login Gagal','Username atau password salah.');
                    return redirect('/');
                }
            }

            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            Session::put('jabatan',$user->jabatan);
            Session::put('jenis_user',$jenisUser);
            Session::put('guard',$guard);
            $this->setFlashData('success-timer','Login Berhasil','Selamat Datang '.$user->nama);
            return redirect($jenisUser);
        } else {
            $this->incrementLoginAttempts($request);
            Session::flash('username',$request->username);
            $this->setFlashData('error','Login Gagal','Username atau password salah.');
            return redirect('/');
        }
    }

    public function postLogout()
    {
        $guard = Session::get('guard');
        auth()->guard($guard)->logout();
        session()->flush();

        return redirect('/');
    }
}
