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
use App\SuratPermohonanSurvei;
use Illuminate\Support\Facades\DB;
use App\PengajuanSuratPermohonanSurvei;
use App\Http\Requests\SuratPermohonanSurveiRequest;

class SuratPermohonanSurveiController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratSurvei(['selesai','menunggu tanda tangan']);
        $pengajuanSuratSurveiList =  PengajuanSuratPermohonanSurvei::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                        ->orderByDesc('created_at')
                                        ->orderBy('status')
                                        ->paginate($perPage,['*'],'page_pengajuan');
        $suratSurveiList =  SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                            ->whereIn('status',['selesai','menunggu tanda tangan'])
                            ->orderByDesc('surat_permohonan_survei.created_at')
                            ->paginate($perPage,['*'],'page');
        $countAllPengajuanSuratSurvei = $pengajuanSuratSurveiList->count();
        $countAllSuratSurvei = $suratSurveiList->count();
        $countPengajuanSuratSurvei = $pengajuanSuratSurveiList->count();
        $countSuratSurvei = $suratSurveiList->count();
        return view('user.'.$this->segmentUser.'.surat_permohonan_survei',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratSurveiList','suratSurveiList','countAllPengajuanSuratSurvei','countAllSuratSurvei','countPengajuanSuratSurvei','countSuratSurvei'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratSurvei(['selesai']);
        $pengajuanSuratSurveiList= PengajuanSuratPermohonanSurvei::where('status','menunggu tanda tangan')
                                    ->orderByDesc('created_at')
                                    ->orderBy('status')
                                    ->paginate($perPage,['*'],'page_pengajuan');
        $suratSurveiList = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                ->where('status','selesai')
                                ->orderBy('status')
                                ->paginate($perPage,['*'],'page');
        $countAllSuratSurvei = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                ->where('status','selesai')
                                ->orderBy('status')
                                ->count();
        $countAllPengajuanSuratSurvei = $pengajuanSuratSurveiList->count();
        $countsuratSurvei = $suratSurveiList->count();
        return view('user.'.$this->segmentUser.'.surat_permohonan_survei',compact('countsuratSurvei','perPage','mahasiswa','nomorSurat','pengajuanSuratSurveiList','suratSurveiList','countAllSuratSurvei','countAllPengajuanSuratSurvei'));
    }

    public function show(SuratPermohonanSurvei $suratSurvei)
    {
        $surat = collect($suratSurvei->load(['pengajuanSuratPermohonanSurvei.mahasiswa.prodi.jurusan','kodeSurat','user']));
        $kodeSurat = explode('/',$suratSurvei->kodeSurat->kode_surat);
        $surat->put('created_at',$suratSurvei->created_at->isoFormat('D MMMM Y'));
        $surat->put('updated_at',$suratSurvei->created_at->isoFormat('D MMMM Y'));
        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratSurvei->pengajuanSuratPermohonanSurvei->file_rekomendasi_jurusan));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratSurvei->pengajuanSuratPermohonanSurvei->file_rekomendasi_jurusan)[0]);
        $surat->put('nomor_surat','B/'.$suratSurvei->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratSurvei->created_at->year);
        return $surat->toJson();
    }

    public function create(){
        if(!$this->isKodeSuratSurveiExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-survei');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.tambah_surat_permohonan_survei',compact('mahasiswa','nomorSuratBaru','userList','kodeSurat'));
    }

    public function store(SuratPermohonanSurveiRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        try{ 
            $input['status'] = 'menunggu tanda tangan';
            $pengajuan = PengajuanSuratPermohonanSurvei::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan survei gagal ditambahkan.');
        }

        try{ 
            $input['id_pengajuan'] = $pengajuan->id;
            SuratPermohonanSurvei::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuan->nim,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>'Surat permohonan survei telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-survei')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>'Tanda tangan surat permohonan survei.',
                'link_notifikasi'=>url('pimpinan/surat-permohonan-survei')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan survei gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat permohonan survei berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function edit(SuratPermohonanSurvei $suratSurvei)
    {
        $kodeSurat = $this->generateKodeSurat();
        $mahasiswa = $this->generateMahasiswa();
        $user = User::where('nip',$suratSurvei->nip)->first();
        $userList = [
            $user->nip => strtoupper($user->jabatan).' - '.$user->nama
        ];
        $pengajuanSuratSurvei = $suratSurvei->pengajuanSuratPermohonanSurvei;
        return view('user.pegawai.edit_surat_permohonan_survei',compact('suratSurvei','mahasiswa','kodeSurat','userList','pengajuanSuratSurvei'));
    }

    public function update(SuratPermohonanSurveiRequest $request, SuratPermohonanSurvei $suratSurvei)
    {
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$suratSurvei->pengajuanSuratPermohonanSurvei->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $suratSurvei->pengajuanSuratPermohonanSurvei->update($input);
        $suratSurvei->update($input);
        $this->setFlashData('success','Berhasil','Surat permohonan survei mahasiswa dengan nama '.$suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->nama.' berhasil diubah');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function destroy(SuratPermohonanSurvei $suratSurvei)
    {
        $this->deleteImage('file_rekomendasi_jurusan',$suratSurvei->pengajuanSuratPermohonanSurvei->file_rekomendasi_jurusan);
        $suratSurvei->pengajuanSuratPermohonanSurvei->delete();
        $suratSurvei->delete();
        $this->setFlashData('success','Berhasil','Surat permohonan survei mahasiswa dengan nama '.$suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function createSurat(PengajuanSuratPermohonanSurvei $pengajuanSuratSurvei){
        if(!$this->isKodeSuratSurveiExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-survei');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.tambah_pengajuan_surat_permohonan_survei',compact('pengajuanSuratSurvei','nomorSuratBaru','userList','kodeSurat'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat',
            'nip'=>'required',
        ]);
        $pengajuanSuratSurvei = PengajuanSuratPermohonanSurvei::findOrFail($request->id_pengajuan);
        $input = [
            'id_pengajuan'=>$pengajuanSuratSurvei->id,
            'nomor_surat'=>$request->nomor_surat,
            'id_kode_surat'=>$request->id_kode_surat,
            'nip' => $request->nip,
        ];
        DB::beginTransaction();
        try{
            $pengajuanSuratSurvei->update([
                'status'=>'menunggu tanda tangan'
            ]);
            SuratPermohonanSurvei::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratSurvei->nim,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>'Surat permohonan survei telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-survei')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>'Tanda tangan surat permohonan survei.',
                'link_notifikasi'=>url('pimpinan/surat-permohonan-survei')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan survei gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat permohonan survei mahasiswa dengan nama '.$pengajuanSuratSurvei->mahasiswa->nama.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratPermohonanSurvei $pengajuanSuratSurvei){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSuratSurvei->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratSurvei->nim,
                'judul_notifikasi'=>'Surat Permohonan Survei',
                'isi_notifikasi'=>'Pengajuan surat permohonan survei di tolak.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-survei')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat permohonan survei gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan survei mahasiswa dengan nama '.$pengajuanSuratSurvei->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function tandaTanganSurvei(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-permohonan-survei');
        }
        $user  = User::where('status_aktif','aktif')->where('jabatan','kasubag pendidikan dan pengajaran')->first();
        $pengajuanSuratSurvei = PengajuanSuratPermohonanSurvei::where('id',$request->id)->first();
        $pengajuanSuratSurvei->update([
            'status'=>'selesai',
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pengajuanSuratSurvei->nim,
            'judul_notifikasi'=>'Surat Permohonan Survei',
            'isi_notifikasi'=>'Surat permohonan survei telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-survei')
        ]);
        NotifikasiUser::create([
            'nip'=>$user->nip,
            'judul_notifikasi'=>'Surat Permohonan Survei',
            'isi_notifikasi'=>'Surat permohonan survei telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-permohonan-survei')
        ]);
        $this->setFlashData('success','Berhasil','Surat permohonan survei mahasiswa dengan nama '.$pengajuanSuratSurvei->mahasiswa->nama.' berhasil ditanda tangani');
        return redirect($this->segmentUser.'/surat-permohonan-survei');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratSurvei(['menunggu tanda tangan','selesai']);

            $pengajuanSuratSurveiList =  PengajuanSuratPermohonanSurvei::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
          
            $suratSurveiList = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderBy('status');

            $countAllPengajuanSuratSurvei = $pengajuanSuratSurveiList->count();
            $countAllSuratSurvei = $suratSurveiList->count();
            
            (isset($keyword['nomor_surat'])) ? $suratSurveiList = $suratSurveiList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratSurveiList = $suratSurveiList->where('nim',$keyword['keywords']):'';

            $suratSurveiList = $suratSurveiList->paginate($perPage)->appends($request->except('page'));

            $countPengajuanSuratSurvei = $pengajuanSuratSurveiList->count();
            $countSuratSurvei = $suratSurveiList->count();
            if($countSuratSurvei < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat permohonan survei tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_permohonan_survei',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratSurveiList','suratSurveiList','countAllPengajuanSuratSurvei','countAllSuratSurvei','countPengajuanSuratSurvei','countSuratSurvei'));
        }else{
            return redirect($this->segmentUser.'/surat-permohonan-survei');
        }
    }

    public function searchPimpinan(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratSurvei(['menunggu tanda tangan','selesai']);
            $pengajuanSuratSurveiList =  PengajuanSuratPermohonanSurvei::whereIn('status',['menunggu tanda tangan'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
            $countAllPengajuanSuratSurvei = $pengajuanSuratSurveiList->count();
            $countPengajuanSuratSurvei = $pengajuanSuratSurveiList  ->count();
            
            $suratSurveiList =  SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                ->whereIn('status',['selesai'])
                                ->orderBy('status');
           
            (isset($keyword['nomor_surat'])) ? $suratSurveiList = $suratSurveiList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratSurveiList = $suratSurveiList->where('nim',$keyword['keywords']):'';

            $suratSurveiList = $suratSurveiList->paginate($perPage)->appends($request->except('page'));

            $countAllSuratSurvei = SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                                    ->where('status','selesai')
                                    ->orderBy('status')
                                    ->count();
            $countsuratSurvei = $suratSurveiList->count();

            if($countsuratSurvei < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat permohonan survei tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_permohonan_survei',compact('countsuratSurvei','perPage','mahasiswa','nomorSurat','pengajuanSuratSurveiList','suratSurveiList','countAllSuratSurvei','countAllPengajuanSuratSurvei'));
        }else{
            return redirect($this->segmentUser.'/surat-permohonan-survei');
        }
    }

    public function cetak(SuratPermohonanSurvei $suratSurvei){
        $data = $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->nim.' - '.$suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);
        if(Session::has('nim')){
            if($suratSurvei->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Permohonan Survei','Anda telah mencetak surat permohonan survei sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-permohonan-survei');
            }
        }
        $jumlahCetak = ++$suratSurvei->jumlah_cetak;
        $suratSurvei->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_permohonan_survei',compact('suratSurvei','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-permohonan_survei'.' - '.$suratSurvei->created_at->format('dmY-Him').'.pdf');
    }

    private function generateNomorSuratSurvei($status){
        $suratSurveiList =  SuratPermohonanSurvei::join('pengajuan_surat_permohonan_survei','pengajuan_surat_permohonan_survei.id','=','surat_permohonan_survei.id_pengajuan')
                            ->whereIn('status',$status)
                            ->get();
        $nomorSuratList = [];
        foreach ($suratSurveiList as $suratSurvei) {
            $kodeSurat = explode('/',$suratSurvei->kodeSurat->kode_surat);
            $nomorSuratList[$suratSurvei->nomor_surat] = 'B/'.$suratSurvei->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratSurvei->created_at->year;
        }
        return $nomorSuratList;
    }
    
    private function isKodeSuratSurveiExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat permohonan survei')->where('status_aktif','aktif')->first();
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
        $kodeSuratList = KodeSurat::where('jenis_surat','surat permohonan survei')->where('status_aktif','aktif')->get();
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
