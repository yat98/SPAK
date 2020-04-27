<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Jurusan;
use App\Mahasiswa;
use App\ProgramStudi;
use App\TahunAkademik;
use App\NotifikasiUser;
use App\StatusMahasiswa;
use App\SuratDispensasi;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use App\PengajuanSuratKeterangan;
use App\DaftarDispensasiMahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\MahasiswaRequest;
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

    public function show($nim){
        $mahasiswa = Mahasiswa::where('nim',$nim)->with(['prodi.jurusan','tahunAkademik'=>function($query){
            $query->orderByDesc('created_at');
        }])->get();
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

    public function dashboard(){
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $countAllPengajuan = 0;
        $countPengajuanSuratKeterangan = 0;
        return view($this->segmentUser.'.dashboard',compact('tahunAkademikAktif'));
    }

    public function pengajuanSuratKeteranganAktif(){
        $perPage = $this->perPage;
        $pengajuanSuratKeteranganAktifList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                            ->where('nim',Session::get('nim'))
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
        $countAllPengajuan = PengajuanSuratKeterangan::whereNotIn('status',['selesai'])->where('jenis_surat','surat keterangan aktif kuliah')->count();
        $countPengajuanSuratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                            ->where('nim',Session::get('nim'))
                                            ->count();
        return view($this->segmentUser.'.pengajuan_surat_keterangan_aktif_kuliah',compact('countAllPengajuan','countPengajuanSuratKeterangan','perPage','pengajuanSuratKeteranganAktifList'));
    }

    public function pengajuanSuratKeteranganKelakuanBaik(){
        $perPage = $this->perPage;
        $pengajuanSuratKeteranganList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                            ->where('nim',Session::get('nim'))
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
        $countAllPengajuan = PengajuanSuratKeterangan::whereNotIn('status',['selesai'])->where('jenis_surat','surat keterangan kelakuan baik')->count();
        $countPengajuanSuratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                            ->where('nim',Session::get('nim'))
                                            ->count();
        return view($this->segmentUser.'.pengajuan_surat_keterangan_kelakuan_baik',compact('countAllPengajuan','countPengajuanSuratKeterangan','perPage','pengajuanSuratKeteranganList'));
    }

    public function suratDispensasi(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratDispensasi();
        $suratDispensasiList = SuratDispensasi::join('daftar_dispensasi_mahasiswa','daftar_dispensasi_mahasiswa.id_surat_dispensasi','=','surat_dispensasi.id_surat_masuk')
                                    ->where('nim',Session::get('nim'))
                                    ->paginate($perPage);
        $countAllSuratDispensasi = $suratDispensasiList->count();
        $countSuratDispensasi = $suratDispensasiList->count();
        return view($this->segmentUser.'.surat_dispensasi',compact('perPage','mahasiswa','nomorSurat','countAllSuratDispensasi','countSuratDispensasi','suratDispensasiList'));
    }

    public function createPengajuanSuratKeteranganAktif(){
        $statusMahasiswa = Mahasiswa::where('nim',Session::get('nim'))->with(['tahunAkademik'=>function($query){
            $query->orderByDesc('created_at');
        }])->first();

        if($statusMahasiswa->tahunAkademik->count() > 0){
            $status = $statusMahasiswa->tahunAkademik->first()->pivot->status;
            if($status == 'lulus' || $status == 'drop out' || $status == 'keluar'){
                $this->setFlashData('info','Pengajuan Gagal','Maaf anda tidak dapat membuat pengajuan surat keterangan aktif kuliah karena status anda adalah '.$status);
                return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
            }
        }

        $tahunAkademik = [];
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $tahunAkademikTerakhir = ($tahunAkademikAktif != null) ? $tahunAkademikAktif:TahunAkademik::orderByDesc('created_at')->first();
        $status = StatusMahasiswa::where('nim',Session::get('nim'))->orderByDesc('created_at')->get();

        foreach ($status as $value) {
            if($value->status == 'aktif'){
                $tahunAkademik[$value->tahunAkademik->id] = $value->tahunAkademik->tahun_akademik.' - '.ucwords($value->tahunAkademik->semester);
                break;
            }
        }

        if(count($tahunAkademik) == 0){
            $this->setFlashData('info','Pengajuan Gagal','Maaf anda tidak dapat membuat pengajuan surat keterangan aktif kuliah karena anda tidak memiliki status aktif');
            return redirect('mahasiswa/pengajuan/surat-keterangan-aktif-kuliah');
        }

        foreach ($status as $value) {
            if ($value->id_tahun_akademik == $tahunAkademikTerakhir->id) {
                if ($value->status != 'aktif') {
                    $this->setFlashData('info-badge', 'Status Mahasiswa', 'Maaf anda tidak dapat melakukan pengajuan surat keterangan aktif kuliah pada tahun akademik '.$tahunAkademikTerakhir->tahun_akademik.' - '.ucwords($tahunAkademikTerakhir->semester).' dikarenakan status anda adalah '.$value->status.', tetapi anda dapat melakukan pengajuan surat keterangan aktif kuliah dengan tahun akademik '.current($tahunAkademik));
                }
            }
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_aktif_kuliah',compact('tahunAkademik'));
    }

    public function createPengajuanSuratKeteranganKelakuanBaik(){
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $tahunAkademikTerakhir = ($tahunAkademikAktif != null) ? $tahunAkademikAktif:TahunAkademik::orderByDesc('created_at')->first();
        if($tahunAkademikTerakhir != null){
            $tahunAkademik[$tahunAkademikTerakhir->id] = $tahunAkademikTerakhir->tahun_akademik.' - '.ucwords($tahunAkademikTerakhir->semester);
        }
        $tahunAkademik = [];
        return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_kelakuan_baik',compact('tahunAkademik'));
    }

    public function storePengajuanSuratKeteranganAktif(PengajuanSuratKeteranganRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();

        DB::beginTransaction();
        try{
            $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first();
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Pengajuan Surat Keterangan',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan aktif kuliah.',
                'link_notifikasi'=>url('pegawai/surat-keterangan-aktif-kuliah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan aktif kuliah gagal dibuat.');
        }

        try{ 
            PengajuanSuratKeterangan::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan aktif kuliah gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan aktif kuliah berhasil dibuat.');
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
    }

    public function storePengajuanSuratKeteranganKelakuanBaik(PengajuanSuratKeteranganRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();

        DB::beginTransaction();
        try{
            $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first();
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Pengajuan Surat Keterangan',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan kelakuan baik.',
                'link_notifikasi'=>url('pegawai/surat-keterangan-kelakuan-baik')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan kelakuan baik gagal dibuat.');
        }

        try{ 
            PengajuanSuratKeterangan::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan kelakuan baik gagal dibuat.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan kelakuan baik berhasil dibuat.');
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-kelakuan-baik');
    }

    public function progressPengajuanSuratKeterangan(PengajuanSuratKeterangan $pengajuanSuratKeterangan){
        $pengajuan = $pengajuanSuratKeterangan->load(['suratKeterangan.user','mahasiswa']);
        $data = collect($pengajuan);
        $tanggalDiajukan = $pengajuan->created_at->format('d F Y - H:i:m');
        $data->put('tanggal_diajukan',$tanggalDiajukan);

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuan->suratKeterangan->created_at->format('d F Y - H:i:m');
            $data->put('tanggal_selesai',$tanggalSelesai);
        }else if($pengajuan->status == 'ditolak'){
            $tanggalDitolak = $pengajuan->updated_at->format('d F Y - H:i:m');
            $data->put('tanggal_ditolak',$tanggalDitolak);
        }
        return $data->toJson();
    }

    public function progressPengajuanSuratDispensasi(SuratDispensasi $suratDispensasi){
        $surat = collect($suratDispensasi);
        $kodeSurat = explode('/',$suratDispensasi->kodeSurat->kode_surat);
        $surat->put('tanggal_diajukan',$suratDispensasi->created_at->format('d F Y - H:i:m'));
        if($suratDispensasi->status == 'selesai'){
            $tanggalSelesai = $suratDispensasi->updated_at->format('d F Y - H:i:m');
            $surat->put('tanggal_selesai',$tanggalSelesai);
        }
        return $surat->toJson();
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
