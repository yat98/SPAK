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
}
