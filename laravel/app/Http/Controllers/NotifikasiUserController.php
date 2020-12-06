<?php

namespace App\Http\Controllers;

use Session;
use DataTables;
use App\NotifikasiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiUserController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $countAllNotifikasi = NotifikasiUser::where('nip',Auth::user()->nip)->count();
        return view('user.notifikasi_user',compact('perPage','countAllNotifikasi'));
    }

    public function getAllNotifikasi(){
        return DataTables::of(NotifikasiUser::where('nip',Auth::user()->nip))
                ->editColumn("status", function ($data) {
                    return ucwords($data->status);
                })
                ->addColumn('tanggal_notifikasi', function ($data) {
                    return $data->created_at->isoFormat('D MMMM Y H:m:ss');;
                })
                ->make(true);
    }

    public function show(NotifikasiUser $notifikasiUser){
        $notifikasiUser->update([
            'status'=>'dilihat'
        ]);
        return redirect($notifikasiUser->link_notifikasi);
    }

    public function allRead(){
        NotifikasiUser::where('nip',Auth::user()->nip)->update(['status'=>'dilihat']);
        $this->setFlashData('success','Berhasil','Semua notifikasi telah ditandai dilihat');
        return redirect($this->segmentUser.'/notifikasi');
    }

    public function allDelete(){
        NotifikasiUser::where('nip',Auth::user()->nip)->delete();
        $this->setFlashData('success','Berhasil','Semua notifikasi telah dihapus');
        return redirect($this->segmentUser.'/notifikasi');
    }
}
