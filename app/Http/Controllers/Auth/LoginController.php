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
        $isLogin = Session::get('login');
        
        if($isLogin == true){
            $jenisUser = Session::get('jenis_user');
            return redirect($jenisUser);
        }

        return view('login.login');
    }

    public function postLogin(Request $request)
    {
        $jenisUser = $request->jenis_user;
        $guard = $jenisUser;
        Session::flash('jenis_user_login',$jenisUser);

        $validator = $this->validate($request,[
            'username'=>'required|string|max:60',
            'password'=>'required|string|max:60',
        ]);
        

        switch ($jenisUser) {
            case 'mahasiswa':
                $user = Mahasiswa::where('nim',$request->username)->first();
                $input = [
                    'nim'=>$request->username,
                    'password'=>$request->password
                ];
                break;
            case 'operator':
                $user = Operator::where('username',$request->username)->first();
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
                break;
        }

        if (auth()->guard($guard)->attempt($input)) {
            if($guard == 'user' || $guard == 'operator'){
                ($guard == 'operator') ? Session::put('jabatan',$user->bagian) : Session::put('jabatan',$user->jabatan);

                if(($user->jabatan == 'kasubag kemahasiswaan' || $user->jabatan == 'kasubag pendidikan dan pengajaran' || $user->jabatan == 'kasubag umum & bmn') && $jenisUser != 'pegawai'){
                    $this->errorLogin($request);
                    return redirect('/');
                }else if(($user->jabatan == 'dekan' || $user->jabatan == 'wd1' || $user->jabatan == 'wd2' ||$user->jabatan == 'wd3' || $user->jabatan == 'kabag tata usaha' || $user->jabatan == 'kepala perpustakaan') && $jenisUser != 'pimpinan'){
                    $this->errorLogin($request);
                    return redirect('/');
                }

                if($user->status_aktif != 'aktif'){
                    $this->errorLogin($request);
                    return redirect('/');
                }
            }
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);            
            Session::put('guard',$guard);
            Session::put('login',true);
            Session::put('jenis_user',$jenisUser);
            $this->setFlashData('success-timer','Login Berhasil','Selamat Datang '.$user->nama);
            return redirect($jenisUser);
        } else {
            $this->errorLogin($request);
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

    private function errorLogin(Request $request){
        $this->incrementLoginAttempts($request);
        Session::flash('username', $request->username);
        $this->setFlashData('error', 'Login Gagal', 'Username atau password salah.');
    }
}
