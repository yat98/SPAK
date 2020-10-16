<?php

namespace App\Http\Controllers;

use DataTables;
use App\WaktuCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WaktuCutiRequest;

class WaktuCutiController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        
        $countAllWaktuCuti = WaktuCuti::count();

        return view('user.'.$this->segmentUser.'.waktu_cuti',compact('perPage','countAllWaktuCuti'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllWaktuCuti = WaktuCuti::count();

        return view($this->segmentUser.'.waktu_cuti',compact('countAllWaktuCuti','perPage'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllWaktuCuti = WaktuCuti::count();

        return view('user.'.$this->segmentUser.'.waktu_cuti',compact('countAllWaktuCuti','perPage'));
    }

    public function getAllWaktuCuti(){
        return DataTables::of(WaktuCuti::join('tahun_akademik','waktu_cuti.id_tahun_akademik','=','tahun_akademik.id')
                                         ->select(['waktu_cuti.*','tahun_akademik.tahun_akademik','tahun_akademik.semester'])
                                         ->with(['tahunAkademik']))
                    ->addColumn('aksi', function ($data) {
                        return $data->id;
                    })
                    ->editColumn("tanggal_awal_cuti", function ($data) {
                        return $data->tanggal_awal_cuti->isoFormat('D MMMM YYYY');
                    })
                    ->editColumn("tanggal_akhir_cuti", function ($data) {
                        return $data->tanggal_akhir_cuti->isoFormat('D MMMM YYYY');
                    })
                    ->addColumn('semester', function ($data) {
                        return ucwords($data->tahunAkademik->semester);
                    })
                    ->make(true);
    }

    public function create()
    {
        $tahun = $this->generateAllTahunAkademik();
        if (isset(Auth::user()->nip))return view('user.'.$this->segmentUser.'.tambah_waktu_cuti',compact('tahun'));
        return view($this->segmentUser.'.tambah_waktu_cuti',compact('tahun'));
    }

    public function store(WaktuCutiRequest $request)
    {
        $input = $request->all();
        $input['tanggal_akhir_cuti'] = $request->tanggal_akhir_cuti.' 23:59:59';
        $waktuCuti = WaktuCuti::create($input);
        $this->setFlashData('success','Berhasil','Data waktu cuti tahun akademik '.$waktuCuti->tahunAkademik->tahun_akademik.' - '.$waktuCuti->tahunAkademik->semester.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/waktu-cuti');
    }
    
    public function edit(WaktuCuti $waktuCuti)
    {
        $tahun = $this->generateAllTahunAkademik();
        if (isset(Auth::user()->nip)) return view('user.'.$this->segmentUser.'.edit_waktu_cuti',compact('tahun','waktuCuti'));
        return view($this->segmentUser.'.edit_waktu_cuti',compact('tahun','waktuCuti'));
    }

    public function update(WaktuCutiRequest $request, WaktuCuti $waktuCuti)
    {
        $input = $request->all();
        $input['tanggal_akhir_cuti'] = $request->tanggal_akhir_cuti.' 23:59:59';
        $waktuCuti->update($input);
        $this->setFlashData('success','Berhasil','Data waktu cuti tahun akademik '.$waktuCuti->tahunAkademik->tahun_akademik.' - '.$waktuCuti->tahunAkademik->semester.' berhasil diubah');
        return redirect($this->segmentUser.'/waktu-cuti');
    }

    public function destroy(WaktuCuti $waktuCuti)
    {
        $waktuCuti->delete();
        $this->setFlashData('success','Berhasil','Data waktu cuti tahun akademik '.$waktuCuti->tahunAkademik->tahun_akademik.' - '.$waktuCuti->tahunAkademik->semester.' berhasil dihapus');
        return redirect($this->segmentUser.'/waktu-cuti');
    }
}
