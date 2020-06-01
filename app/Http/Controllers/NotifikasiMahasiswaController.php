<?php

namespace App\Http\Controllers;

use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Session;

class NotifikasiMahasiswaController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $notifikasiList = NotifikasiMahasiswa::where('nim',Session::get('nim'))->orderByDesc('created_at')->paginate($perPage);
        $countAllNotifikasi = count($notifikasiList);
        return view($this->segmentUser.'.notifikasi_mahasiswa',compact('perPage','notifikasiList','countAllNotifikasi'));
    }

    public function show(NotifikasiMahasiswa $notifikasiMahasiswa){
        $notifikasiMahasiswa->update([
            'status'=>'dilihat'
        ]);
        return redirect($notifikasiMahasiswa->link_notifikasi);
    }

    public function allRead(){
        NotifikasiMahasiswa::where('nim',Session::get('nim'))->update(['status'=>'dilihat']);
        $this->setFlashData('success','Berhasil','Semua notifikasi telah ditandai dilihat');
        return redirect($this->segmentUser.'/notifikasi');
    }

    public function allDelete(){
        NotifikasiMahasiswa::where('nim',Session::get('nim'))->delete();
        $this->setFlashData('success','Berhasil','Semua notifikasi telah dihapus');
        return redirect($this->segmentUser.'/notifikasi');
    }
}
