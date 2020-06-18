<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use Storage;
use App\User;
use App\KodeSurat;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\SuratRekomendasiPenelitian;
use App\PengajuanSuratRekomendasiPenelitian;
use App\Http\Requests\SuratRekomendasiPenelitianRequest;

class SuratRekomendasiPenelitianController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratPenelitian(['selesai','menunggu tanda tangan']);
        $pengajuanSuratPenelitianList =  PengajuanSuratRekomendasiPenelitian::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                        ->orderByDesc('created_at')
                                        ->orderBy('status')
                                        ->paginate($perPage,['*'],'page_pengajuan');
        $suratPenelitianList =  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->orderByDesc('surat_rekomendasi_penelitian.created_at')
                            ->paginate($perPage,['*'],'page');
        $countAllPengajuanSuratPenelitian = $pengajuanSuratPenelitianList->count();
        $countAllSuratPenelitian = $suratPenelitianList->count();
        $countPengajuanSuratPenelitian = $pengajuanSuratPenelitianList->count();
        $countSuratPenelitian = $suratPenelitianList->count();
        return view('user.'.$this->segmentUser.'.surat_rekomendasi_penelitian',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratPenelitianList','suratPenelitianList','countAllPengajuanSuratPenelitian','countAllSuratPenelitian','countPengajuanSuratPenelitian','countSuratPenelitian'));
    }

    public function suratPenelitianPimpinan(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratPenelitian(['selesai']);
        $pengajuanSuratPenelitianList= PengajuanSuratRekomendasiPenelitian::where('status','menunggu tanda tangan')
                                    ->orderByDesc('created_at')
                                    ->orderBy('status')
                                    ->paginate($perPage,['*'],'page_pengajuan');
        $suratPenelitianList = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                ->where('status','selesai')
                                ->orderBy('status')
                                ->paginate($perPage,['*'],'page');
        $countAllSuratPenelitian = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                ->where('status','selesai')
                                ->orderBy('status')
                                ->count();
        $countAllPengajuanSuratPenelitian = $pengajuanSuratPenelitianList->count();
        $countsuratPenelitian = $suratPenelitianList->count();
        return view('user.'.$this->segmentUser.'.surat_rekomendasi_penelitian',compact('countsuratPenelitian','perPage','mahasiswa','nomorSurat','pengajuanSuratPenelitianList','suratPenelitianList','countAllSuratPenelitian','countAllPengajuanSuratPenelitian'));
    }

    public function create(){
        if(!$this->isKodeSuratPenelitianExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.tambah_surat_rekomendasi_penelitian',compact('mahasiswa','nomorSuratBaru','userList','kodeSurat'));
    }

    public function store(SuratRekomendasiPenelitianRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        try{ 
            $input['status'] = 'menunggu tanda tangan';
            $pengajuan = PengajuanSuratRekomendasiPenelitian::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat rekomendasi penelitian gagal ditambahkan.');
        }

        try{ 
            $input['id_pengajuan'] = $pengajuan->id;
            SuratRekomendasiPenelitian::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuan->nim,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>'Surat rekomendasi penelitian telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-rekomendasi-penelitian')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>'Tanda tangan surat rekomendasi penelitian.',
                'link_notifikasi'=>url('pimpinan/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat rekomendasi penelitian gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat rekomendasi penelitian berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function show(SuratRekomendasiPenelitian $suratPenelitian)
    {
        $surat = collect($suratPenelitian->load(['pengajuanSuratRekomendasiPenelitian.mahasiswa.prodi.jurusan','kodeSurat','user']));
        $kodeSurat = explode('/',$suratPenelitian->kodeSurat->kode_surat);
        $surat->put('created_at',$suratPenelitian->created_at->isoFormat('D MMMM Y'));
        $surat->put('updated_at',$suratPenelitian->created_at->isoFormat('D MMMM Y'));
        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratPenelitian->pengajuanSuratRekomendasiPenelitian->file_rekomendasi_jurusan));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratPenelitian->pengajuanSuratRekomendasiPenelitian->file_rekomendasi_jurusan)[0]);
        $surat->put('nomor_surat','B/'.$suratPenelitian->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratPenelitian->created_at->year);
        return $surat->toJson();
    }

    public function edit(SuratRekomendasiPenelitian $suratPenelitian)
    {
        $kodeSurat = $this->generateKodeSurat();
        $mahasiswa = $this->generateMahasiswa();
        $user = User::where('nip',$suratPenelitian->nip)->first();
        $userList = [
            $user->nip => strtoupper($user->jabatan).' - '.$user->nama
        ];
        $pengajuanSuratPenelitian = $suratPenelitian->pengajuanSuratRekomendasiPenelitian;
        return view('user.pegawai.edit_surat_rekomendasi_penelitian',compact('suratPenelitian','mahasiswa','kodeSurat','userList','pengajuanSuratPenelitian'));
    }

    public function update(SuratRekomendasiPenelitianRequest $request, SuratRekomendasiPenelitian $suratPenelitian)
    {
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$suratPenelitian->pengajuanSuratRekomendasiPenelitian->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $suratPenelitian->pengajuanSuratRekomendasiPenelitian->update($input);
        $suratPenelitian->update($input);
        $this->setFlashData('success','Berhasil','Surat rekomendasi penelitian mahasiswa dengan nama '.$suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->nama.' berhasil diubah');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }


    public function destroy(SuratRekomendasiPenelitian $suratPenelitian)
    {
        $this->deleteImage('file_rekomendasi_jurusan',$suratPenelitian->pengajuanSuratRekomendasiPenelitian->file_rekomendasi_jurusan);
        $suratPenelitian->pengajuanSuratRekomendasiPenelitian->delete();
        $suratPenelitian->delete();
        $this->setFlashData('success','Berhasil','Surat rekomendasi penelitian mahasiswa dengan nama '.$suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function createSurat(PengajuanSuratRekomendasiPenelitian $pengajuanSuratPenelitian){
        if(!$this->isKodeSuratPenelitianExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.tambah_pengajuan_surat_rekomendasi_penelitian',compact('pengajuanSuratPenelitian','nomorSuratBaru','userList','kodeSurat'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat',
            'nip'=>'required',
            'tembusan'=>'required|string'
        ]);
        $pengajuanSuratPenelitian = PengajuanSuratRekomendasiPenelitian::findOrFail($request->id_pengajuan);
        $input = $request->all();
        DB::beginTransaction();
        try{
            $pengajuanSuratPenelitian->update([
                'status'=>'menunggu tanda tangan'
            ]);
            SuratRekomendasiPenelitian::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratPenelitian->nim,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>'Surat rekomendasi penelitian telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-rekomendasi-penelitian')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>'Tanda tangan surat rekomendasi penelitian.',
                'link_notifikasi'=>url('pimpinan/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat rekomendasi penelitian gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat rekomendasi penelitian mahasiswa dengan nama '.$pengajuanSuratPenelitian->mahasiswa->nama.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratRekomendasiPenelitian $pengajuanSuratPenelitian){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSuratPenelitian->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratPenelitian->nim,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>'Pengajuan surat rekomendasi penelitian di tolak.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat rekomendasi penelitian gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi penelitian mahasiswa dengan nama '.$pengajuanSuratPenelitian->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratPenelitian(['menunggu tanda tangan','selesai']);

            $pengajuanSuratPenelitianList =  PengajuanSuratRekomendasiPenelitian::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
          
            $suratPenelitianList = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderBy('status');

            $countAllPengajuanSuratPenelitian = $pengajuanSuratPenelitianList->count();
            $countAllSuratPenelitian = $suratPenelitianList->count();
            
            (isset($keyword['nomor_surat'])) ? $suratPenelitianList = $suratPenelitianList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratPenelitianList = $suratPenelitianList->where('nim',$keyword['keywords']):'';

            $suratPenelitianList = $suratPenelitianList->paginate($perPage)->appends($request->except('page'));

            $countPengajuanSuratPenelitian = $pengajuanSuratPenelitianList->count();
            $countSuratPenelitian = $suratPenelitianList->count();
            if($countSuratPenelitian < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat rekomendasi penelitian tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_rekomendasi_penelitian',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratPenelitianList','suratPenelitianList','countAllPengajuanSuratPenelitian','countAllSuratPenelitian','countPengajuanSuratPenelitian','countSuratPenelitian'));
        }else{
            return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
        }
    }

    public function searchPimpinan(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratPenelitian(['menunggu tanda tangan','selesai']);
            $pengajuanSuratPenelitianList =  PengajuanSuratRekomendasiPenelitian::whereIn('status',['menunggu tanda tangan'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
            $countAllPengajuanSuratPenelitian = $pengajuanSuratPenelitianList->count();
            $countPengajuanSuratPenelitian = $pengajuanSuratPenelitianList  ->count();
            
            $suratPenelitianList =  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                ->whereIn('status',['selesai'])
                                ->orderBy('status');
           
            (isset($keyword['nomor_surat'])) ? $suratPenelitianList = $suratPenelitianList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratPenelitianList = $suratPenelitianList->where('nim',$keyword['keywords']):'';

            $suratPenelitianList = $suratPenelitianList->paginate($perPage)->appends($request->except('page'));

            $countAllSuratPenelitian = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                                    ->where('status','selesai')
                                    ->orderBy('status')
                                    ->count();
            $countsuratPenelitian = $suratPenelitianList->count();

            if($countsuratPenelitian < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat rekomendasi penelitian tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_rekomendasi_penelitian',compact('countsuratPenelitian','perPage','mahasiswa','nomorSurat','pengajuanSuratPenelitianList','suratPenelitianList','countAllSuratPenelitian','countAllPengajuanSuratPenelitian'));
        }else{
            return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
        }
    }

    public function tandaTanganPenelitian(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
        }
        $user  = User::where('status_aktif','aktif')->where('jabatan','kasubag pendidikan dan pengajaran')->first();
        $pengajuanSuratPenelitian = PengajuanSuratRekomendasiPenelitian::where('id',$request->id)->first();
        $pengajuanSuratPenelitian->update([
            'status'=>'selesai',
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pengajuanSuratPenelitian->nim,
            'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
            'isi_notifikasi'=>'Surat rekomendasi penelitian telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-rekomendasi-penelitian')
        ]);
        NotifikasiUser::create([
            'nip'=>$user->nip,
            'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
            'isi_notifikasi'=>'Surat rekomendasi penelitian telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-rekomendasi-penelitian')
        ]);
        $this->setFlashData('success','Berhasil','Surat rekomendasi penelitian mahasiswa dengan nama '.$pengajuanSuratPenelitian->mahasiswa->nama.' berhasil ditanda tangani');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function cetak(SuratRekomendasiPenelitian $suratPenelitian){
        $data = $suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->nim.' - '.$suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);
        if(Session::has('nim')){
            if($suratPenelitian->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Rekomendasi Penelitian','Anda telah mencetak surat rekomendasi penelitian sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-rekomendasi-penelitian');
            }
        }
        $jumlahCetak = ++$suratPenelitian->jumlah_cetak;
        $suratPenelitian->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_rekomendasi_penelitian',compact('suratPenelitian','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-rekomendasi-penelitian'.' - '.$suratPenelitian->created_at->format('dmY-Him').'.pdf');
    }

    private function generateNomorSuratPenelitian($status){
        $suratPenelitianList =  SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','pengajuan_surat_rekomendasi_penelitian.id','=','surat_rekomendasi_penelitian.id_pengajuan')
                            ->whereIn('status',$status)
                            ->get();
        $nomorSuratList = [];
        foreach ($suratPenelitianList as $suratPenelitian) {
            $kodeSurat = explode('/',$suratPenelitian->kodeSurat->kode_surat);
            $nomorSuratList[$suratPenelitian->nomor_surat] = 'B/'.$suratPenelitian->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratPenelitian->created_at->year;
        }
        return $nomorSuratList;
    }
    
    private function isKodeSuratPenelitianExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat rekomendasi penelitian')->where('status_aktif','aktif')->first();
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
        $kodeSuratList = KodeSurat::where('jenis_surat','surat rekomendasi penelitian')->where('status_aktif','aktif')->get();
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
