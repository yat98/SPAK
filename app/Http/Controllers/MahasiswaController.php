<?php

namespace App\Http\Controllers;

use Session;
use App\Jurusan;
use App\Mahasiswa;
use App\ProgramStudi;
use App\TahunAkademik;
use App\StatusMahasiswa;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\MahasiswaRequest;
use Maatwebsite\Excel\Validators\ValidationException;
use Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        $perPage = 20;
        $mahasiswaList = Mahasiswa::select('*')
                            ->join('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id')
                            ->join('jurusan', 'jurusan.id', '=', 'prodi.id_jurusan')
                            ->orderBy('nama_jurusan')
                            ->orderBy('id_prodi')
                            ->orderBy('angkatan')
                            ->paginate($perPage);
        $countMahasiswa = Mahasiswa::count();
        $countProdi = ProgramStudi::all()->count();
        $countJurusan = Jurusan::all()->count();
        return view('user.'.$this->segmentUser.'.mahasiswa',compact('perPage','mahasiswaList','countMahasiswa','countProdi','countJurusan'));
    }

    public function create()
    {
        if(!$this->isProdiExists()){
            return redirect($this->segmentUser.'/mahasiswa');
        }
        $prodiList = $this->generateProdi();
        $angkatan = $this->generateAngkatan();
        return view('user.'.$this->segmentUser.'.tambah_mahasiswa',compact('prodiList','angkatan'));
    }

    public function show($nim){
        $mahasiswa = Mahasiswa::select('*')
                                ->join('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id')
                                ->join('jurusan', 'jurusan.id', '=', 'prodi.id_jurusan')
                                ->where('nim',$nim)
                                ->get();
        return $mahasiswa->toJson();
    }

    public function store(MahasiswaRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        Session::flash('success-title','Berhasil');
        Session::flash('success','Data mahasiswa dengan nama '.strtolower($input['nama']).' berhasil ditambahkan');
        Mahasiswa::create($input);
        return redirect($this->segmentUser.'/mahasiswa');
    }

    public function edit(Mahasiswa $mahasiswa)
    {   
        $angkatan = $this->generateAngkatan();
        $prodiList = $this->generateProdi();
        return view('user.'.$this->segmentUser.'.edit_mahasiswa',compact('mahasiswa','angkatan','prodiList'));
    }

    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $jurusan->update($request->all());
        return redirect($this->segmentUser.'/mahasiswa');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect($this->segmentUser.'/mahasiswa');
    }

    public function createImport()
    {
        if(!$this->isProdiExists()){
            return redirect($this->segmentUser.'/mahasiswa');
        }
        return view('user.'.$this->segmentUser.'.import_mahasiswa');
    }

    public function storeImport(Request $request)
    {
        $begin = memory_get_usage();
        $this->validate($request,[
            'data_mahasiswa'=>'required|mimes:csv,xls,xlsx'
        ]);
        $import = new MahasiswaImport();
        try {
            $import->import($request->data_mahasiswa);
            Session::flash('success-title','Berhasil');
            Session::flash('success','Import Data mahasiswa berhasil');
            return redirect($this->segmentUser.'/mahasiswa');
        } catch (ValidationException $e) {
             $failures = $e->failures();
             return view('user.'.$this->segmentUser.'.import_mahasiswa',compact('failures'));
        }
    }

    private function generateAngkatan(){
        $tahun = [];
        for($i = 2000;$i <= 2099;$i++){
            $tahun[$i] = $i;
        }
        return $tahun;
    }

    private function generateProdi(){
        $prodi = ProgramStudi::all();
        $prodiList = [];
        foreach ($prodi as $value) {
            $prodiList[$value->id] = $value->strata.' - '.$value->nama_prodi;
        }
        return $prodiList;
    }

    private function isProdiExists(){
        $countProdi = ProgramStudi::all()->count();
        if($countProdi < 1){
            Session::flash('info-title','Data Program Studi Kosong');
            Session::flash('info','Tambahkan data program studi terlebih dahulu sebelum menambahkan data mahasiswa!');
            return false;
        }
        return true;
    }
}
