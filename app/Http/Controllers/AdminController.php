<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Admin;
use App\Ormawa;
use App\Jurusan;
use App\Mahasiswa;
use App\ProgramStudi;
use App\TahunAkademik;
use App\PimpinanOrmawa;
use App\StatusMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(){
        $jurusanList = Jurusan::all()->sortBy('updated_at');
        $prodiList = ProgramStudi::all()->sortBy('updated_at');
        $tahunAkademikList = TahunAkademik::orderBy('status_aktif')->get();
        $mahasiswaList = Mahasiswa::all()->sortBy('updated_at');
        $userList = User::all();
        $ormawaList = Ormawa::all();
        $pimpinanOrmawaList = PimpinanOrmawa::all();
        $statusMahasiswaList = StatusMahasiswa::join('tahun_akademik','tahun_akademik.id','=','status_mahasiswa.id_tahun_akademik')
                                    ->join('mahasiswa','mahasiswa.nim','=','status_mahasiswa.nim')
                                    ->get()
                                    ->sortByDesc('created_at');
        $countUser = $userList->count();
        $countJurusan = $jurusanList->count();
        $countProdi = $prodiList->count();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $countMahasiswa = $mahasiswaList->count();
        $countTahunAkademik = $tahunAkademikList->count();
        $countTahunAkademik = $tahunAkademikList->count();
        $countStatusMahasiswa = $statusMahasiswaList->count();
        $countOrmawa = $ormawaList->count();
        $countPimpinanOrmawa = $pimpinanOrmawaList->count();
        $jurusanList = $jurusanList->take(5);
        $prodiList = $prodiList->take(5);
        $userList = $userList->take(5);
        $tahunAkademikList = $tahunAkademikList->take(5);
        $mahasiswaList = $mahasiswaList->take(5);
        $statusMahasiswaList = $statusMahasiswaList->take(5);
        $ormawaList = $ormawaList->take(5);
        $pimpinanOrmawaListList = $pimpinanOrmawaList->take(5);
        return view('user.'.$this->segmentUser.'.dashboard',compact('countJurusan','countProdi','countMahasiswa','tahunAkademikAktif','jurusanList','prodiList','tahunAkademikList','mahasiswaList','countTahunAkademik','userList','countUser','countStatusMahasiswa','statusMahasiswaList','ormawaList','countOrmawa','pimpinanOrmawaList','countPimpinanOrmawa'));
    }

    public function profil(){
        $id = Session::get('id');
        $admin = Admin::where('id',$id)->get()->first();
        return view('user.'.$this->segmentUser.'.profil',compact('admin'));
    }

    public function profilPassword(){
        return view('user.'.$this->segmentUser.'.profil_password');
    }

    public function update(Request $request){ 
        $id = Session::get('id');
        $admin = Admin::where('id',$id)->first();
        $this->validate($request,[
            'username'=>'required|string'
        ]);
        $admin->update([
            'username'=>$request->username
        ]);
        Session::forget('username');
        Session::put('username',$request->username);
        $this->setFlashData('success','Berhasil','Username berhasil diubah');
        return redirect($this->segmentUser);
    }

    public function updatePassword(Request $request){        
        $id = Session::get('id');
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
