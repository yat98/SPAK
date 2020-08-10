<?php

namespace App\Http\Controllers;

use Session;
use DataTables;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiMahasiswaController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $countAllNotifikasi = NotifikasiMahasiswa::where('nim',Auth::user()->nim)->count();
        return view($this->segmentUser.'.notifikasi_mahasiswa',compact('perPage','countAllNotifikasi'));
    }

    public function getAllNotifikasi(){
        return DataTables::of(NotifikasiMahasiswa::where('nim',Auth::user()->nim))
                ->editColumn("status", function ($data) {
                    return ucwords($data->status);
                })
                ->addColumn('tanggal_notifikasi', function ($data) {
                    return $data->created_at->isoFormat('D MMMM Y H:m:ss');;
                })
                ->make(true);
    }

    public function show(NotifikasiMahasiswa $notifikasiMahasiswa){
        $notifikasiMahasiswa->update([
            'status'=>'dilihat'
        ]);
        return redirect($notifikasiMahasiswa->link_notifikasi);
    }

    public function allRead(){
        NotifikasiMahasiswa::where('nim',Auth::user()->nim)->update(['status'=>'dilihat']);
        $this->setFlashData('success','Berhasil','Semua notifikasi telah ditandai dilihat');
        return redirect($this->segmentUser.'/notifikasi');
    }

    public function allDelete(){
        NotifikasiMahasiswa::where('nim',Auth::user()->nim)->delete();
        $this->setFlashData('success','Berhasil','Semua notifikasi telah dihapus');
        return redirect($this->segmentUser.'/notifikasi');
    }
}
