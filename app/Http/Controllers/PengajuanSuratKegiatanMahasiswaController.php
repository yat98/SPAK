<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use App\KodeSurat;
use App\Mahasiswa;
use Carbon\Carbon;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\SuratKegiatanMahasiswa;
use Illuminate\Support\Facades\DB;
use App\DisposisiSuratKegiatanMahasiswa;
use App\PengajuanSuratKegiatanMahasiswa;
use App\Http\Requests\PengajuanSuratKegiatanMahasiswaRequest;

class PengajuanSuratKegiatanMahasiswaController extends Controller
{
    public function suratKegiatanMahasiswa(){
        $perPage = $this->perPage;
        $mahasiswa = Mahasiswa::where('nim',Session::get('nim'))->first();
        $pengajuanKegiatanList = PengajuanSuratKegiatanMahasiswa::join('mahasiswa','mahasiswa.nim','=','pengajuan_surat_kegiatan_mahasiswa.nim')
                                    ->join('pimpinan_ormawa','pimpinan_ormawa.nim','=','mahasiswa.nim')
                                    ->join('ormawa','pimpinan_ormawa.id_ormawa','=','ormawa.id')
                                    ->select('*','pengajuan_surat_kegiatan_mahasiswa.id AS id') 
                                    ->where('ormawa.nama',$mahasiswa->pimpinanOrmawa->ormawa->nama)
                                    ->orderByDesc('pengajuan_surat_kegiatan_mahasiswa.created_at')
                                    ->paginate($perPage);
        
        $countAllPengajuanKegiatan = $pengajuanKegiatanList->count();
        $countPengajuanSurat = $pengajuanKegiatanList->count();
        return view($this->segmentUser.'.pengajuan_surat_kegiatan_mahasiswa',compact('countAllPengajuanKegiatan','perPage','pengajuanKegiatanList','countPengajuanSurat'));
    }

    public function create(){
        return view($this->segmentUser.'.tambah_pengajuan_surat_kegiatan_mahasiswa');
    }

    public function show(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        return view('mahasiswa.detail_pengajuan_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan'));
    }

    public function edit(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        return view($this->segmentUser.'.edit_pengajuan_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan'));
    }

    public function update(PengajuanSuratKegiatanMahasiswaRequest $request, PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $input = $request->all();
        if ($request->has('file_surat_permohonan_kegiatan')) {
            $imageFieldName = 'file_surat_permohonan_kegiatan';
            $uploadPath = 'upload_surat_permohonan_kegiatan';
            $this->deleteImage($imageFieldName,$pengajuanKegiatan->file_surat_permohonan_kegiatan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName, $request, $uploadPath);
        }
        $pengajuanKegiatan->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa berhasil diubah');
        return redirect($this->segmentUser.'/pengajuan/surat-kegiatan-mahasiswa');
    }

    public function progress(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $pengajuan = collect($pengajuanKegiatan->load('disposisiUser'));
        $pengajuan->put('created_at',$pengajuanKegiatan->created_at->isoFormat('D MMMM Y H:m:s'));
        $pengajuan->put('updated_at',$pengajuanKegiatan->updated_at->isoFormat('D MMMM Y H:m:s'));
        foreach ($pengajuanKegiatan->disposisiUser as $disposisi) {
            if($disposisi->jabatan == 'dekan'){
                $pengajuan->put('tanggal_disposisi_dekan',$disposisi->pivot->created_at->isoFormat('D MMMM Y H:m:s'));
            }
            if($disposisi->jabatan == 'wd1'){
                $pengajuan->put('tanggal_disposisi_wakil_dekan_1',$disposisi->pivot->created_at->isoFormat('D MMMM Y H:m:s'));
            }
            if($disposisi->jabatan == 'wd2'){
                $pengajuan->put('tanggal_disposisi_wakil_dekan_2',$disposisi->pivot->created_at->isoFormat('D MMMM Y H:m:s'));
            }
            if($disposisi->jabatan == 'wd3'){
                $pengajuan->put('tanggal_disposisi_wakil_dekan_3',$disposisi->pivot->created_at->isoFormat('D MMMM Y H:m:s'));
            }
        }
        if($pengajuanKegiatan->status == 'menunggu tanda tangan'){
            $pengajuan->put('tanggal_tanda_tangan',$pengajuanKegiatan->suratKegiatanMahasiswa->created_at->isoFormat('D MMMM Y H:m:s'));
        }else if($pengajuanKegiatan->status == 'selesai'){
            $pengajuan->put('tanggal_tanda_tangan',$pengajuanKegiatan->suratKegiatanMahasiswa->created_at->isoFormat('D MMMM Y H:m:s'));
            $pengajuan->put('tanggal_selesai',$pengajuanKegiatan->updated_at->isoFormat('D MMMM Y H:m:s'));
        }
        if($pengajuanKegiatan->tanggal_diterima != null) $pengajuan->put('tanggal_diterima',$pengajuanKegiatan->tanggal_diterima->isoFormat('D MMMM Y H:m:s'));
        if($pengajuanKegiatan->tanggal_menunggu_tanda_tangan != null)  $pengajuan->put('tanggal_menunggu_tanda_tangan',$pengajuanKegiatan->tanggal_diterima->isoFormat('D MMMM Y H:m:s'));
        return json_encode($pengajuan->toArray(),JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    public function detailDisposisi(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $disposisi = collect($pengajuanKegiatan->disposisiUser);
        return $disposisi->toJson();
    }

    public function store(PengajuanSuratKegiatanMahasiswaRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::findOrFail(Session::get('nim'));
        $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first();
        $input['nim'] = $mahasiswa->nim;
        $input['nip'] = $user->nip;
        DB::beginTransaction();
        try {
            if ($request->has('file_surat_permohonan_kegiatan')) {
                $imageFieldName = 'file_surat_permohonan_kegiatan';
                $uploadPath = 'upload_surat_permohonan_kegiatan';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName, $request, $uploadPath);
            }
            PengajuanSuratKegiatanMahasiswa::create($input);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Pengajuan Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat kegiatan mahasiswa.',
                'link_notifikasi'=>url('pegawai/surat-kegiatan-mahasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan kegiatan mahasiswa gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa berhasil ditambahkan');
        return redirect($this->segmentUser.'/pengajuan/surat-kegiatan-mahasiswa');
    }

    public function terima(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $user = User::where('jabatan','dekan')->where('status_aktif','aktif')->first();
        DB::beginTransaction();
        try {
            $pengajuanKegiatan->update([
                'status'=>'diterima',
                'tanggal_diterima'=>Carbon::now()
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanKegiatan->nim,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Surat Kegiatan Mahasiswa telah diterima.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa')
            ]);
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Disposisi surat kegiatan mahasiswa.',
                'link_notifikasi'=>url('pimpinan/surat-kegiatan-mahasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan kegiatan mahasiswa gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa diterima');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }
    
    public function tolak(Request $request, PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $keterangan = $request->keterangan ?? '-';
        $pengajuanKegiatan->update([
            'status'=>'ditolak',
            'keterangan'=>$keterangan
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pengajuanKegiatan->nim,
            'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
            'isi_notifikasi'=>'Surat Kegiatan Mahasiswa telah ditolak.',
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa')
        ]);
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa ditolak');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function disposisi(Request $request){
        $pengajuanKegiatan = PengajuanSuratKegiatanMahasiswa::findOrFail($request->id);
        $link = url('pimpinan/surat-kegiatan-mahasiswa');
        $isiNotifikasi = 'Disposisi surat kegiatan mahasiswa.';
        DB::beginTransaction();
        try {
            DisposisiSuratKegiatanMahasiswa::create([
                'id_pengajuan'=>$pengajuanKegiatan->id,
                'nip'=>Session::get('nip'),
                'catatan'=>$request->catatan,
            ]);
            if(Session::get('jabatan') == 'dekan'){
                $user = User::where('jabatan','wd1')->where('status_aktif','aktif')->first(); 
                $pengajuanKegiatan->update([
                    'status'=>'disposisi dekan'
                ]);
            }else if(Session::get('jabatan') == 'wd1'){
                $user = User::where('jabatan','wd2')->where('status_aktif','aktif')->first(); 
                $pengajuanKegiatan->update([
                    'status'=>'disposisi wakil dekan I'
                ]);
            }else if(Session::get('jabatan') == 'wd2'){
                $user = User::where('jabatan','wd3')->where('status_aktif','aktif')->first(); 
                $pengajuanKegiatan->update([
                    'status'=>'disposisi wakil dekan II'
                ]);
            }else if(Session::get('jabatan') == 'wd3'){
                $isiNotifikasi = 'Surat kegiatan mahasiswa telah di disposisi.';
                $link = url('pegawai/surat-kegiatan-mahasiswa');
                $user = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first(); 
                $pengajuanKegiatan->update([
                    'status'=>'disposisi wakil dekan III'
                ]);
            }
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>$link
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanKegiatan->nim,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Surat Kegiatan Mahasiswa telah di disposisi.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan kegiatan mahasiswa gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat kegiatan mahasiswa didisposisi');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function createSurat(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        if(!$this->isKodeSuratKegiatanExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
        }
        $userList = $this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        return view('user.'.$this->segmentUser.'.tambah_pengajuan_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan','kodeSurat','userList','nomorSuratBaru')); 
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan_kegiatan'=>'required|numeric',
            'id_kode_surat'=>'required|numeric',
            'nip'=>'required|numeric',  
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat',
            'menimbang'=>'required|string',
            'mengingat'=>'required|string',
            'memperhatikan'=>'required|string',
            'menetapkan'=>'required|string',
            'kesatu'=>'required|string',
            'kedua'=>'required|string',
            'ketiga'=>'required|string',
            'keempat'=>'required|string',
        ]);
        $input = $request->all();
        DB::beginTransaction();
        try {
            $pengajuanKegiatan = PengajuanSuratKegiatanMahasiswa::where('id',$request->id_pengajuan_kegiatan)->first();
            SuratKegiatanMahasiswa::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanKegiatan->nim,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Surat Kegiatan Mahasiswa telah dibuat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa')
            ]);
            $pengajuanKegiatan->update([
                'status'=>'menunggu tanda tangan'
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Tanda tangan surat kegiatan mahasiswa',
                'link_notifikasi'=>url('pimpinan/surat-kegiatan-mahasiswa')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Surat','Surat kegiatan mahasiswa gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat kegiatan mahasiswa ditambahkan');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat kegiatan mahasiswa')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinan = User::where('jabatan','dekan')->where('status_aktif','aktif')->first();
        $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        return $user;
    }

    private function uploadImage($imageFieldName, $request, $uploadPath){
        $image = $request->file($imageFieldName);
        $ext = $image->getClientOriginalExtension();
        if($image->isValid()){
            $imageName = date('YmdHis').".$ext";
            $image->move($uploadPath,$imageName);            
            return $imageName;
        }
        return false;
    }

    private function deleteImage($imageFieldName,$imageName){
        $exist = Storage::disk($imageFieldName)->exists($imageName);
        if(isset($imageName) && $exist){
            $delete = Storage::disk($imageFieldName)->delete($imageName);
            if($delete) return true;
            return false;
        }
    }

    private function isKodeSuratKegiatanExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat kegiatan mahasiswa')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }
}
