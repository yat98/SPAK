<?php

namespace App\Http\Controllers;

use DataTables;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiOperatorController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $countAllNotifikasi = NotifikasiOperator::where('id_operator',Auth::user()->id)->count();
        return view('operator.notifikasi_operator',compact('perPage','countAllNotifikasi'));
    }

    public function getAllNotifikasi(){
        return DataTables::of(NotifikasiOperator::where('id_operator',Auth::user()->id))
                ->editColumn("status", function ($data) {
                    return ucwords($data->status);
                })
                ->addColumn('tanggal_notifikasi', function ($data) {
                    return $data->created_at->isoFormat('D MMMM Y H:m:ss');;
                })
                ->make(true);
    }

    public function show(NotifikasiOperator $notifikasiOperator){
        $notifikasiOperator->update([
            'status'=>'dilihat'
        ]);
        return redirect($notifikasiOperator->link_notifikasi);
    }

    public function allRead(){
        NotifikasiOperator::where('id_operator',Auth::user()->id)->update(['status'=>'dilihat']);
        $this->setFlashData('success','Berhasil','Semua notifikasi telah ditandai dilihat');
        return redirect($this->segmentUser.'/notifikasi');
    }

    public function allDelete(){
        NotifikasiOperator::where('id_operator',Auth::user()->id)->delete();
        $this->setFlashData('success','Berhasil','Semua notifikasi telah dihapus');
        return redirect($this->segmentUser.'/notifikasi');
    }
}
