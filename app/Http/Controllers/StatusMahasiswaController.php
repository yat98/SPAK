<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use App\TahunAkademik;
use App\StatusMahasiswa;
use Illuminate\Http\Request;
use App\Imports\StatusMahasiswaImport;
use App\Http\Requests\StatusMahasiswaRequest;
use Maatwebsite\Excel\Validators\ValidationException;

class StatusMahasiswaController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $tahunAkademik = $this->generateAllTahunAkademik();
        $statusMahasiswaList = StatusMahasiswa::join('tahun_akademik','tahun_akademik.id','=','status_mahasiswa.id_tahun_akademik')
                                    ->join('mahasiswa','mahasiswa.nim','=','status_mahasiswa.nim')
                                    ->paginate($perPage);
        $countMahasiswa = Mahasiswa::all()->count();
        $countStatusMahasiswa = $statusMahasiswaList->count();
        $countAllStatusMahasiswa = StatusMahasiswa::all()->count();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        return view('user.'.$this->segmentUser.'.status_mahasiswa',compact('statusMahasiswaList','countStatusMahasiswa','countAllStatusMahasiswa','tahunAkademik','perPage','mahasiswa','countMahasiswa','tahunAkademikAktif'));
    }

    public function create()
    {
        if(!$this->isMahasiswaExists() || !$this->isTahunAkademikAktifExists()){
            return redirect($this->segmentUser.'/status-mahasiswa');
        }
        $nimReadOnly = false;
        $mahasiswaList = $this->generateMahasiswa();
        $tahun = $this->generateTahunAkademikAktif();
        return view('user.'.$this->segmentUser.'.tambah_status_mahasiswa',compact('mahasiswaList','tahun','nimReadOnly'));
    }

    public function store(StatusMahasiswaRequest $request)
    {
        $input = $request->all();
        StatusMahasiswa::create($input);
        $this->setFlashData('success','Berhasil','Data status mahasiswa berhasil ditambahkan');
        return redirect($this->segmentUser.'/status-mahasiswa');
    }

    public function edit($id,$nim)
    {
        $nimReadOnly = true;
        $mahasiswa = StatusMahasiswa::where('nim',$nim)->where('id_tahun_akademik',$id)->first();
        $mahasiswaList = $this->generateMahasiswa();
        $tahun = $this->generateAllTahunAkademik();
        return view('user.'.$this->segmentUser.'.edit_status_mahasiswa',compact('mahasiswaList','tahun','mahasiswa','nimReadOnly'));
    }

    public function update(StatusMahasiswaRequest $request)
    {
        $statusMahasiswa = StatusMahasiswa::where('nim',$request->nim)->where('id_tahun_akademik',$request->id_tahun_akademik);
        $statusMahasiswa->update([
            'status'=>$request->status,
            'id_tahun_akademik'=>$request->id_tahun_akademik,
            'nim'=>$request->nim
        ]);
        $this->setFlashData('success','Berhasil','Data status mahasiswa berhasil diubah');
        return redirect($this->segmentUser.'/status-mahasiswa');
    }

    public function destroy(Request $request)
    {
        $statusMahasiswa = StatusMahasiswa::where('nim',$request->nim)->where('id_tahun_akademik',$request->id_tahun_akademik);
        $statusMahasiswa->delete();
        $this->setFlashData('success','Berhasil','Data status mahasiswa berhasil dihapus');
        return redirect($this->segmentUser.'/status-mahasiswa');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['tahun_akademik']) || isset($keyword['status'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $tahunAkademik = $this->generateAllTahunAkademik();

            $countMahasiswa = Mahasiswa::all()->count();
            $countAllStatusMahasiswa = StatusMahasiswa::all()->count();
            $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();

            $nama = isset($keyword['keywords']) ? $keyword['keywords']:'';
            $statusMahasiswaList = StatusMahasiswa::where('status_mahasiswa.nim','like',"%$nama%")
            ->join('tahun_akademik','tahun_akademik.id','=','status_mahasiswa.id_tahun_akademik')
                                    ->join('mahasiswa','mahasiswa.nim','=','status_mahasiswa.nim');
            (isset($keyword['tahun_akademik'])) ? $statusMahasiswaList = $statusMahasiswaList->where('id_tahun_akademik',$keyword['tahun_akademik']) : '';
            (isset($keyword['status'])) ? $statusMahasiswaList = $statusMahasiswaList->where('status',$keyword['status']) : '';
            $statusMahasiswaList = $statusMahasiswaList->paginate($perPage)->appends($request->except('page'));
            $countStatusMahasiswa = $statusMahasiswaList->count();
            if($countStatusMahasiswa < 1){
                $this->setFlashData('search','Hasil Pencarian','Data status mahasiswa tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.status_mahasiswa',compact('statusMahasiswaList','countStatusMahasiswa','countAllStatusMahasiswa','tahunAkademik','perPage','mahasiswa','countMahasiswa','tahunAkademikAktif'));
        }else{
            return redirect($this->segmentUser.'/status-mahasiswa');
        }
        dd($keyword);
    }

    public function createImport()
    {
        if(!$this->isMahasiswaExists() || !$this->isTahunAkademikAktifExists()){
            return redirect($this->segmentUser.'/status-mahasiswa');
        }
        $tahun = $this->generateTahunAkademikAktif();
        return view('user.'.$this->segmentUser.'.import_status_mahasiswa',compact('tahun'));
    }

    public function storeImport(Request $request)
    {
        $this->validate($request,[
            'data_status_mahasiswa'=>'required|mimes:csv,xls,xlsx'
        ]);
        $import = new StatusMahasiswaImport();
        try {
            $import->import($request->data_status_mahasiswa);
            $this->setFlashData('success','Berhasil','Import data status mahasiswa berhasil');
            return redirect($this->segmentUser.'/status-mahasiswa');
        } catch (ValidationException $e) {
             $tahun = $this->generateTahunAkademikAktif();
             $failures = $e->failures();
             return view('user.'.$this->segmentUser.'.import_status_mahasiswa',compact('failures','tahun'));
        }
    }

    private function isMahasiswaExists(){
        $countMahasiswa = Mahasiswa::all()->count();
        if($countMahasiswa < 1){
            $this->setFlashData('info','Data Mahasiswa Kosong','Tambahkan data mahasiswa terlebih dahulu sebelum menambahkan data status mahasiswa!');
            return false;
        }
        return true;
    }

    private function isTahunAkademikAktifExists(){
        $countTahunAkademik = TahunAkademik::where('status_aktif','aktif')->get()->count();
        if($countTahunAkademik < 1){
            $this->setFlashData('info','Data Tahun Akademik Belum Aktif','Aktifkan tahun akademik terlebih dahulu sebelum menambahkan data status mahasiswa!');
            return false;
        }
        return true;
    }
}
