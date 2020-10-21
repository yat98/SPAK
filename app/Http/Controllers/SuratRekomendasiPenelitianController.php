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
use App\SuratRekomendasiPenelitian;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratRekomendasiPenelitian;
use App\Http\Requests\SuratRekomendasiPenelitianRequest;

class SuratRekomendasiPenelitianController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        
        $countAllSurat = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','surat_rekomendasi_penelitian.id_pengajuan','=','pengajuan_surat_rekomendasi_penelitian.id')
                                                     ->whereIn('pengajuan_surat_rekomendasi_penelitian.status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                     ->count();
        
        $countAllVerifikasi = PengajuanSuratRekomendasiPenelitian::where('status','verifikasi kasubag')
                                                                   ->count();

        return view('user.'.$this->segmentUser.'.surat_rekomendasi_penelitian',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;

        $countAllPengajuan = PengajuanSuratRekomendasiPenelitian::whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratRekomendasiPenelitian::join('pengajuan_surat_rekomendasi_penelitian','surat_rekomendasi_penelitian.id_pengajuan','=','pengajuan_surat_rekomendasi_penelitian.id')
                                                 ->whereNotIn('status',['diajukan'])
                                                 ->count();

        return view($this->segmentUser.'.surat_rekomendasi_penelitian',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
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

    public function getAllSurat(){
        $suratPenelitian = PengajuanSuratRekomendasiPenelitian::join('surat_rekomendasi_penelitian','surat_rekomendasi_penelitian.id_pengajuan','=','pengajuan_surat_rekomendasi_penelitian.id')
                                    ->select('surat_rekomendasi_penelitian.nomor_surat','pengajuan_surat_rekomendasi_penelitian.*')
                                    ->with(['suratRekomendasiPenelitian.kodeSurat','mahasiswa']);

        if(isset(Auth::user()->id)){
            $suratPenelitian = $suratPenelitian->whereNotIn('status',['diajukan']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag pendidikan dan pengajaran'){
                $suratPenelitian = $suratPenelitian->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratPenelitian = $suratSurvei->where('status','selesai');
            }
        }

        return DataTables::of($suratPenelitian)
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
        $surat = collect($suratPenelitian->load(['pengajuanSuratRekomendasiPenelitian.mahasiswa.prodi.jurusan','pengajuanSuratRekomendasiPenelitian.operator','kodeSurat','user']));
        $surat->put('status',ucwords($suratPenelitian->pengajuanSuratRekomendasiPenelitian->status));
        $surat->put('dibuat',$suratPenelitian->created_at->isoFormat('D MMMM Y HH:mm:ss'));
        $surat->put('tahun',$suratPenelitian->created_at->isoFormat('Y'));
        $surat->put('file_rekomendasi_jurusan',asset('upload_rekomendasi_jurusan/'.$suratPenelitian->pengajuanSuratRekomendasiPenelitian->file_rekomendasi_jurusan));
        $surat->put('nama_file_rekomendasi_jurusan',explode('.',$suratPenelitian->pengajuanSuratRekomendasiPenelitian->file_rekomendasi_jurusan)[0]);
        
        return json_encode($surat->toArray(),JSON_HEX_QUOT | JSON_HEX_TAG);
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


    public function progress(PengajuanSuratRekomendasiPenelitian $pengajuanSurat){
        $pengajuan = $pengajuanSurat->load(['suratRekomendasiPenelitian.user','mahasiswa']);
        $data = collect($pengajuan);
        $data->put('status',ucwords($pengajuanSurat->status));

        if($pengajuan->status == 'selesai'){
            $tanggalSelesai = $pengajuanSurat->suratRekomendasiPenelitian->updated_at->isoFormat('D MMMM Y HH:mm:ss');
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

    public function createSurat(PengajuanSuratRekomendasiPenelitian $pengajuanSurat)
    {
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganPendidikanDanPengajaran();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view($this->segmentUser.'.tambah_surat_rekomendasi_penelitian',compact('userList','kodeSurat','nomorSuratBaru','userList','pengajuanSurat'));
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
            
        $pengajuanSuratPenelitian = PengajuanSuratRekomendasiPenelitian::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag pendidikan dan pengajaran')
                      ->first();
                      
        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSuratPenelitian->id;

        DB::beginTransaction();
        try{
            SuratRekomendasiPenelitian::create($input);

            $pengajuanSuratPenelitian->update([
                'status'=>'verifikasi kasubag',
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>'Verifikasi surat rekomendasi penelitian mahasiswa dengan nama '.$pengajuanSuratPenelitian->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat rekomendasi penelitian gagal ditambahkan.');
        }
        DB::commit();

        $this->setFlashData('success','Berhasil','Surat rekomendasi penelitian berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratRekomendasiPenelitian $pengajuanSurat){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSurat->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSurat->nim,
                'judul_notifikasi'=>'Surat Rekomendasi Penelitian',
                'isi_notifikasi'=>'Pengajuan surat rekomendasi penelitian ditolak.',
                'link_notifikasi'=>url('mahasiswa/surat-rekomendasi-penelitian')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat rekomendasi penelitian gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat rekomendasi penelitian berhasil ditolak');
        return redirect($this->segmentUser.'/surat-rekomendasi-penelitian');
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
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratPenelitian->pengajuanSuratRekomendasiPenelitian->nim){
                abort(404);
            }

            if($suratPenelitian->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat rekomendasi penelitian sebanyak 3 kali.');
                return redirect('mahasiswa/surat-rekomendasi-penelitian');
            }
        }

        $data = $suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->nim.' - '.$suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",4,4);
        
        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratPenelitian->jumlah_cetak;
                $suratPenelitian->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }      
        }

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
