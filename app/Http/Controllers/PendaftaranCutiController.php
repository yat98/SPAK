<?php

namespace App\Http\Controllers;

use Storage;
use Session;
use App\User;
use Carbon\Carbon;
use App\WaktuCuti;
use App\TahunAkademik;
use App\NotifikasiUser;
use App\PendaftaranCuti;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PendaftaranCutiRequest;

class PendaftaranCutiController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $pengajuanCutiList = PendaftaranCuti::whereIn('status',['diajukan','ditolak'])->orderBy('status')->paginate($perPage);
        $pendaftaranCutiList = PendaftaranCuti::where('status','diterima')->orderByDesc('created_at')->paginate($perPage);

        $countAllPengajuanCuti =PendaftaranCuti::whereIn('status',['diajukan','ditolak'])->count() ;
        $countAllPendaftaranCuti = PendaftaranCuti::where('status','diterima')->count();
        
        $countPengajuanCuti = $pengajuanCutiList->count();
        $countPendaftaranCuti = $pendaftaranCutiList->count();

        $waktuCuti = $this->generateWaktuCuti();
        $mahasiswa = $this->generateMahasiswa();
        return view('user.'.$this->segmentUser.'.pendaftaran_cuti',compact('perPage','countAllPengajuanCuti','countAllPendaftaranCuti','pengajuanCutiList','pendaftaranCutiList','waktuCuti','mahasiswa','countPengajuanCuti','countPendaftaranCuti'));
    }

    public function pendaftaranCutiMahasiswa()
    {
        $perPage = $this->perPage;
        $pendaftaranCutiList = PendaftaranCuti::where('nim',Session::get('nim'))->paginate($perPage);
        $countAllPendaftaranCuti = PendaftaranCuti::all()->count();
        $countPendaftaranCuti = $pendaftaranCutiList->count();
        return view($this->segmentUser.'.pendaftaran_cuti',compact('perPage','countAllPendaftaranCuti','pendaftaranCutiList','countPendaftaranCuti'));
    }

    public function search(Request $request){
        $input = $request->all();
        if(isset($input['waktu_cuti']) || isset($input['keywords'])){
            $perPage = $this->perPage;
            $nim = $input['keywords'] != null ? $input['keywords'] : '';
            $countAllPengajuanCuti =PendaftaranCuti::all()->count() ;
            $countAllPendaftaranCuti = PendaftaranCuti::all()->count();
            
            $pengajuanCutiList = PendaftaranCuti::whereIn('status',['diajukan','ditolak'])->orderBy('status')->paginate($perPage);
            $pendaftaranCutiList = PendaftaranCuti::where('nim','like',"%$nim%")
                                        ->where('status','diterima');

            isset($input['waktu_cuti']) ? $pendaftaranCutiList = $pendaftaranCutiList->where('id_waktu_cuti',$input['waktu_cuti']): '';
            $pendaftaranCutiList = $pendaftaranCutiList->paginate($perPage);
            
            $countPengajuanCuti = $pengajuanCutiList->count();
            $countPendaftaranCuti = $pendaftaranCutiList->count();
            $waktuCuti = $this->generateWaktuCuti();
            $mahasiswa = $this->generateMahasiswa();
            if($countPendaftaranCuti < 1){
                $this->setFlashData('search','Hasil Pencarian','Pendaftaran cuti tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.pendaftaran_cuti',compact('perPage','countAllPengajuanCuti','countAllPendaftaranCuti','pengajuanCutiList','pendaftaranCutiList','waktuCuti','mahasiswa','countPengajuanCuti','countPendaftaranCuti'));
        }else{
            return redirect($this->segmentUser.'/pendaftaran-cuti');
        }
    }

    public function create()
    {
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $waktuCuti = WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first();
        if($this->segmentUser == 'mahasiswa'){
            if(!$this->isPendaftaranCutiMahasiswaExists($tahunAkademikAktif,$waktuCuti) || !$this->isTahunAkademikAktifExists()){
                return redirect($this->segmentUser.'/pendaftaran-cuti');
            }
            return view($this->segmentUser.'.tambah_pendaftaran_cuti',compact('waktuCuti'));
        }
        if(!isset($waktuCuti)){
            $this->setFlashData('info','Waktu Pendaftaran Cuti','Waktu pendaftaran cuti belum di ada');
            return redirect($this->segmentUser.'/pendaftaran-cuti');
        }
        $mahasiswa = $this->generateMahasiswa();
        $waktuCuti = $this->generateWaktuCuti();
        return view('user.'.$this->segmentUser.'.tambah_pendaftaran_cuti',compact('waktuCuti','mahasiswa'));
    }

    public function show(PendaftaranCuti $pendaftaranCuti)
    {
        $pendaftaran = collect($pendaftaranCuti->load(['mahasiswa','waktuCuti.tahunAkademik']));
        $pendaftaran->put('created_at',$pendaftaranCuti->created_at->isoFormat('D MMMM Y'));
        $pendaftaran->put('updated_at',$pendaftaranCuti->updated_at->isoFormat('D MMMM Y'));
        $pendaftaran->put('file_surat_permohonan_cuti',asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_surat_permohonan_cuti));
        $pendaftaran->put('file_krs_sebelumnya',asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_krs_sebelumnya));
        $pendaftaran->put('file_slip_ukt',asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_slip_ukt));
        $pendaftaran->put('nama_file_surat_permohonan_cuti',explode('.',$pendaftaranCuti->file_surat_permohonan_cuti)[0]);
        $pendaftaran->put('nama_file_krs_sebelumnya',explode('.',$pendaftaranCuti->file_krs_sebelumnya)[0]);
        $pendaftaran->put('nama_file_slip_ukt',explode('.',$pendaftaranCuti->file_slip_ukt)[0]);
        return $pendaftaran->toJson();
    }

    public function edit(PendaftaranCuti $pendaftaranCuti){
        if($this->segmentUser == 'pegawai'){
            $mahasiswa = $this->generateMahasiswa();
            $waktuCuti[$pendaftaranCuti->id_waktu_cuti] = $pendaftaranCuti->waktuCuti->tahunAkademik->tahun_akademik.' - '.ucwords($pendaftaranCuti->waktuCuti->tahunAkademik->semester);
            return view('user.'.$this->segmentUser.'.edit_pendaftaran_cuti',compact('mahasiswa','pendaftaranCuti','waktuCuti'));
        }else{
            $waktuCuti = $pendaftaranCuti->waktuCuti;
            return view($this->segmentUser.'.edit_pendaftaran_cuti',compact('pendaftaranCuti','waktuCuti'));
        }
    }

    public function update(PendaftaranCutiRequest $request,PendaftaranCuti $pendaftaranCuti){
        $input = $request->all();
        if($request->has('file_surat_permohonan_cuti')){
            $imageFieldName = 'file_surat_permohonan_cuti'; 
            $uploadPath = 'upload_permohonan_cuti';
            $this->deleteImage($pendaftaranCuti->file_surat_permohonan_cuti);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_krs_sebelumnya')){
            $imageFieldName = 'file_krs_sebelumnya'; 
            $uploadPath = 'upload_permohonan_cuti';
            $this->deleteImage($pendaftaranCuti->file_krs_sebelumnya);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_slip_ukt')){
            $imageFieldName = 'file_slip_ukt'; 
            $uploadPath = 'upload_permohonan_cuti';
            $this->deleteImage($pendaftaranCuti->file_slip_ukt);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        DB::beginTransaction();
        try{
            $pendaftaranCuti->update($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Pendaftaran cuti gagal diubah.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pendaftaran cuti berhasil diubah');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    public function store(PendaftaranCutiRequest $request){
        $input = $request->all();
        if($request->has('file_surat_permohonan_cuti')){
            $imageFieldName = 'file_surat_permohonan_cuti'; 
            $uploadPath = 'upload_permohonan_cuti';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_krs_sebelumnya')){
            $imageFieldName = 'file_krs_sebelumnya'; 
            $uploadPath = 'upload_permohonan_cuti';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        if($request->has('file_slip_ukt')){
            $imageFieldName = 'file_slip_ukt'; 
            $uploadPath = 'upload_permohonan_cuti';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        DB::beginTransaction();
        try{
            if($this->segmentUser == 'pegawai'){
                $input['status'] = 'diterima';
                NotifikasiMahasiswa::create([
                    'nim'=>$request->nim,
                    'judul_notifikasi'=>'Pendaftaran Cuti',
                    'isi_notifikasi'=>'Pendaftaran cuti telah diterima.',
                    'link_notifikasi'=>url('mahasiswa/pendaftaran-cuti')
                ]);
            }
            $pendaftaran = PendaftaranCuti::create($input);
            if($this->segmentUser == 'mahasiswa'){
                $user = User::where('jabatan','kasubag kemahasiswaan')->first();
                NotifikasiUser::create([
                    'nip'=>$user->nip,
                    'judul_notifikasi'=>'Pendaftaran Cuti',
                    'isi_notifikasi'=>'Mahasiswa dengan nama '.$pendaftaran->mahasiswa->nama.' melakukan pendaftaran cuti.',
                    'link_notifikasi'=>url('pegawai/pendaftaran-cuti')
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Pendaftaran cuti gagal ditambahkan.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pendaftaran cuti berhasil ditambahkan');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    public function destroy(PendaftaranCuti $pendaftaranCuti)
    {
        $this->deleteImage($pendaftaranCuti->file_surat_permohonan_cuti);
        $this->deleteImage($pendaftaranCuti->file_krs_sebelumnya);
        $this->deleteImage($pendaftaranCuti->file_slip_ukt);
        $pendaftaranCuti->delete();
        $this->setFlashData('success','Berhasil','Pendaftaran cuti berhasil dihapus');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    public function terima(PendaftaranCuti $pendaftaranCuti){
        $pendaftaranCuti->update([
            'status'=>'diterima'
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pendaftaranCuti->nim,
            'judul_notifikasi'=>'Pendaftaran Cuti',
            'isi_notifikasi'=>'Pendaftaran cuti telah diterima.',
            'link_notifikasi'=>url('mahasiswa/pendaftaran-cuti')
        ]);
        $this->setFlashData('success','Berhasil','Pendaftaran cuti dengan nama mahasiswa '.$pendaftaranCuti->mahasiswa->nama. ' diterima');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
    }

    public function tolak(Request $request,PendaftaranCuti $pendaftaranCuti){
        $keterangan = $request->keterangan ?? '-';
        $pendaftaranCuti->update([
            'status'=>'ditolak',
            'keterangan'=>$keterangan
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pendaftaranCuti->nim,
            'judul_notifikasi'=>'Pendaftaran Cuti',
            'isi_notifikasi'=>'Pendaftaran cuti telah ditolak.',
            'link_notifikasi'=>url('mahasiswa/pendaftaran-cuti')
        ]);
        $this->setFlashData('success','Berhasil','Pendaftaran cuti dengan nama mahasiswa '.$pendaftaranCuti->mahasiswa->nama. ' ditolak');
        return redirect($this->segmentUser.'/pendaftaran-cuti');
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
    
    private function deleteImage($imageName){
        $exist = Storage::disk('file_permohonan_cuti')->exists($imageName);
        if(isset($imageName) && $exist){
            $delete = Storage::disk('file_permohonan_cuti')->delete($imageName);
            if($delete) return true;
            return false;
        }
    }

    private function isTahunAkademikAktifExists(){
        $countTahunAkademik = TahunAkademik::where('status_aktif','aktif')->get()->count();
        if($countTahunAkademik < 1){
            $this->setFlashData('info','Data Tahun Akademik Belum Aktif','Aktifkan tahun akademik terlebih dahulu sebelum menambahkan data status mahasiswa!');
            return false;
        }
        return true;
    }

    private function isPendaftaranCutiMahasiswaExists($tahunAkademikAktif,$waktuCuti){
        if(!isset($waktuCuti)){
            $this->setFlashData('info','Waktu Pendaftaran Cuti','Waktu pendaftaran cuti belum di buka');
            return false;
        }else{
            $tgl = Carbon::now();
            $countPendaftaranCutiSelesai =  PendaftaranCuti::where('id_waktu_cuti',$waktuCuti->id)
                        ->where('nim',Session::get('nim'))
                        ->where('status','diterima')->count();
            $countPendaftaranCutiDiajukan =  PendaftaranCuti::where('id_waktu_cuti',$waktuCuti->id)
                        ->where('nim',Session::get('nim'))
                        ->where('status','diajukan')->count();
            if($countPendaftaranCutiSelesai >= 1){
                $this->setFlashData('info','Pendaftaran Cuti Diterima','Pendaftaran cuti anda telah diterima');
                return false;
            }
            if($countPendaftaranCutiDiajukan >= 1){
                $this->setFlashData('info','Pendaftaran Cuti Diajukan','Mohon menunggu, pendaftaran cuti anda akan segera di proses');
                return false;
            }
            
            if($tgl->lessThanOrEqualTo($waktuCuti->tanggal_awal_cuti)){
                $this->setFlashData('info','Pendaftaran Cuti','Pendaftaran cuti semester ini belum di buka');
                return false;
            }  
            
            if($tgl->greaterThanOrEqualTo($waktuCuti->tanggal_akhir_cuti)){
                $this->setFlashData('info','Pendaftaran Cuti','Pendaftaran cuti semester ini telah selesai');
                return false;
            }
        }
        return true;
    }

    private function generateWaktuCuti(){
        $waktuCuti = WaktuCuti::all();
        $waktuCutiList = [];
        foreach($waktuCuti as $value){
            $waktuCutiList[$value->id] = $value->tahunAkademik->tahun_akademik.' - '.ucwords($value->tahunAkademik->semester);
        }
        return $waktuCutiList;
    }
}
