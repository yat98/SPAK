<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\KodeSurat;
use App\WaktuCuti;
use Carbon\Carbon;
use App\SuratMasuk;
use App\SuratTugas;
use App\TahunAkademik;
use App\PendaftaranCuti;
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
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\SuratRekomendasiPenelitian;
use Illuminate\Support\Facades\Hash;
use App\SuratPermohonanPengambilanDataAwal;
use App\SuratPermohonanPengambilanMaterial;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $perPage = $this->perPage;
        $userList = User::paginate($perPage);
        $countUser = $userList->count();
        $countAllUser = $countUser;        
        return view('user.'.$this->segmentUser.'.user',compact('countUser','userList','countAllUser','perPage'));
    }

    public function create()
    {
        $formPassword = true;
        return view('user.'.$this->segmentUser.'.tambah_user',compact('formPassword'));
    }

    public function search(Request $request){
        $perPage = $this->perPage;
        $keyword = $request->all();
        if(isset($keyword['keyword'])){
            $nama = $keyword['keyword'] != null ? $keyword['keyword'] : '';
            $userList = User::where('nama','like','%'.$nama.'%')
                            ->paginate($perPage)
                            ->appends($request
                            ->except('page'));
            $countUser = count($userList);
            $countAllUser = User::all()->count();
            if($countUser < 1){
                $this->setFlashData('search','Hasil Pencarian','Data user tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.user',compact('countUser','userList','countAllUser','perPage'));
        }else{
            return redirect($this->segmentUser.'/user');
        }
    }

    public function store(UserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $input['tanda_tangan'] = null;
        $this->setFlashData('success','Berhasil','Data user dengan nama '.strtolower($input['nama']).' berhasil ditambahkan');
        User::create($input);
        return redirect($this->segmentUser.'/user');
    }

    public function edit(User $user)
    {
        $formPassword = false;
        return view('user.'.$this->segmentUser.'.edit_user',compact('user','formPassword'));
    }

    public function update(UserRequest $request,User $user)
    {
        $input = $request->all();
        if(isset($input['password'])){
            $input['password'] = Hash::make($request->password);
        }else{
            $input['password'] = $user->password;
        }
        $input['tanda_tangan'] = null;
        $user->update($input);
        $this->setFlashData('success','Berhasil','Data user '.strtolower($user->nama).' berhasil diubah');
        return redirect($this->segmentUser.'/user');
    }

    public function destroy(User $user)
    {
        $user->delete();
        $this->setFlashData('success','Berhasil','Data user '.strtolower($user->nama).' berhasil dihapus');
        return redirect($this->segmentUser.'/user');
    }

    public function pegawaiDashboard(){
        $tgl = Carbon::now();
        if(Session::get('jabatan') == 'kasubag kemahasiswaan'){
            $kodeSuratList = KodeSurat::whereIn('jenis_surat',['surat keterangan','surat dispensasi','surat pengantar cuti','surat rekomendasi','surat persetujuan pindah','surat tugas','surat pengantar beasiswa','surat kegiatan mahasiswa'])->get();
            $suratMasukList = SuratMasuk::where('bagian','subbagian kemahasiswaan')->orderByDesc('created_at')->get();
        }else if(Session::get('jabatan') == 'kasubag pengajaran dan pendidikan'){
            $kodeSuratList = KodeSurat::whereNotIn('jenis_surat',['surat keterangan','surat dispensasi','surat pengantar cuti','surat rekomendasi','surat persetujuan pindah','surat tugas','surat pengantar beasiswa','surat kegiatan mahasiswa'])->get();
            $suratMasukList = SuratMasuk::where('bagian','subbagian pengajaran dan pendidikan')->orderByDesc('created_at')->get();
        }else{
            $kodeSuratList = KodeSurat::whereIn('jenis_surat',['surat keterangan bebas perpustakaan','surat keterangan bebas perlengkapan'])->get();
            $suratMasukList = SuratMasuk::where('bagian','subbagian umum & bmn')->orderByDesc('created_at')->get();
        }
        
        $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan aktif kuliah')
                                        ->get();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $suratKeteranganKelakuanList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan kelakuan baik')
                                        ->get();
        $suratDispensasiList = SuratDispensasi::orderBy('status')->get();
        $suratRekomendasiList = SuratRekomendasi::orderBy('status')->get();
        $suratTugasList = SuratTugas::orderBy('status')->get();
        $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                        ->orderByDesc('surat_persetujuan_pindah.created_at')
                                        ->orderByDesc('nomor_surat')
                                        ->get();
        $suratCutiList = SuratPengantarCuti::orderByDesc('nomor_surat')->get();
        $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->get();
        $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->get();
        $waktuCutiList = WaktuCuti::all();
        $pendaftaranCutiList = PendaftaranCuti::where('status','diterima')->orderByDesc('created_at')->get();
        $waktuCuti = isset($tahunAkademikAktif) ? WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first():null;

        $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->orderBy('status')
                            ->get();                         
        $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderBy('status')
                                ->get();
        $suratSurveiList =  SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderByDesc('surat_permohonan_survei.created_at')
                                ->get();
        $suratPenelitianList =  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderByDesc('surat_rekomendasi_penelitian.created_at')
                                ->get();
        $suratDataAwalList =  SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderBy('status')
                                ->get();

        $countAllKodeSurat = $kodeSuratList->count();
        $countAllSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $countAllSuratKeteranganKelakuan = $suratKeteranganKelakuanList->count();
        $countAllSuratMasuk  = $suratMasukList->count();
        $countAllSuratDispensasi = $suratDispensasiList->count();
        $countAllSuratRekomendasi = $suratRekomendasiList->count();
        $countAllSuratTugas = $suratTugasList->count();
        $countAllSuratPersetujuanPindah = $suratPersetujuanPindahList->count();
        $countAllSuratCuti = $suratCutiList->count();
        $countAllSuratBeasiswa = $suratBeasiswaList->count();
        $countAllSuratKegiatan = $suratKegiatanList->count();
        $countAllWaktuCuti = $waktuCutiList->count();
        $countAllPendaftaranCuti = $pendaftaranCutiList->count();
        $countAllsuratLulus = $suratLulusList->count();
        $countAllSuratMaterial = $suratMaterialList->count();
        $countAllSuratSurvei = $suratSurveiList->count();
        $countAllSuratPenelitian = $suratPenelitianList->count();
        $countAllSuratDataAwal = $suratDataAwalList->count();
      
        $kodeSuratList = $kodeSuratList->take(5);
        $suratMasukList = $suratMasukList->take(5);
        $suratKeteranganAktifList = $suratKeteranganAktifList->take(5);
        $suratKeteranganKelakuanList = $suratKeteranganKelakuanList->take(5);
        $suratDispensasiList = $suratDispensasiList->take(5);
        $suratRekomendasiList = $suratRekomendasiList->take(5);
        $suratTugasList = $suratTugasList->take(5);
        $suratPersetujuanPindahList = $suratPersetujuanPindahList->take(5);
        $suratCutiList = $suratCutiList->take(5);
        $suratBeasiswaList = $suratBeasiswaList->take(5);
        $suratKegiatanList = $suratKegiatanList->take(5);
        $waktuCutiList = $waktuCutiList->take(5);
        $pendaftaranCutiList = $pendaftaranCutiList->take(5);
        $suratLulusList = $suratLulusList->take(5);
        $suratMaterialList = $suratMaterialList->take(5);
        $suratSurveiList = $suratSurveiList->take(5);
        $suratPenelitianList = $suratPenelitianList->take(5);
        $suratDataAwalList = $suratDataAwalList->take(5);
       
        return view('user.'.$this->segmentUser.'.dashboard',compact('tahunAkademikAktif','countAllKodeSurat','kodeSuratList','countAllSuratKeteranganAktif','suratMasukList','countAllSuratMasuk','suratKeteranganAktifList','suratKeteranganKelakuanList','countAllSuratKeteranganKelakuan','suratDispensasiList','countAllSuratDispensasi','waktuCuti','tgl','suratRekomendasiList','countAllSuratRekomendasi','suratTugasList','countAllSuratTugas','suratPersetujuanPindahList','countAllSuratPersetujuanPindah','suratCutiList','countAllSuratCuti','suratBeasiswaList','countAllSuratBeasiswa','suratKegiatanList','countAllSuratKegiatan','waktuCutiList','countAllWaktuCuti','pendaftaranCutiList','countAllPendaftaranCuti','suratLulusList','countAllsuratLulus','suratMaterialList','countAllSuratMaterial','suratSurveiList','suratPenelitianList','suratDataAwalList','countAllSuratSurvei','countAllSuratPenelitian','countAllSuratDataAwal'));
    }

    public function pimpinanDashboard(){
        $bulan = [
            '1'=>'Januari',
            '2'=>'Januari',
            '3'=>'Februari',
            '4'=>'Maret',
            '5'=>'April',
            '6'=>'Juni',
            '7'=>'Juli',
            '8'=>'Agustus',
            '9'=>'September',
            '10'=>'Oktober',
            '11'=>'November',
            '12'=>'Desember',
        ];
        $tahun = $this->generateAngkatan();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan aktif kuliah')
                                        ->get();
        $suratKeteranganKelakuanList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan kelakuan baik')
                                        ->get();
        $suratDispensasiList = SuratDispensasi::orderByDesc('created_at')->where('status','selesai')->get();
        $suratRekomendasiList = SuratRekomendasi::orderByDesc('created_at')->where('status','selesai')->get();
        $suratTugasList = SuratTugas::orderByDesc('created_at')->where('status','selesai')->get();
        $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                        ->orderByDesc('surat_persetujuan_pindah.created_at')
                                        ->orderByDesc('nomor_surat')
                                        ->get();
        $suratCutiList = SuratPengantarCuti::orderByDesc('nomor_surat')->get();
        $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->where('status','selesai')->get();
        $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->get();
        $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                            ->whereIn('status',['selesai'])
                            ->orderBy('status')
                            ->get();
        $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                            ->whereIn('status',['selesai'])
                            ->orderBy('status')
                            ->get();     
        
        $suratSurveiList = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                            ->where('status','selesai')
                            ->orderBy('status')
                            ->get();
        $suratPenelitianList = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                            ->where('status','selesai')
                            ->orderBy('status')
                            ->get();
        $suratDataAwalList =  SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->orderBy('status')
                            ->get();

        $countAllSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $countAllSuratKeteranganKelakuan = $suratKeteranganKelakuanList->count();
        $countAllSuratDispensasi = $suratDispensasiList->count();
        $countAllSuratRekomendasi = $suratRekomendasiList->count();
        $countAllSuratTugas = $suratTugasList->count();
        $countAllSuratPersetujuanPindah = $suratPersetujuanPindahList->count();
        $countAllSuratCuti = $suratCutiList->count();
        $countAllSuratBeasiswa = $suratBeasiswaList->count();
        $countAllSuratKegiatan = $suratKegiatanList->count();
        $countAllsuratLulus = $suratLulusList->count();
        $countAllSuratMaterial=$suratMaterialList->count();
        $countAllSuratSurvei = $suratSurveiList->count();
        $countAllSuratPenelitian = $suratPenelitianList->count();
        $countAllSuratDataAwal = $suratDataAwalList->count();

        $suratKeteranganAktifList = $suratKeteranganAktifList->take(5);
        $suratKeteranganKelakuanList = $suratKeteranganKelakuanList->take(5);
        $suratDispensasiList = $suratDispensasiList->take(5);
        $suratRekomendasiList = $suratRekomendasiList->take(5);
        $suratTugasList = $suratTugasList->take(5);
        $suratPersetujuanPindahList = $suratPersetujuanPindahList->take(5);
        $suratCutiList = $suratCutiList->take(5);
        $suratBeasiswaList = $suratBeasiswaList->take(5);
        $suratKegiatanList = $suratKegiatanList->take(5);
        $suratLulusList = $suratLulusList->take(5);
        $suratMaterialList = $suratMaterialList->take(5);
        $suratSurveiList = $suratSurveiList->take(5);
        $suratPenelitianList = $suratPenelitianList->take(5);
        $suratDataAwalList = $suratDataAwalList->take(5);

        $bln = Carbon::now()->isoFormat('M');
        $thn = date('Y');

        $chartKemahasiswaan = [
            'Surat Keterangan Aktif Kuliah'=>SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                                ->where('jenis_surat','surat keterangan aktif kuliah')
                                                ->whereYear('surat_keterangan.created_at',$thn)
                                                ->whereMonth('surat_keterangan.created_at',$bln)
                                                ->count(),
            'Surat Keterangan Kelakuan Baik'=>SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                                ->where('jenis_surat','surat keterangan kelakuan baik')
                                                ->whereYear('surat_keterangan.created_at',$thn)
                                                ->whereMonth('surat_keterangan.created_at',$bln)
                                                ->count(),
            'Surat Dispensasi'=>SuratDispensasi::orderByDesc('created_at')
                                                ->where('status','selesai')
                                                ->whereYear('created_at',$thn)
                                                ->whereMonth('created_at',$bln)
                                                ->count(),
            'Surat Pengantar Cuti'=>SuratPengantarCuti::orderByDesc('nomor_surat')
                                        ->whereYear('created_at',$thn)
                                        ->whereMonth('created_at',$bln)
                                        ->count(),
            'Surat Rekomendasi'=>SuratRekomendasi::orderByDesc('created_at')
                                                ->where('status','selesai')
                                                ->whereYear('created_at',$thn)
                                                ->whereMonth('created_at',$bln)
                                                ->count(),
            'Surat Persetujuan Pindah'=>SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                                ->orderByDesc('surat_persetujuan_pindah.created_at')
                                                ->whereYear('surat_persetujuan_pindah.created_at',$thn)
                                                ->whereMonth('surat_persetujuan_pindah.created_at',$bln)
                                                ->orderByDesc('nomor_surat')
                                                ->count(),
            'Surat Tugas'=> SuratTugas::orderByDesc('created_at')
                                ->where('status','selesai')
                                ->whereYear('created_at',$thn)
                                ->whereMonth('created_at',$bln)
                                ->count(),
            'Surat Pengantar Beasiswa'=>SuratPengantarBeasiswa::orderBy('status')
                                            ->whereYear('created_at',$thn)
                                            ->whereMonth('created_at',$bln)
                                            ->where('status','selesai')
                                            ->count(),
            'Surat Kegiatan Mahasiswa'=> SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                            ->where('status','selesai')
                                            ->whereYear('surat_kegiatan_mahasiswa.created_at',$thn)
                                            ->whereMonth('surat_kegiatan_mahasiswa.created_at',$bln)
                                            ->count(),
        ];

        $chartPendidikanPengajaran = [
            'Surat Keterangan Lulus'=> SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                                            ->whereIn('status',['selesai'])
                                            ->whereYear('surat_keterangan_lulus.created_at',$thn)
                                            ->whereMonth('surat_keterangan_lulus.created_at',$bln)
                                            ->count(),
            'Surat Permohonan Pengambilan Material'=> SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                            ->whereIn('status',['selesai'])
                                            ->whereYear('surat_permohonan_pengambilan_material.created_at',$thn)
                                            ->whereMonth('surat_permohonan_pengambilan_material.created_at',$bln)
                                            ->count(),
            'Surat Permohonan Survei'=> SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                            ->where('status','selesai')
                                            ->whereYear('surat_permohonan_survei.created_at',$thn)
                                            ->whereMonth('surat_permohonan_survei.created_at',$bln)
                                            ->count(),
            'Surat Rekomendasi Penelitian'=>  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                                ->where('status','selesai')
                                                ->whereYear('surat_rekomendasi_penelitian.created_at',$thn)
                                                ->whereMonth('surat_rekomendasi_penelitian.created_at',$bln)
                                                ->count(),
            'Surat Permohonan Pengambilan Data Awal'=>SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                                                        ->whereIn('status',['selesai','menunggu tanda tangan'])
                                                        ->whereYear('surat_permohonan_pengambilan_data_awal.created_at',$thn)
                                                        ->whereMonth('surat_permohonan_pengambilan_data_awal.created_at',$bln)
                                                        ->count()
        ];
       
        return view('user.'.$this->segmentUser.'.dashboard',compact('tahunAkademikAktif','suratKeteranganAktifList','suratKeteranganKelakuanList','suratDispensasiList','suratRekomendasiList','suratTugasList','suratPersetujuanPindahList','suratCutiList','suratBeasiswaList','suratKegiatanList','countAllSuratKeteranganAktif','countAllSuratKeteranganKelakuan','countAllSuratDispensasi','countAllSuratRekomendasi','countAllSuratTugas','countAllSuratPersetujuanPindah','countAllSuratCuti','countAllSuratBeasiswa','countAllSuratKegiatan','suratLulusList','countAllsuratLulus','suratMaterialList','countAllSuratMaterial','suratSurveiList','suratPenelitianList','suratDataAwalList','countAllSuratSurvei','countAllSuratPenelitian','countAllSuratDataAwal','bulan','tahun','bln','thn','chartKemahasiswaan','chartPendidikanPengajaran'));
    }

    public function chartPimpinanDashboard(Request $request){
        $bulan = [
            '1'=>'Januari',
            '2'=>'Januari',
            '3'=>'Februari',
            '4'=>'Maret',
            '5'=>'April',
            '6'=>'Juni',
            '7'=>'Juli',
            '8'=>'Agustus',
            '9'=>'September',
            '10'=>'Oktober',
            '11'=>'November',
            '12'=>'Desember',
        ];
        $tahun = $this->generateAngkatan();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan aktif kuliah')
                                        ->get();
        $suratKeteranganKelakuanList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan kelakuan baik')
                                        ->get();
        $suratDispensasiList = SuratDispensasi::orderByDesc('created_at')->where('status','selesai')->get();
        $suratRekomendasiList = SuratRekomendasi::orderByDesc('created_at')->where('status','selesai')->get();
        $suratTugasList = SuratTugas::orderByDesc('created_at')->where('status','selesai')->get();
        $suratPersetujuanPindahList = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                        ->orderByDesc('surat_persetujuan_pindah.created_at')
                                        ->orderByDesc('nomor_surat')
                                        ->get();
        $suratCutiList = SuratPengantarCuti::orderByDesc('nomor_surat')->get();
        $suratBeasiswaList = SuratPengantarBeasiswa::orderBy('status')->where('status','selesai')->get();
        $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->get();
        $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                            ->whereIn('status',['selesai'])
                            ->orderBy('status')
                            ->get();
        $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                            ->whereIn('status',['selesai'])
                            ->orderBy('status')
                            ->get();     
        
        $suratSurveiList = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                            ->where('status','selesai')
                            ->orderBy('status')
                            ->get();
        $suratPenelitianList = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                            ->where('status','selesai')
                            ->orderBy('status')
                            ->get();
        $suratDataAwalList =  SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->orderBy('status')
                            ->get();

        $countAllSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $countAllSuratKeteranganKelakuan = $suratKeteranganKelakuanList->count();
        $countAllSuratDispensasi = $suratDispensasiList->count();
        $countAllSuratRekomendasi = $suratRekomendasiList->count();
        $countAllSuratTugas = $suratTugasList->count();
        $countAllSuratPersetujuanPindah = $suratPersetujuanPindahList->count();
        $countAllSuratCuti = $suratCutiList->count();
        $countAllSuratBeasiswa = $suratBeasiswaList->count();
        $countAllSuratKegiatan = $suratKegiatanList->count();
        $countAllsuratLulus = $suratLulusList->count();
        $countAllSuratMaterial=$suratMaterialList->count();
        $countAllSuratSurvei = $suratSurveiList->count();
        $countAllSuratPenelitian = $suratPenelitianList->count();
        $countAllSuratDataAwal = $suratDataAwalList->count();

        $suratKeteranganAktifList = $suratKeteranganAktifList->take(5);
        $suratKeteranganKelakuanList = $suratKeteranganKelakuanList->take(5);
        $suratDispensasiList = $suratDispensasiList->take(5);
        $suratRekomendasiList = $suratRekomendasiList->take(5);
        $suratTugasList = $suratTugasList->take(5);
        $suratPersetujuanPindahList = $suratPersetujuanPindahList->take(5);
        $suratCutiList = $suratCutiList->take(5);
        $suratBeasiswaList = $suratBeasiswaList->take(5);
        $suratKegiatanList = $suratKegiatanList->take(5);
        $suratLulusList = $suratLulusList->take(5);
        $suratMaterialList = $suratMaterialList->take(5);
        $suratSurveiList = $suratSurveiList->take(5);
        $suratPenelitianList = $suratPenelitianList->take(5);
        $suratDataAwalList = $suratDataAwalList->take(5);

        $bln = $request->bulan;
        $thn = $request->tahun;

        $chartKemahasiswaan = [
            'Surat Keterangan Aktif Kuliah'=>SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                                ->where('jenis_surat','surat keterangan aktif kuliah')
                                                ->whereYear('surat_keterangan.created_at',$thn)
                                                ->whereMonth('surat_keterangan.created_at',$bln)
                                                ->count(),
            'Surat Keterangan Kelakuan Baik'=>SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                                ->where('jenis_surat','surat keterangan kelakuan baik')
                                                ->whereYear('surat_keterangan.created_at',$thn)
                                                ->whereMonth('surat_keterangan.created_at',$bln)
                                                ->count(),
            'Surat Dispensasi'=>SuratDispensasi::orderByDesc('created_at')
                                                ->where('status','selesai')
                                                ->whereYear('created_at',$thn)
                                                ->whereMonth('created_at',$bln)
                                                ->count(),
            'Surat Pengantar Cuti'=>SuratPengantarCuti::orderByDesc('nomor_surat')
                                        ->whereYear('created_at',$thn)
                                        ->whereMonth('created_at',$bln)
                                        ->count(),
            'Surat Rekomendasi'=>SuratRekomendasi::orderByDesc('created_at')
                                                ->where('status','selesai')
                                                ->whereYear('created_at',$thn)
                                                ->whereMonth('created_at',$bln)
                                                ->count(),
            'Surat Persetujuan Pindah'=>SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','pengajuan_surat_persetujuan_pindah.id','=','surat_persetujuan_pindah.id_pengajuan_persetujuan_pindah')
                                                ->orderByDesc('surat_persetujuan_pindah.created_at')
                                                ->whereYear('surat_persetujuan_pindah.created_at',$thn)
                                                ->whereMonth('surat_persetujuan_pindah.created_at',$bln)
                                                ->orderByDesc('nomor_surat')
                                                ->count(),
            'Surat Tugas'=> SuratTugas::orderByDesc('created_at')
                                ->where('status','selesai')
                                ->whereYear('created_at',$thn)
                                ->whereMonth('created_at',$bln)
                                ->count(),
            'Surat Pengantar Beasiswa'=>SuratPengantarBeasiswa::orderBy('status')
                                            ->whereYear('created_at',$thn)
                                            ->whereMonth('created_at',$bln)
                                            ->where('status','selesai')
                                            ->count(),
            'Surat Kegiatan Mahasiswa'=> SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                            ->where('status','selesai')
                                            ->whereYear('surat_kegiatan_mahasiswa.created_at',$thn)
                                            ->whereMonth('surat_kegiatan_mahasiswa.created_at',$bln)
                                            ->count(),
        ];

        $chartPendidikanPengajaran = [
            'Surat Keterangan Lulus'=> SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                                            ->whereIn('status',['selesai'])
                                            ->whereYear('surat_keterangan_lulus.created_at',$thn)
                                            ->whereMonth('surat_keterangan_lulus.created_at',$bln)
                                            ->count(),
            'Surat Permohonan Pengambilan Material'=> SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                            ->whereIn('status',['selesai'])
                                            ->whereYear('surat_permohonan_pengambilan_material.created_at',$thn)
                                            ->whereMonth('surat_permohonan_pengambilan_material.created_at',$bln)
                                            ->count(),
            'Surat Permohonan Survei'=> SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                            ->where('status','selesai')
                                            ->whereYear('surat_permohonan_survei.created_at',$thn)
                                            ->whereMonth('surat_permohonan_survei.created_at',$bln)
                                            ->count(),
            'Surat Rekomendasi Penelitian'=>  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                                ->where('status','selesai')
                                                ->whereYear('surat_rekomendasi_penelitian.created_at',$thn)
                                                ->whereMonth('surat_rekomendasi_penelitian.created_at',$bln)
                                                ->count(),
            'Surat Permohonan Pengambilan Data Awal'=>SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                                                        ->whereIn('status',['selesai','menunggu tanda tangan'])
                                                        ->whereYear('surat_permohonan_pengambilan_data_awal.created_at',$thn)
                                                        ->whereMonth('surat_permohonan_pengambilan_data_awal.created_at',$bln)
                                                        ->count()
        ];
       
        return view('user.'.$this->segmentUser.'.dashboard',compact('tahunAkademikAktif','suratKeteranganAktifList','suratKeteranganKelakuanList','suratDispensasiList','suratRekomendasiList','suratTugasList','suratPersetujuanPindahList','suratCutiList','suratBeasiswaList','suratKegiatanList','countAllSuratKeteranganAktif','countAllSuratKeteranganKelakuan','countAllSuratDispensasi','countAllSuratRekomendasi','countAllSuratTugas','countAllSuratPersetujuanPindah','countAllSuratCuti','countAllSuratBeasiswa','countAllSuratKegiatan','suratLulusList','countAllsuratLulus','suratMaterialList','countAllSuratMaterial','suratSurveiList','suratPenelitianList','suratDataAwalList','countAllSuratSurvei','countAllSuratPenelitian','countAllSuratDataAwal','bulan','tahun','bln','thn','chartKemahasiswaan','chartPendidikanPengajaran'));
    }

    public function indexTandaTangan(){
        $nip = Session::get('nip');
        $user = User::where('nip',$nip)->first();
        return view('user.'.$this->segmentUser.'.tanda_tangan',compact('user'));
    }

    public function updateTandaTangan(Request $request){
        $nip = Session::get('nip');
        $user = User::where('nip',$nip)->first();
        $user->update([
            'tanda_tangan'=>$request->tanda_tangan
        ]);
        $this->setFlashData('success','Berhasil','Data tanda tangan berhasil ditambahkan');
        return redirect($this->segmentUser.'/tanda-tangan');
    }

    public function profil(){
        $nip = Session::get('nip');
        $user = User::where('nip',$nip)->get()->first();
        $status = $user->status_aktif;
        return view('user.'.$this->segmentUser.'.profil',compact('user','status'));
    }

    public function profilPassword(){
        return view('user.'.$this->segmentUser.'.profil_password');
    }
    
    public function updateProfil(Request $request,User $user){
        $this->validate($request,[
            'nip'=>'required|unique:user,nip,'.$request->nip.',nip',
            'nama'=>'required|string|alpha_spaces'
        ]);
        $user->update($request->all());
        Session::forget(['nip','username']);
        Session::put([
            'nip'=>$request->nip,
            'username'=>$request->nama,
        ]);
        $this->setFlashData('success','Berhasil','Profil berhasil diubah');
        return redirect($this->segmentUser);
    }

    public function updatePassword(Request $request){
        $nip = Session::get('nip');
        $user = User::where('nip',$nip)->first();
        $this->validate($request,[
            'password_lama'=>function($attr,$val,$fail) use($user){
                if (!Hash::check($val, $user->password)) {
                    $fail('password lama tidak sesuai.');
                }
            },
            'password'=>'required|string|max:60|confirmed',
            'password_confirmation'=>'required|string|max:60'
       ]);
       $user->update([
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
}
