<?php

namespace App\Http\Controllers;

use DataTables;
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
        $countAllMahasiswa = Mahasiswa::count();
        $countAllStatusMahasiswa = StatusMahasiswa::count();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        return view('user.'.$this->segmentUser.'.status_mahasiswa',compact('countAllStatusMahasiswa','tahunAkademikAktif','perPage','countAllMahasiswa'));
    }

    public function getAllStatusMahasiswa(){
        return DataTables::of(StatusMahasiswa::with(['mahasiswa','tahunAkademik'])
                                ->join('tahun_akademik', 'tahun_akademik.id', '=', 'status_mahasiswa.id_tahun_akademik')
                                ->join('mahasiswa', 'mahasiswa.nim', '=', 'status_mahasiswa.nim')
                                ->select(['*']))
                ->editColumn("status", function ($data) {
                    return ucwords($data->status);
                })
                ->editColumn("tahun_akademik.semester", function ($data) {
                    return ucwords($data->tahunAkademik->semester);
                })
                ->addColumn('aksi', function ($data) {
                    return $data->nim;
                })
                ->make(true);
    }

    public function getLimitStatusMahasiswa(){
        return DataTables::collection(StatusMahasiswa::join('tahun_akademik', 'tahun_akademik.id', '=', 'status_mahasiswa.id_tahun_akademik')
                                            ->join('mahasiswa', 'mahasiswa.nim', '=', 'status_mahasiswa.nim')
                                            ->get()
                                            ->take(5)
                                            ->sortByDesc('updated_at')
                                            ->load(['mahasiswa','tahunAkademik']))
                    ->editColumn("status", function ($data) {
                        return ucwords($data->status_aktif);
                    })
                    ->editColumn("tahun_akademik.semester", function ($data) {
                        return ucwords($data->tahunAkademik->semester);
                    })
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->diffForHumans();
                    })
                    ->editColumn("updated_at", function ($data) {
                        return $data->updated_at->diffForHumans();
                    })
                    ->toJson();
    }

    public function show(Request $request){
        $statusMahasiswa = StatusMahasiswa::where('nim',$request->nim)
                                ->where('id_tahun_akademik',$request->id_tahun_akademik)
                                ->with(['mahasiswa','tahunAkademik'])
                                ->first();
        $data = collect($statusMahasiswa);
        $data->put('created_at',$statusMahasiswa->created_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('updated_at',$statusMahasiswa->updated_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('status',ucwords($statusMahasiswa->status));
        
        return $data->toJson();
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
