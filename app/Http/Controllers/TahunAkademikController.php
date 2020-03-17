<?php

namespace App\Http\Controllers;

use App\TahunAkademik;
use Illuminate\Http\Request;
use Validator;

class TahunAkademikController extends Controller
{
    // Pesan error status_aktif
    private $errorStatusAktif = 'Tahun akademik dengan status aktif sudah ada';

    public function index()
    {
        $tahunAkademikList = TahunAkademik::all()->sortBy('tahun_akademik')->sortBy('status_aktif');
        $countTahunAkademik = $tahunAkademikList->count();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        return view('user.admin.tahun_akademik',compact('tahunAkademikList','countTahunAkademik','tahunAkademikAktif'));
    }

    public function create()
    {
        $tahun = $this->generateTahunAkademik();
        return view('user.admin.tambah_tahun_akademik',compact('tahun'));
    }

    public function store(Request $request)
    {
        // Cek ada status tahun akadek yang aktif
        $adaStatusAktif = TahunAkademik::where('status_aktif','aktif')->exists();
        // Menimpa isi field tahun_akademik
        $request->merge([
            'tahun_akademik' => $request->tahun_akademik.' - '.$request->semester,
        ]);        
        // Mengambil semua inputan form
        $input = $request->all();
        // Melakukan validasi
        $validator = Validator::make($input,[
            'tahun_akademik'=>'required|string|unique:tahun_akademik,tahun_akademik',
            'status_aktif'=>'required|string|in:aktif,non aktif'
        ]);
        // Validasi status_aktif dan menambahkan pesan error
        $validator->after(function($validator) use($adaStatusAktif,$request) {
            if($adaStatusAktif && $request->status_aktif == 'aktif'){
                $validator->errors()->add('status_aktif',$this->errorStatusAktif);
            }
        });
        // Jika validasi gagal
        if($validator->fails()){
            // Repopulate tahun_akademik
            $input['tahun_akademik'] = trim(explode('-',$input['tahun_akademik'])[0]);
            return redirect('admin/tahun-akademik/create')
                    ->withInput($input)
                    ->withErrors($validator);
        }
        // Menambahkan ke db jika validasi berhasil
        TahunAkademik::create($request->all());
        return redirect('admin/tahun-akademik');
    }

    public function edit(TahunAkademik $tahunAkademik)
    {   
        $tahun = $this->generateTahunAkademik();
        $tahunAkademikSemester = explode('-',$tahunAkademik->tahun_akademik);
        $tahunAkademik->tahun_akademik = trim($tahunAkademikSemester[0]);
        $tahunAkademik->semester = trim(strtolower($tahunAkademikSemester[1]));
        return view('user.admin.edit_tahun_akademik',compact('tahunAkademik','tahun'));
    }

    public function update(Request $request, TahunAkademik $tahunAkademik)
    {  
        // Mengambil data tahun akademik aktif
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif');
        // Menimpa isi field tahun_akademik
        $request->merge([
            'tahun_akademik' => $request->tahun_akademik.' - '.$request->semester,
        ]);        
        // Mengambil semua inputan form
        $input = $request->all();
        // Melakukan validasi
        $validator = Validator::make($input,[
            'tahun_akademik'=>'required|string|unique:tahun_akademik,tahun_akademik,'.$request->id,
            'status_aktif'=>'required|string|in:aktif,non aktif'
        ]);
        // Validasi status_aktif dan menambahkan pesan error
        $validator->after(function($validator) use($tahunAkademikAktif,$request) {
            if($tahunAkademikAktif->exists() && $request->status_aktif == 'aktif'){
                if($request->id != $tahunAkademikAktif->first()->id){
                    $validator->errors()->add('status_aktif',$this->errorStatusAktif);
                }
            }
        });
        // Jika validasi gagal
        if($validator->fails()){
            // Repopulate tahun_akademik
            $input['tahun_akademik'] = trim(explode('-',$input['tahun_akademik'])[0]);
            return redirect('admin/tahun-akademik/'.$request->id.'/edit')
                    ->withInput($input)
                    ->withErrors($validator);
        }
        // Melakukan update jika tidak ada error
        $tahunAkademik->update($request->all());
        return redirect('admin/tahun-akademik');
    }

    public function destroy(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->delete();
        return redirect('admin/tahun-akademik');
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
