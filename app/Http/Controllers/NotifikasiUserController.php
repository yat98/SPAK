<?php

namespace App\Http\Controllers;

use App\NotifikasiUser;
use Illuminate\Http\Request;
use Session;

class NotifikasiUserController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $notifikasiList = NotifikasiUser::where('nip',Session::get('nip'))->orderByDesc('created_at')->paginate($perPage);
        $countAllNotifikasi = count($notifikasiList);
        return view('user.notifikasi_user',compact('perPage','notifikasiList','countAllNotifikasi'));
    }

    public function show(NotifikasiUser $notifikasiUser){
        $notifikasiUser->update([
            'status'=>'dilihat'
        ]);
        return redirect($notifikasiUser->link_notifikasi);
    }

    public function allRead(){
        NotifikasiUser::where('nip',Session::get('nip'))->update(['status'=>'dilihat']);
        $this->setFlashData('success','Berhasil','Semua notifikasi telah ditandai dilihat');
        return redirect($this->segmentUser.'/notifikasi');
    }

    public function allDelete(){
        NotifikasiUser::where('nip',Session::get('nip'))->delete();
        $this->setFlashData('success','Berhasil','Semua notifikasi telah dihapus');
        return redirect($this->segmentUser.'/notifikasi');
    }
}
