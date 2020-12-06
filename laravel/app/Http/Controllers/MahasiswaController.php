<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use DataTables;
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
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\SuratPengantarCuti;
use App\PengajuanSuratTugas;
use Illuminate\Http\Request;
use App\SuratKeteranganLulus;
use App\SuratPermohonanSurvei;
use App\SuratKegiatanMahasiswa;
use App\SuratPengantarBeasiswa;
use App\SuratPersetujuanPindah;
use App\Imports\MahasiswaImport;
use App\PengajuanSuratDispensasi;
use App\PengajuanSuratKeterangan;
use App\DaftarDispensasiMahasiswa;
use App\PengajuanSuratRekomendasi;
use Illuminate\Support\Facades\DB;
use App\SuratRekomendasiPenelitian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\PengajuanSuratKeteranganLulus;
use App\Http\Requests\MahasiswaRequest;
use App\PengajuanSuratPermohonanSurvei;
use App\PengajuanSuratKegiatanMahasiswa;
use App\PengajuanSuratPersetujuanPindah;
use App\SuratPermohonanPengambilanDataAwal;
use App\SuratPermohonanPengambilanMaterial;
use App\PengajuanSuratRekomendasiPenelitian;
use App\PengajuanSuratKeteranganBebasPerlengkapan;
use App\PengajuanSuratKeteranganBebasPerpustakaan;
use App\PengajuanSuratPermohonanPengambilanDataAwal;
use App\PengajuanSuratPermohonanPengambilanMaterial;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Http\Requests\PengajuanSuratKeteranganRequest;

class MahasiswaController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $countAllMahasiswa = Mahasiswa::count();
        $countAllProdi = ProgramStudi::all()->count();
        $countAllJurusan = Jurusan::all()->count(); 
        return view('user.'.$this->segmentUser.'.mahasiswa',compact('perPage','countAllProdi','countAllJurusan','countAllMahasiswa'));
    }

    public function indexMahasiswa(){
        $perPageDashboard = $this->perPageDashboard;

        $tgl = Carbon::now();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $waktuCuti = isset($tahunAkademikAktif) ? WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first():null;
        $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
        $countAllSuratKegiatan = null;
        
        $countAllSuratAktif = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                                        ->where('nim',Auth::user()->nim)
                                                        ->count();

        $countAllSuratBaik = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                                       ->where('nim',Auth::user()->nim)
                                                       ->count();
        
        $countAllSuratDispensasi = PengajuanSuratDispensasi::join('daftar_dispensasi_mahasiswa','daftar_dispensasi_mahasiswa.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                                             ->where('daftar_dispensasi_mahasiswa.nim',Auth::user()->nim)
                                                             ->count();

        $countAllSuratRekomendasi = PengajuanSuratRekomendasi::join('daftar_rekomendasi_mahasiswa','daftar_rekomendasi_mahasiswa.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                                               ->where('daftar_rekomendasi_mahasiswa.nim',Auth::user()->nim)
                                                               ->count();

        $countAllSuratTugas = PengajuanSuratTugas::join('daftar_tugas_mahasiswa','daftar_tugas_mahasiswa.id_pengajuan','=','pengajuan_surat_tugas.id')
                                                   ->where('daftar_tugas_mahasiswa.nim',Auth::user()->nim)
                                                   ->count();
        
        $countAllSuratPindah = PengajuanSuratPersetujuanPindah::where('nim',Auth::user()->nim)
                                                                ->count();

        $countAllPendaftaran = PendaftaranCuti::where('nim',Auth::user()->nim)
                                                ->count();
        
        if($mahasiswa->pimpinanOrmawa != null){
            $countAllSuratKegiatan = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                                                      ->join('pimpinan_ormawa','pimpinan_ormawa.id_ormawa','=','ormawa.id')
                                                                      ->where('pimpinan_ormawa.nim',Auth::user()->nim)
                                                                      ->count();
        }

        $countAllSuratLulus = PengajuanSuratKeteranganLulus::where('nim',Auth::user()->nim)
                                                             ->count();

        $countAllSuratMaterial = PengajuanSuratPermohonanPengambilanMaterial::join('daftar_kelompok_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','daftar_kelompok_pengambilan_material.id_pengajuan')
                                                                              ->join('mahasiswa','mahasiswa.nim','=','daftar_kelompok_pengambilan_material.nim')
                                                                              ->where('daftar_kelompok_pengambilan_material.nim',Auth::user()->nim)
                                                                              ->count();

        $countAllSuratSurvei = PengajuanSuratPermohonanSurvei::where('nim',Auth::user()->nim)
                                                               ->count();

        $countAllSuratPenelitian = PengajuanSuratRekomendasiPenelitian::where('nim',Auth::user()->nim)
                                                                        ->count();

        $countAllSuratDataAwal = PengajuanSuratPermohonanPengambilanDataAwal::where('nim',Auth::user()->nim)
                                                                              ->count();

        $countAllSuratPerpustakaan = PengajuanSuratKeteranganBebasPerpustakaan::where('nim',Auth::user()->nim)
                                                                              ->count();

        $countAllSuratPerlengkapan = PengajuanSuratKeteranganBebasPerlengkapan::where('nim',Auth::user()->nim)
                                                                              ->count();

        return view($this->segmentUser.'.dashboard',compact('perPageDashboard','tgl','tahunAkademikAktif','waktuCuti','countAllSuratAktif','countAllSuratBaik','countAllSuratDispensasi','countAllSuratRekomendasi','countAllSuratTugas','countAllSuratPindah','countAllPendaftaran','countAllSuratKegiatan','countAllSuratLulus','countAllSuratMaterial','countAllSuratSurvei','countAllSuratPenelitian','countAllSuratDataAwal','countAllSuratPerpustakaan','countAllSuratPerlengkapan'));
    }

    public function getAllMahasiswa(){
        return DataTables::of(Mahasiswa::with(['prodi.jurusan'])->select(['*']))
                ->editColumn("status_aktif", function ($data) {
                    return ucwords($data->status_aktif);
                })
                ->addColumn('aksi', function ($data) {
                    return $data->nim;
                })
                ->make(true);
    }

    public function getLimitMahasiswa(){
        return DataTables::collection(Mahasiswa::all()->take(5)->sortByDesc('updated_at')->load(['prodi.jurusan']))
                    ->editColumn("status_aktif", function ($data) {
                        return ucwords($data->status_aktif);
                    })
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->diffForHumans();
                    })
                    ->editColumn("updated_at", function ($data) {
                        return $data->updated_at->diffForHumans();
                    })
                    ->toJson();
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;
        $countAllMahasiswa = Mahasiswa::count();
        return view('user.'.$this->segmentUser.'.mahasiswa',compact('perPage','countAllMahasiswa'));
    }

    public function showPimpinan(Mahasiswa $mahasiswa){
        $perPage = $this->perPage;
        $countAllSuratKegiatan = null;

        $countAllSuratAktif = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                ->where('nim',$mahasiswa->nim)
                                ->count();

        $countAllSuratBaik = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                ->where('nim',$mahasiswa->nim)
                                ->count();

        $countAllSuratDispensasi = PengajuanSuratDispensasi::join('daftar_dispensasi_mahasiswa','daftar_dispensasi_mahasiswa.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                        ->where('daftar_dispensasi_mahasiswa.nim',$mahasiswa->nim)
                                        ->count();

        $countAllSuratRekomendasi = PengajuanSuratRekomendasi::join('daftar_rekomendasi_mahasiswa','daftar_rekomendasi_mahasiswa.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                        ->where('daftar_rekomendasi_mahasiswa.nim',$mahasiswa->nim)
                                        ->count();

        $countAllSuratTugas = PengajuanSuratTugas::join('daftar_tugas_mahasiswa','daftar_tugas_mahasiswa.id_pengajuan','=','pengajuan_surat_tugas.id')
                                    ->where('daftar_tugas_mahasiswa.nim',$mahasiswa->nim)
                                    ->count();

        $countAllSuratPindah = PengajuanSuratPersetujuanPindah::where('nim',$mahasiswa->nim)
                                        ->count();

        $countAllSuratCuti = SuratPengantarCuti::join('waktu_cuti','waktu_cuti.id','=','surat_pengantar_cuti.id_waktu_cuti')
                                ->join('tahun_akademik','waktu_cuti.id_tahun_akademik','=','tahun_akademik.id')
                                ->join('pendaftaran_cuti','pendaftaran_cuti.id_waktu_cuti','=','surat_pengantar_cuti.id_waktu_cuti')
                                ->where('pendaftaran_cuti.nim',$mahasiswa->nim)
                                ->count();

        $countAllSuratBeasiswa = SuratPengantarBeasiswa::join('daftar_beasiswa_mahasiswa','daftar_beasiswa_mahasiswa.id_surat_beasiswa','=','surat_pengantar_beasiswa.id')
                                    ->where('daftar_beasiswa_mahasiswa.nim',$mahasiswa->nim)
                                    ->count();

        $countAllPendaftaran = PendaftaranCuti::where('nim',$mahasiswa->nim)
                                        ->count();

        if($mahasiswa->pimpinanOrmawa != null){
            $countAllSuratKegiatan = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                                ->join('pimpinan_ormawa','pimpinan_ormawa.id_ormawa','=','ormawa.id')
                                                ->where('pimpinan_ormawa.nim',$mahasiswa->nim)
                                                ->count();
        }

        $countAllSuratLulus = PengajuanSuratKeteranganLulus::where('nim',$mahasiswa->nim)
                                                             ->count();

        $countAllSuratMaterial = PengajuanSuratPermohonanPengambilanMaterial::join('daftar_kelompok_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','daftar_kelompok_pengambilan_material.id_pengajuan')
                                                                              ->join('mahasiswa','mahasiswa.nim','=','daftar_kelompok_pengambilan_material.nim')
                                                                              ->where('daftar_kelompok_pengambilan_material.nim',$mahasiswa->nim)
                                                                              ->count();

        $countAllSuratSurvei = PengajuanSuratPermohonanSurvei::where('nim',$mahasiswa->nim)
                                                               ->count();

        $countAllSuratPenelitian = PengajuanSuratRekomendasiPenelitian::where('nim',$mahasiswa->nim)
                                                                        ->count();

        $countAllSuratDataAwal = PengajuanSuratPermohonanPengambilanDataAwal::where('nim',$mahasiswa->nim)
                                                                              ->count();

        $countAllSuratPerlengkapan = PengajuanSuratKeteranganBebasPerlengkapan::where('nim',$mahasiswa->nim)
                                                                              ->count(); 

        $countAllSuratPerpustakaan = PengajuanSuratKeteranganBebasPerpustakaan::where('nim',$mahasiswa->nim)
                                                                              ->count();
        return view('user.pimpinan.detail_mahasiswa',compact('perPage','mahasiswa','countAllSuratAktif','countAllSuratBaik','countAllSuratDispensasi','countAllSuratRekomendasi','countAllSuratTugas','countAllSuratPindah','countAllPendaftaran','countAllSuratKegiatan','countAllSuratCuti','countAllSuratBeasiswa','countAllSuratLulus','countAllSuratMaterial','countAllSuratSurvei','countAllSuratPenelitian','countAllSuratDataAwal','countAllSuratPerlengkapan','countAllSuratPerpustakaan'));
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

    public function profil(){
        $nim = Auth::user()->nim;
        $mahasiswa = Mahasiswa::findOrFail($nim);
        return view($this->segmentUser.'.profil',compact('mahasiswa'));
    }

    public function updateProfil(Request $request,Mahasiswa $mahasiswa){
        $this->validate($request,[
            'tempat_lahir'=>'required|string',
            'tanggal_lahir'=>'required|date',
        ]);
        $mahasiswa->update($request->all());
        $this->setFlashData('success','Berhasil','Profil berhasil diubah');
        return redirect($this->segmentUser);
    }

    public function password(){
        return view($this->segmentUser.'.password');
    }
    
    public function updatePassword(Request $request){
        $nim = Auth::user()->nim;
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
    
    public function logout(){
        Session::flush();
        Session::regenerate(true);
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
