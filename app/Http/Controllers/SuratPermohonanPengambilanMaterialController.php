<?php

namespace App\Http\Controllers;

use PDF;
use Storage;
use Session;
use App\User;
use App\KodeSurat;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\SuratPermohonanPengambilanMaterial;
use App\PengajuanSuratPermohonanPengambilanMaterial;
use App\Http\Requests\SuratPermohonanPengambilanMaterialRequest;

class SuratPermohonanPengambilanMaterialController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratMaterial(['selesai','menunggu tanda tangan']);
        $pengajuanSuratMaterialList =  PengajuanSuratPermohonanPengambilanMaterial::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                        ->orderByDesc('created_at')
                                        ->orderBy('status')
                                        ->paginate($perPage,['*'],'page_pengajuan');

        $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                ->whereIn('status',['selesai','menunggu tanda tangan'])
                                ->orderBy('status')
                                ->paginate($perPage,['*'],'page');

        $countAllPengajuanSuratMaterial = $pengajuanSuratMaterialList->count();
        $countAllSuratMaterial = $suratMaterialList->count();
        $countPengajuanSuratMaterial = $pengajuanSuratMaterialList->count();
        $countSuratMaterial = $suratMaterialList->count();
        return view('user.'.$this->segmentUser.'.surat_permohonan_pengambilan_material',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratMaterialList','suratMaterialList','countAllPengajuanSuratMaterial','countAllSuratMaterial','countPengajuanSuratMaterial','countSuratMaterial'));
    }

    public function suratMaterialPimpinan(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $nomorSurat = $this->generateNomorSuratMaterial(['selesai']);
        $pengajuanSuratMaterialList =SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                        ->whereIn('status',['menunggu tanda tangan'])
                                        ->orderByDesc('surat_permohonan_pengambilan_material.created_at')
                                        ->paginate($perPage);
        $countAllPengajuanMaterial = $pengajuanSuratMaterialList->count();
        $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                ->whereIn('status',['selesai'])
                                ->orderBy('status')
                                ->paginate($perPage);
        $countAllSuratMaterial=$suratMaterialList->count();
        $countsuratMaterial=$suratMaterialList->count();
        return view('user.'.$this->segmentUser.'.surat_permohonan_pengambilan_material',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratMaterialList','countAllPengajuanMaterial','suratMaterialList','countAllSuratMaterial','countsuratMaterial'));
    }

    public function tandaTanganMaterial(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
        }
        $user  = User::where('status_aktif','aktif')->where('jabatan','kasubag pendidikan dan pengajaran')->first();
        $pengajuanSuratMaterial = PengajuanSuratPermohonanPengambilanMaterial::where('id',$request->id)->first();
        $pengajuanSuratMaterial->update([
            'status'=>'selesai',
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pengajuanSuratMaterial->nim,
            'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
            'isi_notifikasi'=>'Surat Permohonan pengambilan material telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-lulus')
        ]);
        NotifikasiUser::create([
            'nip'=>$user->nip,
            'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
            'isi_notifikasi'=>'Surat Permohonan pengambilan material telah di tanda tangani.',
            'link_notifikasi'=>url('pegawai/surat-keterangan-lulus')
        ]);
        $this->setFlashData('success','Berhasil','Surat Permohonan pengambilan material berhasil ditanda tangani');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function cetak(SuratPermohonanPengambilanMaterial $suratMaterial){
        if(Session::has('nim')){
            if($suratMaterial->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Permohonan Pengambialn Material','Anda telah mencetak surat permohonan pengambilan material sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-permohonan-pengambilan-material');
            }
        }
        $jumlahCetak = ++$suratMaterial->jumlah_cetak;
        $suratMaterial->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_permohonan_pengambilan_material',compact('suratMaterial'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-permohonan-pengambilan-material'.' - '.$suratMaterial->created_at->format('dmY-Him').'.pdf');
    }

    public function create(){
        if(!$this->isKodeSuratMaterialExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
        }
        $mahasiswa = $this->generateMahasiswa();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.tambah_surat_permohonan_pengajuan_material',compact('nomorSuratBaru','userList','kodeSurat','mahasiswa'));
    }

    public function store(SuratPermohonanPengambilanMaterialRequest $request){
        $input = $request->all();
        $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();

        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }

        try{ 
            $input['status'] = 'menunggu tanda tangan';
            $pengajuan = PengajuanSuratPermohonanPengambilanMaterial::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan pengambilan material gagal ditambahkan.');
        }

        try{ 
            $input['id_pengajuan'] = $pengajuan->id;
            $pengajuan->daftarKelompok()->attach($request->daftar_kelompok);
            SuratPermohonanPengambilanMaterial::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuan->nim,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>'Surat permohonan pengambilan material telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-pengambilan-material')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>'Tanda tangan surat permohonan pengambilan material.',
                'link_notifikasi'=>url('pimpinan/surat-permohonan-pengambilan-material')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan pengambilan material gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan material berhasil ditambahkan.');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function edit(SuratPermohonanPengambilanMaterial $suratMaterial){
        $pengajuanSuratMaterial = $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial;
        $mahasiswa = $this->generateMahasiswa();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.'.$this->segmentUser.'.edit_surat_permohonan_pengambilan_material',compact('suratMaterial','pengajuanSuratMaterial','userList','kodeSurat','mahasiswa'));
    }

    public function update(SuratPermohonanPengambilanMaterialRequest $request, SuratPermohonanPengambilanMaterial $suratMaterial){
        $input = $request->all();
        if($request->has('file_rekomendasi_jurusan')){
            $imageFieldName = 'file_rekomendasi_jurusan'; 
            $uploadPath = 'upload_rekomendasi_jurusan';
            $this->deleteImage($imageFieldName,$suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->file_rekomendasi_jurusan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $suratMaterial->update($input);
        $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->update($input);
        $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok()->sync($request->daftar_kelompok);
        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan material berhasil diubah.');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function destroy(SuratPermohonanPengambilanMaterial $suratMaterial){
        $this->deleteImage('file_rekomendasi_jurusan',$suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->file_rekomendasi_jurusan);
        $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->delete();
        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan material berhasil dihapus.');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;

            
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratMaterial(['selesai','menunggu tanda tangan']);
            $pengajuanSuratMaterialList =  PengajuanSuratPermohonanPengambilanMaterial::whereNotIn('status',['menunggu tanda tangan','selesai'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
    
            $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                    ->whereIn('status',['selesai','menunggu tanda tangan'])
                                    ->orderBy('status');
    
            $countAllSuratMaterial = SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                        ->whereIn('status',['selesai','menunggu tanda tangan'])
                                        ->orderBy('status')
                                        ->count();

            $countPengajuanSuratMaterial = $pengajuanSuratMaterialList->count();
            

            (isset($keyword['nomor_surat'])) ? $suratMaterialList = $suratMaterialList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratMaterialList = $suratMaterialList->where('nim',$keyword['keywords']):'';

            $suratMaterialList = $suratMaterialList->paginate($perPage)->appends($request->except('page'));

            $countAllPengajuanSuratMaterial = $pengajuanSuratMaterialList->count();
            $countSuratMaterial = $suratMaterialList->count();
            if($countSuratMaterial < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat keterangan lulus tidak ditemukan!');
            }

            return view('user.'.$this->segmentUser.'.surat_permohonan_pengambilan_material',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratMaterialList','suratMaterialList','countAllPengajuanSuratMaterial','countAllSuratMaterial','countPengajuanSuratMaterial','countSuratMaterial'));
        }else{
            return redirect($this->segmentUser.'/surat-keterangan-lulus');
        }
    }

    public function searchPimpinan(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $nomorSurat = $this->generateNomorSuratMaterial(['selesai']);


            $pengajuanSuratMaterialList =SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                        ->whereIn('status',['menunggu tanda tangan'])
                                        ->orderByDesc('surat_permohonan_pengambilan_material.created_at')
                                        ->paginate($perPage);
            $countAllPengajuanMaterial = $pengajuanSuratMaterialList->count();
            $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                    ->whereIn('status',['selesai'])
                                    ->orderBy('status');

            (isset($keyword['nomor_surat'])) ? $suratMaterialList = $suratMaterialList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratMaterialList = $suratMaterialList->where('nim',$keyword['keywords']):'';
            
            $suratMaterialList = $suratMaterialList->paginate($perPage)->appends($request->except('page'));

            $countAllSuratMaterial = SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                        ->whereIn('status',['selesai'])
                                        ->count();
            $countsuratMaterial = $suratMaterialList->count();

            if($countsuratMaterial < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat permohonan pengambilan material tidak ditemukan!');
            }

            return view('user.'.$this->segmentUser.'.surat_permohonan_pengambilan_material',compact('perPage','mahasiswa','nomorSurat','pengajuanSuratMaterialList','countAllPengajuanMaterial','suratMaterialList','countAllSuratMaterial','countsuratMaterial'));
        }else{
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
        }
    }

    public function show(SuratPermohonanPengambilanMaterial $suratMaterial)
    {
        $surat = collect($suratMaterial->load(['pengajuanSuratPermohonanPengambilanMaterial.mahasiswa.prodi.jurusan','pengajuanSuratPermohonanPengambilanMaterial.daftarKelompok','user']));
        $surat->put('created_at',$suratMaterial->created_at->isoFormat('D MMMM Y'));
        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->file_rekomendasi_jurusan));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->file_rekomendasi_jurusan)[0]);
        $kodeSurat = explode('/',$suratMaterial->kodeSurat->kode_surat);
        $surat->put('nomor_surat','B/'.$suratMaterial->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratMaterial->created_at->year);
        return $surat->toJson();
    }

    public function createSurat(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSuratMaterial){
        if(!$this->isKodeSuratMaterialExists() || !$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.tambah_pengajuan_surat_permohonan_pengambilan_material',compact('pengajuanSuratMaterial','nomorSuratBaru','userList','kodeSurat'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat',
            'nip'=>'required',
        ]);
        $pengajuanSuratMaterial = PengajuanSuratPermohonanPengambilanMaterial::findOrFail($request->id_pengajuan);
        $input = [
            'id_pengajuan'=>$pengajuanSuratMaterial->id,
            'nomor_surat'=>$request->nomor_surat,
            'id_kode_surat'=>$request->id_kode_surat,
            'nip' => $request->nip,
        ];
        DB::beginTransaction();
        try{
            $pengajuanSuratMaterial->update([
                'status'=>'menunggu tanda tangan'
            ]);
            SuratPermohonanPengambilanMaterial::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratMaterial->nim,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>'Surat permohonan pengambilan material telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-pengambilan-material')
            ]);
            NotifikasiUser::create([
                'nip'=>$request->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>'Tanda tangan surat permohonan pengambilan material.',
                'link_notifikasi'=>url('pimpinan/surat-permohonan-pengambilan-material')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan pengambilan material gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan material berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratPermohonanPengambilanMaterial $pengajuanSuratMaterial){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSuratMaterial->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratMaterial->nim,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>'Pengajuan surat permohonan pengambilan material di tolak.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-permohonan-pengambilan-material')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat permohonan pengambilan material gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan material ditolak');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    private function generateNomorSuratMaterial($status){
        $suratMaterialList =  SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','pengajuan_surat_permohonan_pengambilan_material.id','=','surat_permohonan_pengambilan_material.id_pengajuan')
                                ->whereIn('status',$status)
                                ->get();
        $nomorSuratList = [];
        foreach ($suratMaterialList as $suratMaterial) {
            $kodeSurat = explode('/',$suratMaterial->kodeSurat->kode_surat);
            $nomorSuratList[$suratMaterial->nomor_surat] = 'B/'.$suratMaterial->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratMaterial->created_at->year;
        }
        return $nomorSuratList;
    }

    private function isKodeSuratMaterialExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat permohonan pengambilan material')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat permohonan pengambilan material')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinan = User::where('jabatan','wd1')->where('status_aktif','aktif')->first();
        $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        return $user;
    }

    private function uploadImage($imageFieldName, $request, $uploadPath){
        $image = $request->file($imageFieldName);
        $ext = $image->getClientOriginalExtension();
        if($image->isValid()){
            $imageName = Session::get('nim').'_'.date('YmdHis').'_'.$imageFieldName.".$ext";
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
