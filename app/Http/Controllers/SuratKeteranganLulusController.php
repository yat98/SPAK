<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use App\KodeSurat;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\SuratKeteranganLulus;
use Illuminate\Support\Facades\DB;
use App\PengajuanSuratKeteranganLulus;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SuratKeteranganLulusRequest;

class SuratKeteranganLulusController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratLulus(['selesai','menunggu tanda tangan']);
        $pengajuanSuratLulusList =  PengajuanSuratKeteranganLulus::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                        ->orderByDesc('created_at')
                                        ->orderBy('status')
                                        ->paginate($perPage,['*'],'page_pengajuan');
        $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->orderBy('status')
                            ->paginate($perPage,['*'],'page');
        $countAllpengajuanSuratLulus = $pengajuanSuratLulusList->count();
        $countAllsuratLulus = $suratLulusList->count();
        $countPengajuanSuratLulus = $pengajuanSuratLulusList->count();
        $countSuratLulus = $suratLulusList->count();
        return view('user.'.$this->segmentUser.'.surat_keterangan_lulus',compact('perPage','mahasiswa','pengajuanSuratLulusList','suratLulusList','countAllpengajuanSuratLulus','countAllsuratLulus','countPengajuanSuratLulus','countSuratLulus','nomorSurat'));
    }

    public function suratLulusPimpinan(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratLulus(['selesai']);
        $pengajuanSuratLulusList= PengajuanSuratKeteranganLulus::where('status','menunggu tanda tangan')
                                    ->orderByDesc('created_at')
                                    ->orderBy('status')
                                    ->paginate($perPage,['*'],'page_pengajuan');
        $suratLulusList = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                                ->where('status','selesai')
                                ->orderBy('status')
                                ->paginate($perPage,['*'],'page');
        $countAllPengajuanSuratLulus = $pengajuanSuratLulusList->count();
        $countAllSuratLulus = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                                ->where('status','selesai')
                                ->orderBy('status')
                                ->count();
        $countSuratLulus = $suratLulusList->count();
        return view('user.'.$this->segmentUser.'.surat_keterangan_lulus',compact('countSuratLulus','perPage','mahasiswa','nomorSurat','pengajuanSuratLulusList','suratLulusList','countAllPengajuanSuratLulus','countAllSuratLulus'));
    }

    public function tandaTanganLulus(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-lulus');
        }
        $user  = User::where('status_aktif','aktif')->where('jabatan','kasubag pendidikan dan pengajaran')->first();
        $pengajuanSuratLulus = PengajuanSuratKeteranganLulus::where('id',$request->id)->first();
        $pengajuanSuratLulus->update([
            'status'=>'selesai',
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pengajuanSuratLulus->nim,
            'judul_notifikasi'=>'Surat Keterangan Lulus',
            'isi_notifikasi'=>'Surat keterangan lulus telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-lulus')
        ]);
        NotifikasiUser::create([
            'nip'=>$user->nip,
            'judul_notifikasi'=>'Surat Keterangan Lulus',
            'isi_notifikasi'=>'Surat keterangan lulus telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-keterangan-lulus')
        ]);
        $this->setFlashData('success','Berhasil','Surat keterangan lulus mahasiswa dengan nama '.$pengajuanSuratLulus->mahasiswa->nama.' berhasil ditanda tangani');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratKeteranganLulus $pengajuanSuratLulus){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSuratLulus->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratLulus->nim,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>'Pengajuan surat keterangan lulus di tolak.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-lulus')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat keterangan kelakuan baik gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan lulus mahasiswa dengan nama '.$pengajuanSuratLulus->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function create()
    {
        if(!$this->isKodeSuratLulusExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-keterangan-lulus');
        }
        $kodeSurat = $this->generateKodeSurat();
        $mahasiswa = $this->generateMahasiswa();
        $userList =$this->generatePimpinan();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        return view('user.pegawai.tambah_surat_keterangan_lulus',compact('nomorSuratBaru','mahasiswa','kodeSurat','userList'));
    }

    public function store(SuratKeteranganLulusRequest $request)
    {
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_berita_acara_ujian')){
            $imageFieldName = 'file_berita_acara_ujian'; 
            $uploadPath = 'upload_berita_acara_ujian';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        DB::beginTransaction();
        try{
            $input['status'] = 'menunggu tanda tangan';
            $pengajuanSuratLulus = PengajuanSuratKeteranganLulus::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan lulus gagal ditambahkan.');
        }

        try{
            $input['id_pengajuan_surat_lulus'] =  $pengajuanSuratLulus->id;
            SuratKeteranganLulus::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratLulus->nim,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>'Surat keterangan lulus telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-lulus')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>'Tanda tangan surat keterangan lulus.',
                'link_notifikasi'=>url('pimpinan/surat-keterangan-lulus')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan lulus gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat keterangan lulus mahasiswa dengan nama '.$pengajuanSuratLulus->mahasiswa->nama.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function show(SuratKeteranganLulus $suratLulus)
    {
        $surat = collect($suratLulus->load(['pengajuanSuratKeteranganLulus.mahasiswa.prodi.jurusan','kodeSurat','user']));
        $kodeSurat = explode('/',$suratLulus->kodeSurat->kode_surat);
        $surat->put('created_at',$suratLulus->created_at->isoFormat('D MMMM Y'));
        $surat->put('updated_at',$suratLulus->created_at->isoFormat('D MMMM Y'));

        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratLulus->pengajuanSuratKeteranganLulus->file_rekomendasi_jurusan));
        $surat->put('file_berita_acara_ujian',asset('upload_berita_acara_ujian/'.$suratLulus->pengajuanSuratKeteranganLulus->file_berita_acara_ujian));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratLulus->pengajuanSuratKeteranganLulus->file_rekomendasi_jurusan)[0]);
        $surat->put('nama_file_berita_acara_ujian',explode('.',$suratLulus->pengajuanSuratKeteranganLulus->file_berita_acara_ujian)[0]);


        $surat->put('nomor_surat','B/'.$suratLulus->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratLulus->created_at->year);
        return $surat->toJson();
    }

    public function edit(SuratKeteranganLulus $suratLulus)
    {
        $kodeSurat = $this->generateKodeSurat();
        $mahasiswa = $this->generateMahasiswa();
        $user = User::where('nip',$suratLulus->nip)->first();
        $userList = [
            $user->nip => strtoupper($user->jabatan).' - '.$user->nama
        ];
        $pengajuanSuratLulus = $suratLulus->pengajuanSuratKeteranganLulus;
        return view('user.pegawai.edit_surat_keterangan_lulus',compact('suratLulus','mahasiswa','kodeSurat','userList','pengajuanSuratLulus'));
    }

    public function update(SuratKeteranganLulusRequest $request, SuratKeteranganLulus $suratLulus)
    {
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$suratLulus->pengajuanSuratKeteranganLulus->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_berita_acara_ujian')){
            $imageFieldName = 'file_berita_acara_ujian'; 
            $uploadPath = 'upload_berita_acara_ujian';
            $this->deleteImage($imageFieldName,$suratLulus->pengajuanSuratKeteranganLulus->file_berita_acara_ujian);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $suratLulus->pengajuanSuratKeteranganLulus->update($input);
        $suratLulus->update($input);
        $this->setFlashData('success','Berhasil','Surat keterangan lulus mahasiswa dengan nama '.$suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->nama.' berhasil diubah');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function destroy(SuratKeteranganLulus $suratLulus)
    {
        $this->deleteImage('file_rekomendasi_jurusan',$suratLulus->pengajuanSuratKeteranganLulus->file_rekomendasi_jurusan);
        $this->deleteImage('file_berita_acara_ujian',$suratLulus->pengajuanSuratKeteranganLulus->file_berita_acara_ujian);
        $suratLulus->pengajuanSuratKeteranganLulus->delete();
        $suratLulus->delete();
        $this->setFlashData('success','Berhasil','Surat keterangan lulus mahasiswa dengan nama '.$suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }
    
    public function createSurat(PengajuanSuratKeteranganLulus $pengajuanSuratLulus){
        if(!$this->isKodeSuratLulusExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-keterangan-lulus');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.tambah_pengajuan_surat_keterangan_lulus',compact('pengajuanSuratLulus','nomorSuratBaru','userList','kodeSurat'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan_surat_lulus'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat',
            'nip'=>'required',
        ]);
        $pengajuanSuratLulus = PengajuanSuratKeteranganLulus::findOrFail($request->id_pengajuan_surat_lulus);
        $input = [
            'id_pengajuan_surat_lulus'=>$pengajuanSuratLulus->id,
            'nomor_surat'=>$request->nomor_surat,
            'id_kode_surat'=>$request->id_kode_surat,
            'nip' => $request->nip,
        ];
        DB::beginTransaction();
        try{
            $pengajuanSuratLulus->update([
                'status'=>'menunggu tanda tangan'
            ]);
            SuratKeteranganLulus::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratLulus->nim,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>'Surat keterangan lulus telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-lulus')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Keterangan Lulus',
                'isi_notifikasi'=>'Tanda tangan surat keterangan lulus.',
                'link_notifikasi'=>url('pimpinan/surat-keterangan-lulus')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan lulus gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat keterangan lulus mahasiswa dengan nama '.$pengajuanSuratLulus->mahasiswa->nama.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-lulus');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratLulus(['menunggu tanda tangan','selesai']);
            
            $pengajuanSuratLulusList =  PengajuanSuratKeteranganLulus::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
                                    
            $countAllpengajuanSuratLulus = $pengajuanSuratLulusList->count();
            $countPengajuanSuratLulus = $pengajuanSuratLulusList->count();
            
            $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderBy('status');
           
            (isset($keyword['nomor_surat'])) ? $suratLulusList = $suratLulusList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratLulusList = $suratLulusList->where('nim',$keyword['keywords']):'';

            $suratLulusList = $suratLulusList->paginate($perPage)->appends($request->except('page'));

            $countAllsuratLulus = $suratLulusList->count();
            $countSuratLulus = $suratLulusList->count();
            if($countSuratLulus < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat keterangan lulus tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_keterangan_lulus',compact('perPage','mahasiswa','pengajuanSuratLulusList','suratLulusList','countAllpengajuanSuratLulus','countAllsuratLulus','countPengajuanSuratLulus','countSuratLulus','nomorSurat'));
        }else{
            return redirect($this->segmentUser.'/surat-keterangan-lulus');
        }
    }

    public function searchPimpinan(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratLulus(['menunggu tanda tangan','selesai']);
            $pengajuanSuratLulusList =  PengajuanSuratKeteranganLulus::whereIn('status',['menunggu tanda tangan'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
            $countAllPengajuanSuratLulus  = $pengajuanSuratLulusList->count();
            $countPengajuanSuratLulus = $pengajuanSuratLulusList->count();
            
            $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                                ->whereIn('status',['selesai'])
                                ->orderBy('status');
           
            
            (isset($keyword['nomor_surat'])) ? $suratLulusList = $suratLulusList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratLulusList = $suratLulusList->where('nim',$keyword['keywords']):'';

            $suratLulusList = $suratLulusList->paginate($perPage)->appends($request->except('page'));

            $countAllSuratLulus = SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                                    ->where('status','selesai')
                                    ->orderBy('status')
                                    ->count();
            $countSuratLulus = $suratLulusList->count();

            if($countSuratLulus < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat keterangan lulus tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_keterangan_lulus',compact('perPage','mahasiswa','pengajuanSuratLulusList','suratLulusList','countAllPengajuanSuratLulus','countAllSuratLulus','countPengajuanSuratLulus','countSuratLulus','nomorSurat'));
        }else{
            return redirect($this->segmentUser.'/surat-keterangan-lulus');
        }
    }

    public function cetak(SuratketeranganLulus $suratLulus){
        $data = $suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->nim.' - '.$suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);
        if(Session::has('nim')){
            if($suratLulus->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Keterangan Lulus','Anda telah mencetak surat keterangan lulus sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-keterangan-lulus');
            }
        }
        $jumlahCetak = ++$suratLulus->jumlah_cetak;
        $suratLulus->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_keterangan_lulus',compact('suratLulus','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-keterangan-lulus'.' - '.$suratLulus->created_at->format('dmY-Him').'.pdf');
    }

    private function generateNomorSuratLulus($status){
        $suratLulusList =  SuratKeteranganLulus::join('pengajuan_surat_keterangan_lulus','pengajuan_surat_keterangan_lulus.id','=','surat_keterangan_lulus.id_pengajuan_surat_lulus')
                            ->whereIn('status',$status)
                            ->get();
        $nomorSuratList = [];
        foreach ($suratLulusList as $suratLulus) {
            $kodeSurat = explode('/',$suratLulus->kodeSurat->kode_surat);
            $nomorSuratList[$suratLulus->nomor_surat] = 'B/'.$suratLulus->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratLulus->created_at->year;
        }
        return $nomorSuratList;
    }
    
    private function isKodeSuratLulusExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan lulus')->where('status_aktif','aktif')->first();
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
        $kodeSuratList = KodeSurat::where('jenis_surat','surat keterangan lulus')->where('status_aktif','aktif')->get();
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
