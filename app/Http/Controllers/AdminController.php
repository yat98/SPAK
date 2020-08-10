<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Admin;
use App\Ormawa;
use App\Jurusan;
use App\Operator;
use App\Mahasiswa;
use App\ProgramStudi;
use App\TahunAkademik;
use App\PimpinanOrmawa;
use App\StatusMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(){
        $perPageDashboard = $this->perPageDashboard;
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $countAllJurusan = Jurusan::count();
        $countAllProdi = ProgramStudi::count();
        $countAllTahunAkademik = TahunAkademik::count();
        $countAllMahasiswa = Mahasiswa::count();
        $countAllUser = User::count();
        $countAllOrmawa = Ormawa::count();
        $countAllPimpinanOrmawa = PimpinanOrmawa::count();
        $countAllStatusMahasiswa = StatusMahasiswa::count();
        $countAllOperator = Operator::count();

        return view('user.'.$this->segmentUser.'.dashboard',compact('perPageDashboard','countAllJurusan','countAllProdi','countAllMahasiswa','countAllTahunAkademik','countAllUser','countAllStatusMahasiswa','countAllOrmawa','countAllPimpinanOrmawa','tahunAkademikAktif','countAllOperator'));
    }

    public function profil(){
        $id = Auth::user()->id;
        $admin = Admin::where('id',$id)->get()->first();
        return view('user.'.$this->segmentUser.'.profil',compact('admin'));
    }

    public function profilPassword(){
        return view('user.'.$this->segmentUser.'.profil_password');
    }

    public function update(Request $request){ 
        $id = Auth::user()->id;
        $admin = Admin::where('id',$id)->first();
        $this->validate($request,[
            'username'=>'required|string'
        ]);
        $admin->update([
            'username'=>$request->username
        ]);
        $this->setFlashData('success','Berhasil','Username berhasil diubah');
        return redirect($this->segmentUser);
    }

    public function updatePassword(Request $request){        
        $id = Auth::user()->id;
        $admin = Admin::where('id',$id)->first();
        $this->validate($request,[
            'password_lama'=>function($attr,$val,$fail) use($admin){
                if (!Hash::check($val, $admin->password)) {
                    $fail('password lama tidak sesuai.');
                }
            },
            'password'=>'required|string|max:60|confirmed',
            'password_confirmation'=>'required|string|max:60'
       ]);
       $admin->update([
           'password'=>$request->password
       ]);
       Session::flush();
       $this->setFlashData('success','Berhasil','Password  berhasil diubah');
       return redirect($this->segmentUser);
    }

    public function logout(){
        Session::flush();
        return redirect('admin');
    }
}
