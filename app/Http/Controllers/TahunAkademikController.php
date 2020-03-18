<?php

namespace App\Http\Controllers;

use Validator;
use App\TahunAkademik;
use Illuminate\Http\Request;
use App\Http\Requests\TahunAkademikRequest;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $tahunAkademikList = TahunAkademik::all()->sortByDesc('tahun_akademik')
                                                 ->sortByDesc('created_at')
                                                 ->sortBy('status_aktif');
        $countTahunAkademik = $tahunAkademikList->count();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        return view('user.'.$this->segmentUser.'.tahun_akademik',compact('tahunAkademikList','countTahunAkademik','tahunAkademikAktif'));
    }

    public function create()
    {
        $tahun = $this->generateTahunAkademik();
        return view('user.'.$this->segmentUser.'.tambah_tahun_akademik',compact('tahun'));
    }

    public function store(TahunAkademikRequest $request)
    {
        $input = $request->all();
        TahunAkademik::create($request->all());
        return redirect($this->segmentUser.'/tahun-akademik');
    }

    public function edit(TahunAkademik $tahunAkademik)
    {   
        $tahun = $this->generateTahunAkademik();
        return view('user.'.$this->segmentUser.'.edit_tahun_akademik',compact('tahun','tahunAkademik'));
    }

    public function update(TahunAkademikRequest $request, TahunAkademik $tahunAkademik)
    {  
        $input = $request->all();
        $tahunAkademik->update($request->all());
        return redirect($this->segmentUser.'/tahun-akademik');
    }

    public function destroy(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->delete();
        return redirect($this->segmentUser.'/tahun-akademik');
    }

    private function generateTahunAkademik(){
        $tahun = [];
        for($i = 2019; $i < 2099;$i++){ 
            $tahunAkhir = $i;
            $tahun[$i.'/'.++$tahunAkhir]="$i/".$tahunAkhir;
        }
        return $tahun; 
    }
}
