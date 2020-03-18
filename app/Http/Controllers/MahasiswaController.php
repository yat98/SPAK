<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswaList = Mahasiswa::all();
        $countMahasiswa = $mahasiswaList->count();
        return view('user.'.$this->segmentUser.'.mahasiswa',compact('mahasiswaList','countMahasiswa'));
    }

    public function create()
    {
        $angkatan = $this->generateAngkatan();
        return view('user.'.$this->segmentUser.'.tambah_mahasiswa',compact('angkatan'));
    }

    public function store(JurusanRequest $request)
    {
        $input = $request->all();
        Jurusan::create($input);
        return redirect($this->segmentUser.'/jurusan');
    }

    public function edit(Jurusan $jurusan)
    {   
        return view('user.'.$this->segmentUser.'.edit_jurusan',compact('jurusan'));
    }

    public function update(JurusanRequest $request, Jurusan $jurusan)
    {
        $jurusan->update($request->all());
        return redirect($this->segmentUser.'/jurusan');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect($this->segmentUser.'/mahasiswa');
    }

    private function generateAngkatan(){
        $tahun = [];
        for($i = 2000;$i <= 2099;$i++){
            $tahun[$i] = $i;
        }
        return $tahun;
    }
}
