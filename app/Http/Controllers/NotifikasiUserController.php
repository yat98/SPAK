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
}
