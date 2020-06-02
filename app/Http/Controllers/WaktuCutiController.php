<?php

namespace App\Http\Controllers;

use App\WaktuCuti;
use Illuminate\Http\Request;
use App\Http\Requests\WaktuCutiRequest;

class WaktuCutiController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $tahunAkademik = $this->generateAllTahunAkademik();
        $countAllWaktuCuti = WaktuCuti::all()->count();
        $waktuCutiList = WaktuCuti::paginate($perPage);
        $countWaktuCuti = $waktuCutiList->count();
        return view('user.'.$this->segmentUser.'.waktu_cuti',compact('tahunAkademik','perPage','waktuCutiList','countAllWaktuCuti','countWaktuCuti'));
    }

    public function create()
    {
        $tahun = $this->generateTahunAkademikAktif();
        return view('user.'.$this->segmentUser.'.tambah_waktu_cuti',compact('tahun'));
    }

    public function store(WaktuCutiRequest $request)
    {
        $input = $request->all();
        $input['tanggal_akhir_cuti'] = $request->tanggal_akhir_cuti+' 23:59:59';
        $waktuCuti = WaktuCuti::create($input);
        $this->setFlashData('success','Berhasil','Data waktu cuti tahun akademik '.$waktuCuti->tahunAkademik->tahun_akademik.' - '.$waktuCuti->tahunAkademik->semester.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/waktu-cuti');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords'])){
            $perPage = $this->perPage;
            $idTahunAkademik = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $waktuCutiList = WaktuCuti::where('id_tahun_akademik',$idTahunAkademik)->paginate($perPage);
            $countAllWaktuCuti = WaktuCuti::all()->count();
            $countWaktuCuti = $waktuCutiList->count();
            $tahunAkademik = $this->generateAllTahunAkademik();
            if($countWaktuCuti < 1){
                $this->setFlashData('search','Hasil Pencarian','Data waktu cuti tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.waktu_cuti',compact('tahunAkademik','perPage','waktuCutiList','countAllWaktuCuti','countWaktuCuti'));
        }else{
            return redirect($this->segmentUser.'/waktu-cuti');
        }
    }
    
    public function edit(WaktuCuti $waktuCuti)
    {
        $tahun = $this->generateAllTahunAkademik();
        return view('user.'.$this->segmentUser.'.edit_waktu_cuti',compact('tahun','waktuCuti'));
    }

    public function update(WaktuCutiRequest $request, WaktuCuti $waktuCuti)
    {
        $input = $request->all();
        $input['tanggal_akhir_cuti'] = $request->tanggal_akhir_cuti+' 23:59:59';
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
