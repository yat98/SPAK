<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Jurusan;
use App\Mahasiswa;
use App\WaktuCuti;
use Carbon\Carbon;
use App\SuratTugas;
use App\ProgramStudi;
use App\TahunAkademik;
use App\NotifikasiUser;
use App\PendaftaranCuti;
use App\StatusMahasiswa;
use App\SuratDispensasi;
use App\SuratRekomendasi;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use App\PengajuanSuratKeterangan;
use App\DaftarDispensasiMahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\MahasiswaRequest;
use App\PengajuanSuratKegiatanMahasiswa;
use App\PengajuanSuratPersetujuanPindah;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Http\Requests\PengajuanSuratKeteranganRequest;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
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

    public function show(Mahasiswa $mahasiswa){
        $mhs = collect($mahasiswa->load(['prodi.jurusan','tahunAkademik'=>function($query){
            $query->orderByDesc('created_at');
        }]));
        $mhs->put('tanggal_lahir',$mahasiswa->tanggal_lahir->isoFormat('D MMMM Y'));
        return $mhs->toJson();
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

    public function password(){
        return view($this->segmentUser.'.password');
    }
    
    public function updatePassword(Request $request){
        $nim = Session::get('nim');
        $mahasiswa = Mahasiswa::where('nim',$nim)->first();
        $this->validate($request,[
            'password_lama'=>function($attr,$val,$fail) use($mahasiswa){
                if (!Hash::check($val, $mahasiswa->password)) {
                    $fail('password lama tidak sesuai.');
                }
            },
            'password'=>'required|string|max:60|confirmed',
            'password_confirmation'=>'required|string|max:60'
       ]);
       $mahasiswa->update([
           'password'=>Hash::make($request->password)
       ]);
       Session::flush();
       $this->setFlashData('success','Berhasil','Password  berhasil diubah');
       return redirect($this->segmentUser);
    }

    public function dashboard(){
        $tgl = Carbon::now();
        $pengajuanKegiatanList = null;
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $waktuCuti = isset($tahunAkademikAktif) ? WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first():null;

        $pengajuanSuratKeteranganAktifList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                               ->orderByDesc('created_at')
                                               ->orderBy('status')
                                               ->where('nim',Session::get('nim'))
                                               ->get();
        $pengajuanSuratKeteranganList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                               ->where('nim',Session::get('nim'))
                                               ->orderByDesc('created_at')
                                               ->orderBy('status')
                                               ->get();
        $pengajuanSuratPindahList = PengajuanSuratPersetujuanPindah::where('nim',Session::get('nim'))
                                               ->orderByDesc('created_at')
                                               ->orderBy('status')
                                               ->get();
        $suratDispensasiList = SuratDispensasi::join('daftar_dispensasi_mahasiswa','daftar_dispensasi_mahasiswa.id_surat_dispensasi','=','surat_dispensasi.id_surat_masuk')
                                               ->where('nim',Session::get('nim'))
                                               ->orderByDesc('surat_dispensasi.created_at')
                                               ->get();
        $suratRekomendasiList = SuratRekomendasi::join('daftar_rekomendasi_mahasiswa','daftar_rekomendasi_mahasiswa.id_surat_rekomendasi','=','surat_rekomendasi.id')
                                               ->where('nim',Session::get('nim'))
                                               ->orderByDesc('surat_rekomendasi.created_at')
                                               ->get();
        $suratTugasList = SuratTugas::select('*','surat_tugas.created_at','surat_tugas.updated_at')
                                               ->join('daftar_tugas_mahasiswa','daftar_tugas_mahasiswa.id_surat_tugas','=','surat_tugas.id')
                                               ->where('nim',Session::get('nim'))
                                               ->orderByDesc('surat_tugas.created_at')
                                               ->get();
        if(isset($mahasiswa->pimpinanOrmawa)){
            $pengajuanKegiatanList = PengajuanSuratKegiatanMahasiswa::join('mahasiswa','mahasiswa.nim','=','pengajuan_surat_kegiatan_mahasiswa.nim')
                                        ->join('pimpinan_ormawa','pimpinan_ormawa.nim','=','mahasiswa.nim')
                                        ->join('ormawa','pimpinan_ormawa.id_ormawa','=','ormawa.id')
                                        ->select('*','pengajuan_surat_kegiatan_mahasiswa.id AS id') 
                                        ->where('ormawa.nama',$mahasiswa->pimpinanOrmawa->ormawa->nama)
                                        ->orderByDesc('pengajuan_surat_kegiatan_mahasiswa.created_at')
                                        ->get();
        }
        $pendaftaranCutiList = PendaftaranCuti::where('nim',Session::get('nim'))->get();

        $countAllPengajuan =    $pengajuanSuratKeteranganAktifList->count();
        $countAllPengajuanBaik =    $pengajuanSuratKeteranganList->count();
        $countAllPengajuanPindah =    $pengajuanSuratPindahList->count();
        $countAllDispensasi =    $suratDispensasiList->count();
        $countAllSuratRekomendasi =    $suratRekomendasiList->count();
        $countAllSuratTugas =    $suratTugasList->count();
        $countAllPengajuanKegiatan = ($pengajuanKegiatanList != null) ? $pengajuanKegiatanList->count() : 0;
        $countPendaftaranCuti = $pendaftaranCutiList->count();

        $pengajuanSuratKeteranganAktifList = $pengajuanSuratKeteranganAktifList->take(5);
        $pengajuanSuratKeteranganList = $pengajuanSuratKeteranganList->take(5);
        $pengajuanSuratPindahList = $pengajuanSuratPindahList->take(5);
        $suratDispensasiList = $suratDispensasiList->take(5);
        $suratRekomendasiList = $suratRekomendasiList->take(5);
        $suratTugasList = $suratTugasList->take(5);
        $pengajuanKegiatanList =  ($pengajuanKegiatanList != null) ? $pengajuanKegiatanList->take(5) : null;
        $pendaftaranCutiList = $pendaftaranCutiList->take(5);
            
        return view($this->segmentUser.'.dashboard',compact('tahunAkademikAktif','tgl','waktuCuti','pengajuanSuratKeteranganAktifList','countAllPengajuan','pengajuanSuratKeteranganList','countAllPengajuanBaik','pengajuanSuratKeteranganList','pengajuanSuratPindahList','countAllPengajuanPindah','suratDispensasiList','countAllDispensasi','countAllSuratRekomendasi','suratRekomendasiList','suratTugasList','countAllSuratTugas','pengajuanKegiatanList','countAllPengajuanKegiatan','pendaftaranCutiList','countPendaftaranCuti'));
    }
    
    public function logout(){
        Session::flush();
        return redirect('/');
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
