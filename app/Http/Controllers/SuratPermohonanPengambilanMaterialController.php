<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use Storage;
use App\User;
use DataTables;
use App\KodeSurat;
use App\Mahasiswa;
use App\NotifikasiUser;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\SuratPermohonanPengambilanMaterial;
use App\PengajuanSuratPermohonanPengambilanMaterial;
use App\Http\Requests\SuratPermohonanPengambilanMaterialRequest;

class SuratPermohonanPengambilanMaterialController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        
        $countAllSurat = SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','surat_permohonan_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                                 ->whereIn('pengajuan_surat_permohonan_pengambilan_material.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                 ->count();
        
        $countAllVerifikasi = PengajuanSuratPermohonanPengambilanMaterial::where('status','verifikasi kasubag')
                                                             ->count();

        return view('user.'.$this->segmentUser.'.surat_permohonan_pengambilan_material',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratPermohonanPengambilanMaterial::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','surat_permohonan_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                                             ->whereNotIn('status',['diajukan'])
                                                             ->count();

        return view($this->segmentUser.'.surat_permohonan_pengambilan_material',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratPermohonanPengambilanMaterial::where('status','verifikasi kabag')
                                            ->count();

        $countAllSurat = SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','surat_permohonan_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                            ->where('status','selesai')
                                            ->count();
        
        $countAllTandaTangan = SuratPermohonanPengambilanMaterial::join('pengajuan_surat_permohonan_pengambilan_material','surat_permohonan_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.pimpinan.surat_permohonan_pengambilan_material',compact('countAllVerifikasi','perPage','countAllSurat','countAllTandaTangan'));
    }

    public function getAllSurat(){
        $suratMaterial = PengajuanSuratPermohonanPengambilanMaterial::join('surat_permohonan_pengambilan_material','surat_permohonan_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                    ->select('surat_permohonan_pengambilan_material.nomor_surat','pengajuan_surat_permohonan_pengambilan_material.*')
                                    ->with(['suratPermohonanPengambilanMaterial.kodeSurat','mahasiswa']);

        if(isset(Auth::user()->id)){
            $suratMaterial = $suratMaterial->whereNotIn('status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran'){
                $suratMaterial = $suratMaterial->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratMaterial = $suratMaterial->where('status','selesai');
            }
        }

        return DataTables::of($suratMaterial)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                        })
                        ->make(true);
    }

    public function getAllTandaTangan(){
        $suratMaterial =  PengajuanSuratPermohonanPengambilanMaterial::join('surat_permohonan_pengambilan_material','surat_permohonan_pengambilan_material.id_pengajuan','=','pengajuan_surat_permohonan_pengambilan_material.id')
                                    ->where('status','menunggu tanda tangan')
                                    ->where('nip',Auth::user()->nip)
                                    ->select('surat_permohonan_pengambilan_material.nomor_surat','pengajuan_surat_permohonan_pengambilan_material.*')
                                    ->with(['mahasiswa','suratPermohonanPengambilanMaterial.kodeSurat']);
                                   
        return DataTables::of($suratMaterial)
                                    ->addColumn('aksi', function ($data) {
                                        return $data->id;
                                    })
                                    ->addColumn('waktu_pengajuan', function ($data) {
                                        return $data->created_at->diffForHumans();
                                    })
                                    ->editColumn("status", function ($data) {
                                        return ucwords($data->status);
                                    })
                                    ->editColumn("created_at", function ($data) {
                                        return $data->created_at->isoFormat('D MMMM YYYY HH:mm:ss');
                                    })
                                    ->make(true);                            
    }

    public function progress(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSurat){
        $pengajuan = $pengajuanSurat->load(['suratPermohonanPengambilanMaterial.user','mahasiswa']);
        $data = collect($pengajuan);
        $data->put('status',ucwords($pengajuanSurat->status));

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuanSurat->suratPermohonanPengambilanMaterial->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalSelesai);
        }else if($pengajuan->status == 'ditolak'){
            $tanggalDitolak = $pengajuanSurat->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalDitolak);
        }else{
            $tanggal = $pengajuanSurat->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggal);
        }

        return $data->toJson();
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
        }

        $suratLulus = SuratPermohonanPengambilanMaterial::findOrFail($request->id);
        
        $suratLulus->pengajuanSuratPermohonanPengambilanMaterial->update([
            'status'=>'selesai',
        ]);

        foreach($suratLulus->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok as $mahasiswa){
            NotifikasiMahasiswa::create([
                'nim'=>$mahasiswa->nim,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>'Surat permohonan pengambilan material telah di tanda tangani.',
                'link_notifikasi'=>url('mahasiswa/surat-permohonan-pengambilan-material')
            ]);
        }

        $this->setFlashData('success','Berhasil','Tanda tangan surat permohonan pengambilan material berhasil');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function cetak(SuratPermohonanPengambilanMaterial $suratMaterial){
        if(isset(Auth::user()->nim)){
            $nim = $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok->map(function ($mahasiswa) {
                return strtoupper($mahasiswa->nim);
            })->toArray();

            if(!in_array(Auth::user()->nim,$nim)){ 
                abort(404);
            }

            if($suratMaterial->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat permohonan pengambilan material sebanyak 3 kali.');
                return redirect('mahasiswa/surat-permohonan-pengambilan-material');
            }
        }

        $data = '';
        foreach ($suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok as $value) {
            $data .= $value->nim.' - '.$value->nama.' - '.$value->prodi->nama_prodi.' ';
        }
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",2,2);

        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratMaterial->jumlah_cetak;
                $suratMaterial->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }      
        }

        $pdf = PDF::loadview('surat.surat_permohonan_pengambilan_material',compact('suratMaterial','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-permohonan-pengambilan-material'.' - '.$suratMaterial->created_at->format('dmY-Him').'.pdf');
    }

    public function show(SuratPermohonanPengambilanMaterial $suratMaterial)
    {
        $surat = collect($suratMaterial->load(['pengajuanSuratPermohonanPengambilanMaterial.mahasiswa.prodi.jurusan','pengajuanSuratPermohonanPengambilanMaterial.operator','pengajuanSuratPermohonanPengambilanMaterial.daftarKelompok','kodeSurat','user']));
        $surat->put('status',ucwords($suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->status));
        $surat->put('tahun',$suratMaterial->created_at->isoFormat('Y'));
        $surat->put('dibuat',$suratMaterial->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->file_rekomendasi_jurusan));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->file_rekomendasi_jurusan)[0]);
        return $surat->toJson();
    }

    public function createSurat(PengajuanSuratPermohonanPengambilanMaterial $pengajuanSurat)
    {
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganPendidikanDanPengajaran();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view($this->segmentUser.'.tambah_surat_permohonan_pengambilan_material',compact('userList','kodeSurat','nomorSuratBaru','userList','pengajuanSurat'));
    }

    public function storeSurat(Request $request)
    {
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat',
            'nip'=>'required',
        ]);
            
        $pengajuanSuratMaterial = PengajuanSuratPermohonanPengambilanMaterial::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag pendidikan dan pengajaran')
                      ->first();
                      
        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSuratMaterial->id;

        DB::beginTransaction();
        try{
            SuratPermohonanPengambilanMaterial::create($input);

            $pengajuanSuratMaterial->update([
                'status'=>'verifikasi kasubag',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                'isi_notifikasi'=>'Verifikasi surat permohonan pengambilan material.',
                'link_notifikasi'=>url('pegawai/surat-permohonan-pengambilan-material')
            ]);
           
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat permohonan pengambilan material gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat permohonan pengambilan material berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratPermohonanPengambilanMaterial $pengajuanSurat){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSurat->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            foreach($pengajuanSurat->daftarKelompok as $mahasiswa){
                NotifikasiMahasiswa::create([
                    'nim'=>$mahasiswa->nim,
                    'judul_notifikasi'=>'Surat Permohonan Pengambilan Material',
                    'isi_notifikasi'=>'Pengajuan surat permohonan pengambilan material ditolak.',
                    'link_notifikasi'=>url('mahasiswa/surat-permohonan-pengambilan-material')
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat permohonan pengambilan material gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat permohonan pengambilan material berhasil ditolak');
        return redirect($this->segmentUser.'/surat-permohonan-pengambilan-material');
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
