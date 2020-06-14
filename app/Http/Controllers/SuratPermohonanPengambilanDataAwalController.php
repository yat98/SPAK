<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use App\User;
use App\KodeSurat;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\SuratPermohonanPengambilanDataAwal;
use App\PengajuanSuratPermohonanPengambilanDataAwal;
use App\Http\Requests\SuratPermohonanPengambilanDataAwalRequest;

class SuratPermohonanPengambilanDataAwalController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratDataAwal(['selesai','menunggu tanda tangan']);
        $pengajuanSuratDataAwalList =  PengajuanSuratPermohonanPengambilanDataAwal::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                        ->orderByDesc('created_at')
                                        ->orderBy('status')
                                        ->paginate($perPage,['*'],'page_pengajuan');
        $suratDataAwalList =  SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->orderBy('status')
                            ->paginate($perPage,['*'],'page');
        $countAllPengajuanDataAwal = $pengajuanSuratDataAwalList->count();
        $countAllSuratDataAwal = $suratDataAwalList->count();
        $countPengajuanDataAwal = $pengajuanSuratDataAwalList->count();
        $countSuratDataAwal = $suratDataAwalList->count();
        return view('user.'.$this->segmentUser.'.surat_permohonan_pengambilan_data_awal',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratDataAwalList','suratDataAwalList','countAllPengajuanDataAwal','countAllSuratDataAwal','countPengajuanDataAwal','countSuratDataAwal'));
    }

    public function create(){
        if(!$this->isKodeSuratDataAwalExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.tambah_surat_permohonan_pengambilan_data_awal',compact('mahasiswa','nomorSuratBaru','userList','kodeSurat'));
    }

    public function store(SuratPermohonanPengambilanDataAwalRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        try{ 
            $input['status'] = 'menunggu tanda tangan';
            $pengajuan = PengajuanSuratPermohonanPengambilanDataAwal::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan pengambilan data awal gagal ditambahkan.');
        }

        try{ 
            $input['id_pengajuan'] = $pengajuan->id;
            SuratPermohonanPengambilanDataAwal::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuan->nim,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>'Surat permohonan pengambilan data awal telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-pengambilan-data-awal')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>'Tanda tangan surat permohonan pengambilan data awal.',
                'link_notifikasi'=>url('pimpinan/surat-permohonan-pengambilan-data-awal')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan pengambilan data awal gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan data awal berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function edit(SuratPermohonanPengambilanDataAwal $suratDataAwal)
    {
        $kodeSurat = $this->generateKodeSurat();
        $mahasiswa = $this->generateMahasiswa();
        $user = User::where('nip',$suratDataAwal->nip)->first();
        $userList = [
            $user->nip => strtoupper($user->jabatan).' - '.$user->nama
        ];
        $pengajuanSuratDataAwal = $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal;
        return view('user.pegawai.edit_surat_permohonan_pengambilan_data_awal',compact('suratDataAwal','mahasiswa','kodeSurat','userList','pengajuanSuratDataAwal'));
    }

    public function update(SuratPermohonanPengambilanDataAwalRequest $request, SuratPermohonanPengambilanDataAwal $suratDataAwal)
    {
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->update($input);
        $suratDataAwal->update($input);
        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan data awal mahasiswa dengan nama '.$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->nama.' berhasil diubah');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function show(SuratPermohonanPengambilanDataAwal $suratDataAwal)
    {
        $surat = collect($suratDataAwal->load(['pengajuanSuratPermohonanPengambilanDataAwal.mahasiswa.prodi.jurusan','kodeSurat','user']));
        $kodeSurat = explode('/',$suratDataAwal->kodeSurat->kode_surat);
        $surat->put('created_at',$suratDataAwal->created_at->isoFormat('D MMMM Y'));
        $surat->put('updated_at',$suratDataAwal->created_at->isoFormat('D MMMM Y'));
        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->file_rekomendasi_jurusan));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->file_rekomendasi_jurusan)[0]);
        $surat->put('nomor_surat','B/'.$suratDataAwal->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratDataAwal->created_at->year);
        return $surat->toJson();
    }

    public function destroy(SuratPermohonanPengambilanDataAwal $suratDataAwal)
    {
        $this->deleteImage('file_rekomendasi_jurusan',$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->file_rekomendasi_jurusan);
        $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->delete();
        $suratDataAwal->delete();
        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan data awal mahasiswa dengan nama '.$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function createSurat(PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSuratDataAwal){
        if(!$this->isKodeSuratDataAwalExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.tambah_pengajuan_surat_permohonan_pengambilan_data_awal',compact('pengajuanSuratDataAwal','nomorSuratBaru','userList','kodeSurat'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat',
            'nip'=>'required',
        ]);
        $pengajuanSuratDataAwal = PengajuanSuratPermohonanPengambilanDataAwal::findOrFail($request->id_pengajuan);
        $input = $request->all();
        DB::beginTransaction();
        try{
            $pengajuanSuratDataAwal->update([
                'status'=>'menunggu tanda tangan'
            ]);
            SuratPermohonanPengambilanDataAwal::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratDataAwal->nim,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>'Surat permohonan pengambilan data awal telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-pengambilan-data-awal')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>'Tanda tangan surat permohonan pengambilan data awal.',
                'link_notifikasi'=>url('pimpinan/surat-permohonan-pengambilan-data-awal')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan pengambilan data awal gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan data awal mahasiswa dengan nama '.$pengajuanSuratDataAwal->mahasiswa->nama.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratPermohonanPengambilanDataAwal $pengajuanSuratDataAwal){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSuratDataAwal->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratDataAwal->nim,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Data Awal',
                'isi_notifikasi'=>'Pengajuan surat permohonan pengambilan data awal di tolak.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-pengambilan-data-awal')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat permohonan pengambilan data awal gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan data awal mahasiswa dengan nama '.$pengajuanSuratDataAwal->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratDataAwal(['menunggu tanda tangan','selesai']);

            $pengajuanSuratDataAwalList =  PengajuanSuratPermohonanPengambilanDataAwal::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
          
            $suratDataAwalList = SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderBy('status');

            $countAllPengajuanDataAwal = $pengajuanSuratDataAwalList->count();
            $countAllSuratDataAwal = $suratDataAwalList->count();
            
            (isset($keyword['nomor_surat'])) ? $suratDataAwalList = $suratDataAwalList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratDataAwalList = $suratDataAwalList->where('nim',$keyword['keywords']):'';

            $suratDataAwalList = $suratDataAwalList->paginate($perPage)->appends($request->except('page'));

            $countPengajuanDataAwal = $pengajuanSuratDataAwalList->count();
            $countSuratDataAwal = $suratDataAwalList->count();
            if($countSuratDataAwal < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat permohonan pengambilan data awal tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_permohonan_pengambilan_data_awal',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratDataAwalList','suratDataAwalList','countAllPengajuanDataAwal','countAllSuratDataAwal','countPengajuanDataAwal','countSuratDataAwal'));
        }else{
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-data-awal');
        }
    }

    private function generateNomorSuratDataAwal($status){
        $suratDataAwalList =  SuratPermohonanPengambilanDataAwal::join('pengajuan_surat_permohonan_pengambilan_data_awal','pengajuan_surat_permohonan_pengambilan_data_awal.id','=','surat_permohonan_pengambilan_data_awal.id_pengajuan')
                            ->whereIn('status',$status)
                            ->get();
        $nomorSuratList = [];
        foreach ($suratDataAwalList as $suratDataAwal) {
            $kodeSurat = explode('/',$suratDataAwal->kodeSurat->kode_surat);
            $nomorSuratList[$suratDataAwal->nomor_surat] = 'B/'.$suratDataAwal->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratDataAwal->created_at->year;
        }
        return $nomorSuratList;
    }
    
    private function isKodeSuratDataAwalExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat permohonan pengambilan data awal')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinan = User::where('jabatan','wd1')->where('status_aktif','aktif')->first();
        $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        return $user;
    }

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat permohonan pengambilan data awal')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function uploadImage($imageFieldName, $request, $uploadPath){
        $image = $request->file($imageFieldName);
        $ext = $image->getClientOriginalExtension();
        if($image->isValid()){
            $imageName = $request->nim.'_'.date('YmdHis').'_'.$imageFieldName.".$ext";
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
}
