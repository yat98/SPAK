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
use Illuminate\Http\Request;
use App\SuratKeteranganLulus;
use App\SuratPermohonanSurvei;
use App\SuratKegiatanMahasiswa;
use App\SuratPengantarBeasiswa;
use App\SuratPersetujuanPindah;
use App\Imports\MahasiswaImport;
use App\PengajuanSuratKeterangan;
use App\DaftarDispensasiMahasiswa;
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
        $angkatan = $this->generateAngkatan();
        $prodiList = $this->generateProdi();
        $jurusanList = Jurusan::pluck('nama_jurusan','id')->toArray();
        $mahasiswaList = Mahasiswa::orderBy('nim')->with('prodi.jurusan')->paginate($perPage);
        $countAllMahasiswa = Mahasiswa::count();
        $countMahasiswa = count($mahasiswaList);
        return view('user.'.$this->segmentUser.'.mahasiswa',compact('perPage','mahasiswaList','countMahasiswa','prodiList','angkatan','jurusanList','countAllMahasiswa'));
    }

    public function showPimpinan(Mahasiswa $mahasiswa){
        $perPage = $this->perPage;
        $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan aktif kuliah')
                                        ->where('nim',$mahasiswa->nim)
                                        ->paginate($perPage);

        $suratKeteranganKelakuanList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan kelakuan baik')
                                        ->where('nim',$mahasiswa->nim)
                                        ->paginate($perPage);

        $suratDispensasiList = SuratDispensasi::join('daftar_dispensasi_mahasiswa','surat_dispensasi.id_surat_masuk','=','daftar_dispensasi_mahasiswa.id_surat_dispensasi')
                                        ->select('*','surat_dispensasi.created_at','surat_dispensasi.updated_at')                                
                                        ->orderByDesc('surat_dispensasi.created_at')
                                        ->where('status','selesai')
                                        ->where('daftar_dispensasi_mahasiswa.nim',$mahasiswa->nim)
                                        ->paginate($perPage);
                                        // dd($suratDispensasiList->first());

        $suratRekomendasiList = SuratRekomendasi::join('daftar_rekomendasi_mahasiswa','surat_rekomendasi.id','=','daftar_rekomendasi_mahasiswa.id_surat_rekomendasi')
                                        ->orderByDesc('surat_rekomendasi.created_at')
                                        ->where('status','selesai')
                                        ->where('nim',$mahasiswa->nim)
                                        ->paginate($perPage);

        $suratTugasList = SuratTugas::join('daftar_tugas_mahasiswa','surat_tugas.id','=','daftar_tugas_mahasiswa.id_surat_tugas')
                                        ->select('*','surat_tugas.created_at','surat_tugas.updated_at')                                        
                                        ->orderByDesc('surat_tugas.created_at')
                                        ->where('nim',$mahasiswa->nim)
                                        ->where('status','selesai')
                                        ->paginate($perPage);

        $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                        ->orderByDesc('surat_persetujuan_pindah.created_at')
                                        ->orderByDesc('nomor_surat')
                                        ->where('nim',$mahasiswa->nim)
                                        ->paginate($perPage);

        $suratCutiList = SuratPengantarCuti::join('waktu_cuti','waktu_cuti.id','=','surat_pengantar_cuti.id_waktu_cuti')
                                        ->join('pendaftaran_cuti','pendaftaran_cuti.id_waktu_cuti','=','waktu_cuti.id')
                                        ->orderByDesc('nomor_surat')
                                        ->where('nim',$mahasiswa->nim)
                                        ->paginate($perPage);

        $suratBeasiswaList = SuratPengantarBeasiswa::join('daftar_beasiswa_mahasiswa','surat_pengantar_beasiswa.id','=','daftar_beasiswa_mahasiswa.id_surat_beasiswa')
                                        ->select('*','surat_pengantar_beasiswa.created_at','surat_pengantar_beasiswa.updated_at')                                                                        
                                        ->orderBy('status')
                                        ->where('status','selesai')
                                        ->where('nim',$mahasiswa->nim)
                                        ->paginate($perPage);

        $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->where('nim',$mahasiswa->nim)
                                ->paginate($perPage);

        $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                            ->whereIn('status',['selesai'])
                            ->where('nim',$mahasiswa->nim)
                            ->orderBy('status')
                            ->paginate($perPage);

        $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                            ->whereIn('status',['selesai'])
                            ->where('nim',$mahasiswa->nim)
                            ->orderBy('status')
                            ->paginate($perPage);     
        
        $suratSurveiList = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                            ->where('status','selesai')
                            ->where('nim',$mahasiswa->nim)
                            ->orderBy('status')
                            ->paginate($perPage);
                            
        $suratPenelitianList = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                            ->where('status','selesai')
                            ->where('nim',$mahasiswa->nim)
                            ->orderBy('status')
                            ->paginate($perPage);

        $suratDataAwalList =  SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->where('nim',$mahasiswa->nim)
                            ->orderBy('status')
                            ->paginate($perPage);
                            
        return view('user.pimpinan.detail_mahasiswa',compact('mahasiswa','suratKeteranganAktifList','suratKeteranganKelakuanList','suratDispensasiList','suratRekomendasiList','suratTugasList','suratPersetujuanPindahList','suratCutiList','suratBeasiswaList','suratKegiatanList','suratLulusList','suratMaterialList','suratSurveiList','suratPenelitianList','suratDataAwalList'));
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

    public function dashboard(){
        $tgl = Carbon::now();
        $pengajuanKegiatanList = null;
        $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $waktuCuti = isset($tahunAkademikAktif) ? WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first():null;

        $pengajuanSuratKeteranganAktifList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                               ->orderByDesc('created_at')
                                               ->orderBy('status')
                                               ->where('nim',Auth::user()->nim)
                                               ->get();
        $pengajuanSuratKeteranganList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                               ->where('nim',Auth::user()->nim)
                                               ->orderByDesc('created_at')
                                               ->orderBy('status')
                                               ->get();
        $pengajuanSuratPindahList = PengajuanSuratPersetujuanPindah::where('nim',Auth::user()->nim)
                                               ->orderByDesc('created_at')
                                               ->orderBy('status')
                                               ->get();
        $suratDispensasiList = [];
        $suratRekomendasiList = SuratRekomendasi::join('daftar_rekomendasi_mahasiswa','daftar_rekomendasi_mahasiswa.id_surat_rekomendasi','=','surat_rekomendasi.id')
                                               ->where('nim',Auth::user()->nim)
                                               ->orderByDesc('surat_rekomendasi.created_at')
                                               ->get();
        $suratTugasList = SuratTugas::select('*','surat_tugas.created_at','surat_tugas.updated_at')
                                               ->join('daftar_tugas_mahasiswa','daftar_tugas_mahasiswa.id_surat_tugas','=','surat_tugas.id')
                                               ->where('nim',Auth::user()->nim)
                                               ->orderByDesc('surat_tugas.created_at')
                                               ->get();

        $pengajuanSuratLulusList = PengajuanSuratKeteranganLulus::where('nim',Auth::user()->nim)->get();
        $pengajuanSuratMaterialList = PengajuanSuratPermohonanPengambilanMaterial::join('daftar_kelompok_pengambilan_material','daftar_kelompok_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                        ->where('daftar_kelompok_pengambilan_material.nim',Auth::user()->nim)
                                        ->get();
        $pengajuanSuratSurveiList = PengajuanSuratPermohonanSurvei::where('nim',Auth::user()->nim)->get();
        $pengajuanSuratPenelitianList = PengajuanSuratRekomendasiPenelitian::where('nim',Auth::user()->nim)->get();
        $pengajuanSuratDataAwalList = PengajuanSuratPermohonanPengambilanDataAwal::where('nim',Auth::user()->nim)->get();

        if(isset($mahasiswa->pimpinanOrmawa)){
            $pengajuanKegiatanList = PengajuanSuratKegiatanMahasiswa::join('mahasiswa','mahasiswa.nim','=','pengajuan_surat_kegiatan_mahasiswa.nim')
                                        ->join('pimpinan_ormawa','pimpinan_ormawa.nim','=','mahasiswa.nim')
                                        ->join('ormawa','pimpinan_ormawa.id_ormawa','=','ormawa.id')
                                        ->select('*','pengajuan_surat_kegiatan_mahasiswa.id AS id') 
                                        ->where('ormawa.nama',$mahasiswa->pimpinanOrmawa->ormawa->nama)
                                        ->orderByDesc('pengajuan_surat_kegiatan_mahasiswa.created_at')
                                        ->get();
        }
        $pendaftaranCutiList = PendaftaranCuti::where('nim',Auth::user()->nim)->get();

        $countAllPengajuan =    $pengajuanSuratKeteranganAktifList->count();
        $countAllPengajuanBaik =    $pengajuanSuratKeteranganList->count();
        $countAllPengajuanPindah =    $pengajuanSuratPindahList->count();
        $countAllDispensasi =    0;
        $countAllSuratRekomendasi =    $suratRekomendasiList->count();
        $countAllSuratTugas =    $suratTugasList->count();
        $countAllPengajuanKegiatan = ($pengajuanKegiatanList != null) ? $pengajuanKegiatanList->count() : 0;
        $countPendaftaranCuti = $pendaftaranCutiList->count();
        $countAllPengajuanLulus = $pengajuanSuratLulusList->count();
        $countAllPengajuanMaterial = $pengajuanSuratMaterialList->count();
        $countAllPengajuanSurvei = $pengajuanSuratSurveiList->count();
        $countAllPengajuanPenelitian = $pengajuanSuratPenelitianList->count();
        $countAllPengajuanDataAwal = $pengajuanSuratDataAwalList->count();

        $pengajuanSuratKeteranganAktifList = $pengajuanSuratKeteranganAktifList->take(5);
        $pengajuanSuratKeteranganList = $pengajuanSuratKeteranganList->take(5);
        $pengajuanSuratPindahList = $pengajuanSuratPindahList->take(5);
        $suratDispensasiList = [];
        $suratRekomendasiList = $suratRekomendasiList->take(5);
        $suratTugasList = $suratTugasList->take(5);
        $pengajuanKegiatanList =  ($pengajuanKegiatanList != null) ? $pengajuanKegiatanList->take(5) : null;
        $pendaftaranCutiList = $pendaftaranCutiList->take(5);
        $pengajuanSuratLulusList = $pengajuanSuratLulusList->take(5);
        $pengajuanSuratMaterialList = $pengajuanSuratMaterialList->take(5);
        $pengajuanSuratSurveiList = $pengajuanSuratSurveiList->take(5) ;
        $pengajuanSuratPenelitianList = $pengajuanSuratPenelitianList->take(5) ;
        $pengajuanSuratDataAwalList = $pengajuanSuratDataAwalList->take(5) ;
            
        return view($this->segmentUser.'.dashboard',compact('tahunAkademikAktif','tgl','waktuCuti','pengajuanSuratKeteranganAktifList','countAllPengajuan','pengajuanSuratKeteranganList','countAllPengajuanBaik','pengajuanSuratKeteranganList','pengajuanSuratPindahList','countAllPengajuanPindah','suratDispensasiList','countAllDispensasi','countAllSuratRekomendasi','suratRekomendasiList','suratTugasList','countAllSuratTugas','pengajuanKegiatanList','countAllPengajuanKegiatan','pendaftaranCutiList','countPendaftaranCuti','pengajuanSuratLulusList','countAllPengajuanLulus','pengajuanSuratMaterialList','countAllPengajuanMaterial','pengajuanSuratSurveiList','pengajuanSuratPenelitianList','pengajuanSuratDataAwalList','countAllPengajuanSurvei','countAllPengajuanPenelitian','countAllPengajuanDataAwal',
    ));
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
