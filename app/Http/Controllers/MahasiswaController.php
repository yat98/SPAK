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
use App\Http\Requests\MahasiswaRequest;
use Maatwebsite\Excel\Validators\ValidationException;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function index()
    {
        $perPage = $this->perPage;
        $prodiList = $this->generateProdi();
        $jurusanList = Jurusan::pluck('nama_jurusan','id')->toArray();
        $angkatan = $this->generateAngkatan();
        $mahasiswaList = Mahasiswa::orderBy('nim')->with('prodi.jurusan')->paginate($perPage);
        $countAllMahasiswa = Mahasiswa::count();
        $countMahasiswa = count($mahasiswaList);
        $countProdi = ProgramStudi::all()->count();
        $countJurusan = Jurusan::all()->count();
        return view('user.'.$this->segmentUser.'.mahasiswa',compact('perPage','mahasiswaList','countMahasiswa','countProdi','countJurusan','prodiList','angkatan','jurusanList','countAllMahasiswa'));
    }

    public function create()
    {
        if(!$this->isProdiExists()){
            return redirect($this->segmentUser.'/mahasiswa');
        }
        $formPassword = true;
        $prodiList = $this->generateProdi();
        $angkatan = $this->generateAngkatan();
        return view('user.'.$this->segmentUser.'.tambah_mahasiswa',compact('prodiList','angkatan','formPassword'));
    }

    public function show($nim){
        $mahasiswa = Mahasiswa::where('nim',$nim)->with('prodi.jurusan')->get();
        return $mahasiswa->toJson();
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['angkatan']) || isset($keyword['jurusan']) || isset($keyword['prodi']) || isset($keyword['keyword'])){
            $countAllMahasiswa = Mahasiswa::count();
            $countProdi = ProgramStudi::all()->count();
            $countJurusan = Jurusan::all()->count();
            $perPage = $this->perPage;
            $prodiList = $this->generateProdi();
            $jurusanList = Jurusan::pluck('nama_jurusan','id')->toArray();
            $angkatan = $this->generateAngkatan();
            $nama = isset($keyword['keyword']) ? $keyword['keyword']:'';
            $mahasiswaList = Mahasiswa::where('nama','like','%'.$nama.'%')
                                ->join('prodi','prodi.id','=','mahasiswa.id_prodi')
                                ->join('jurusan','jurusan.id','=','prodi.id_jurusan');
            (isset($keyword['angkatan'])) ? $mahasiswaList = $mahasiswaList->where('angkatan',$keyword['angkatan']) : '';
            (isset($keyword['jurusan'])) ? $mahasiswaList = $mahasiswaList->where('id_jurusan',$keyword['jurusan']) : '';
            (isset($keyword['prodi'])) ? $mahasiswaList = $mahasiswaList->where('id_prodi',$keyword['prodi']) : '';
            $mahasiswaList = $mahasiswaList->paginate($perPage)->appends($request->except('page'));
            $countMahasiswa = count($mahasiswaList);
            if($countMahasiswa < 1){
                $this->setFlashData('search','Hasil Pencarian','Data mahasiswa tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.mahasiswa',compact('perPage','mahasiswaList','countMahasiswa','countProdi','countJurusan','prodiList','angkatan','jurusanList','countAllMahasiswa'));
        }else{
            return redirect($this->segmentUser.'/mahasiswa');
        }
    }

    public function store(MahasiswaRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $this->setFlashData('success','Berhasil','Data mahasiswa dengan nama '.strtolower($input['nama']).' berhasil ditambahkan');
        $mahasiswa = Mahasiswa::create($input);
        return redirect($this->segmentUser.'/mahasiswa');
    }

    public function edit(Mahasiswa $mahasiswa)
    {   
        $formPassword = false;
        $angkatan = $this->generateAngkatan();
        $prodiList = $this->generateProdi();
        return view('user.'.$this->segmentUser.'.edit_mahasiswa',compact('mahasiswa','angkatan','prodiList','formPassword'));
    }

    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $input = $request->all();
        if(isset($input['password'])){
            $input['password'] = Hash::make($request->password);
        }else{
            $input['password'] = $mahasiswa->password;
        }
        $mahasiswa->update($input);
        $this->setFlashData('success','Berhasil','Data mahasiswa '.strtolower($mahasiswa->nama).' berhasil diubah');
        return redirect($this->segmentUser.'/mahasiswa');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        $this->setFlashData('success','Berhasil','Data mahasiswa '.strtolower($mahasiswa->nama).' berhasil dihapus');
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
        $this->validate($request,[
            'data_mahasiswa'=>'required|mimes:csv,xls,xlsx'
        ]);
        $import = new MahasiswaImport();
        try {
            $import->import($request->data_mahasiswa);
            $this->setFlashData('success','Berhasil','Import data mahasiswa berhasil');
            return redirect($this->segmentUser.'/mahasiswa');
        } catch (ValidationException $e) {
             $failures = $e->failures();
             return view('user.'.$this->segmentUser.'.import_mahasiswa',compact('failures'));
        }
    }

    private function isProdiExists(){
        $countProdi = ProgramStudi::all()->count();
        if($countProdi < 1){
            $this->setFlashData('info','Data Program Studi Kosong','Tambahkan data program studi terlebih dahulu sebelum menambahkan data mahasiswa!');
            return false;
        }
        return true;
    }
}
