<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use Storage;
use App\User;
use App\Ormawa;
use App\KodeSurat;
use App\NotifikasiUser;
use App\PimpinanOrmawa;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\SuratKegiatanMahasiswa;
use Illuminate\Support\Facades\DB;
use App\PengajuanSuratKegiatanMahasiswa;
use App\Http\Requests\SuratKegiatanMahasiswaRequest;
use App\Http\Requests\PengajuanSuratKegiatanMahasiswaRequest;

class SuratKegiatanMahasiswaController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $countAllPengajuanKegiatan = PengajuanSuratKegiatanMahasiswa::whereNotIn('status',['selesai'])->count();
        $countAllSuratKegiatan = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                    ->where('status','selesai')
                                    ->count();

        $pengajuanKegiatanList = PengajuanSuratKegiatanMahasiswa::whereNotIn('status',['selesai'])->paginate($perPage);
        $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->paginate($perPage);

        $countPengajuanKegiatan = $pengajuanKegiatanList->count();
        $countSuratKegiatan =  $suratKegiatanList->count();

        $nomorSurat = $this->generateNomorSuratKegiatan(['selesai']);
        return view('user.'.$this->segmentUser.'.surat_kegiatan_mahasiswa',compact('countAllPengajuanKegiatan','perPage','countAllSuratKegiatan','countPengajuanKegiatan','nomorSurat','countSuratKegiatan','pengajuanKegiatanList','suratKegiatanList'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;
        $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->paginate($perPage);
        $countAllSuratKegiatan = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('status','selesai')
                                ->count();
        $countSuratKegiatan = $suratKegiatanList->count();
        $nomorSurat = $this->generateNomorSuratKegiatan(['selesai']);
        if(Session::get('jabatan') == 'dekan'){
            $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::where('status','diterima')->count();
            $disposisiList = PengajuanSuratKegiatanMahasiswa::orderByDesc('created_at')->whereIn('status',['diterima','disposisi dekan','disposisi wakil dekan I','disposisi wakil dekan II','disposisi wakil dekan III','surat dibuat'])->paginate($perPage);
            $tandaTanganList = PengajuanSuratKegiatanMahasiswa::orderByDesc('created_at')->where('status','menunggu tanda tangan')->paginate($perPage);
            $countDisposisi = $disposisiList->count();
            $countTandaTangan = $tandaTanganList->count();
            $countAllTandaTangan = PengajuanSuratKegiatanMahasiswa::where('status','menunggu tanda tangan')->count();
            return view('user.'.$this->segmentUser.'.surat_kegiatan_mahasiswa',compact('countAllDisposisi','disposisiList','countDisposisi','perPage','countAllTandaTangan','tandaTanganList','countTandaTangan','suratKegiatanList','countSuratKegiatan','countAllSuratKegiatan','nomorSurat'));
        }else if(Session::get('jabatan') == 'wd1'){
            $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::where('status','disposisi dekan')->count();
            $disposisiList = PengajuanSuratKegiatanMahasiswa::orderByDesc('updated_at')->whereIn('status',['disposisi dekan','disposisi wakil dekan I','disposisi wakil dekan II','disposisi wakil dekan III','surat dibuat','menunggu tanda tangan'])->paginate($perPage);
        }else if(Session::get('jabatan') == 'wd2'){
            $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::where('status','disposisi wakil dekan I')->count();
            $disposisiList = PengajuanSuratKegiatanMahasiswa::orderByDesc('updated_at')->whereIn('status',['disposisi wakil dekan I','disposisi wakil dekan II','disposisi wakil dekan III','surat dibuat','menunggu tanda tangan'])->paginate($perPage);
        }else if(Session::get('jabatan') == 'wd3'){
            $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::where('status','disposisi wakil dekan II')->count();
            $disposisiList = PengajuanSuratKegiatanMahasiswa::orderByDesc('updated_at')->whereIn('status',['disposisi wakil dekan II','disposisi wakil dekan III','surat dibuat','menunggu tanda tangan'])->paginate($perPage);
        }
        $countDisposisi = $disposisiList->count();
        return view('user.'.$this->segmentUser.'.surat_kegiatan_mahasiswa',compact('countAllDisposisi','disposisiList','countDisposisi','perPage','suratKegiatanList','countSuratKegiatan','countAllSuratKegiatan','nomorSurat'));
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords'])){
            $nomor = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $perPage = $this->perPage;
            $countAllPengajuanKegiatan = PengajuanSuratKegiatanMahasiswa::whereNotIn('status',['selesai'])->count();
            $countAllSuratKegiatan = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                        ->where('status','selesai')
                                        ->count();
    
            $pengajuanKegiatanList = PengajuanSuratKegiatanMahasiswa::whereNotIn('status',['selesai'])->paginate($perPage);
            $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                    ->where('status','selesai')
                                    ->where('nomor_surat',$nomor)
                                    ->paginate($perPage);
    
            $countPengajuanKegiatan = $pengajuanKegiatanList->count();
            $countSuratKegiatan =  $suratKegiatanList->count();
    
            $nomorSurat = $this->generateNomorSuratKegiatan(['selesai']);
            if($countSuratKegiatan < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat kegiatan mahasiswa tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_kegiatan_mahasiswa',compact('countAllPengajuanKegiatan','perPage','countAllSuratKegiatan','countPengajuanKegiatan','nomorSurat','countSuratKegiatan','pengajuanKegiatanList','suratKegiatanList'));
        }else{
            return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
        }
    }

    public function searchPimpinan(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords'])){
            $nomor = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $perPage = $this->perPage;
            
            $suratKegiatanList =  SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                ->where('nomor_surat',$nomor)
                                ->where('status','selesai')
                                ->paginate($perPage);
            $countAllSuratKegiatan = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                                    ->where('status','selesai')
                                    ->count();
            $countSuratKegiatan = $suratKegiatanList->count();
            $nomorSurat = $this->generateNomorSuratKegiatan(['selesai']);
            if($countSuratKegiatan < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat kegiatan mahasiswa tidak ditemukan!');
            }
            if(Session::get('jabatan') == 'dekan'){
                $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::where('status','diterima')->count();
                $disposisiList = PengajuanSuratKegiatanMahasiswa::orderByDesc('created_at')->whereIn('status',['diterima','disposisi dekan','disposisi wakil dekan I','disposisi wakil dekan II','disposisi wakil dekan III','surat dibuat'])->paginate($perPage);
                $tandaTanganList = PengajuanSuratKegiatanMahasiswa::orderByDesc('created_at')->where('status','menunggu tanda tangan')->paginate($perPage);
                $countDisposisi = $disposisiList->count();
                $countTandaTangan = $tandaTanganList->count();
                $countAllTandaTangan = PengajuanSuratKegiatanMahasiswa::where('status','menunggu tanda tangan')->count();
                return view('user.'.$this->segmentUser.'.surat_kegiatan_mahasiswa',compact('countAllDisposisi','disposisiList','countDisposisi','perPage','countAllTandaTangan','tandaTanganList','countTandaTangan','suratKegiatanList','countSuratKegiatan','countAllSuratKegiatan','nomorSurat'));
            }else if(Session::get('jabatan') == 'wd1'){
                $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::where('status','disposisi dekan')->count();
                $disposisiList = PengajuanSuratKegiatanMahasiswa::orderByDesc('updated_at')->whereIn('status',['disposisi dekan','disposisi wakil dekan I','disposisi wakil dekan II','disposisi wakil dekan III','surat dibuat','menunggu tanda tangan'])->paginate($perPage);
            }else if(Session::get('jabatan') == 'wd2'){
                $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::where('status','disposisi wakil dekan I')->count();
                $disposisiList = PengajuanSuratKegiatanMahasiswa::orderByDesc('updated_at')->whereIn('status',['disposisi wakil dekan I','disposisi wakil dekan II','disposisi wakil dekan III','surat dibuat','menunggu tanda tangan'])->paginate($perPage);
            }else if(Session::get('jabatan') == 'wd3'){
                $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::where('status','disposisi wakil dekan II')->count();
                $disposisiList = PengajuanSuratKegiatanMahasiswa::orderByDesc('updated_at')->whereIn('status',['disposisi wakil dekan II','disposisi wakil dekan III','surat dibuat','menunggu tanda tangan'])->paginate($perPage);
            }
            $countDisposisi = $disposisiList->count();
            return view('user.'.$this->segmentUser.'.surat_kegiatan_mahasiswa',compact('countAllDisposisi','disposisiList','countDisposisi','perPage','suratKegiatanList','countSuratKegiatan','countAllSuratKegiatan','nomorSurat'));
        }else{
            return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
        }
    }

    public function show(SuratKegiatanMahasiswa $suratKegiatan){
        return view('user.pegawai.detail_surat_kegiatan_mahasiswa',compact('suratKegiatan'));
    }

    public function create(){
        $userList = $this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $ormawaList = Ormawa::pluck('nama','id')->toArray();
        return view('user.'.$this->segmentUser.'.tambah_surat_kegiatan_mahasiswa',compact('kodeSurat','userList','nomorSuratBaru','ormawaList'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'nomor_surat_permohonan_kegiatan'=>'required|string',
            'nama_kegiatan'=>'required|string',
            'file_surat_permohonan_kegiatan'=>'required|image|mimes:jpg,jpeg,bmp,png|max:1024',
            'lampiran_panitia'=>'required|string',
            'ormawa'=>'required|numeric'
        ]);
        $input = $request->all();
        DB::beginTransaction();
        try {
            $input['status'] = 'diterima';
            $mahasiswa = PimpinanOrmawa::where('id_ormawa',$request->ormawa)->where('jabatan','sekretaris')->first();
            $user = User::where('nip',Session::get('nip'))->first();
            $dekan = User::where('jabatan','dekan')->where('status_aktif','aktif')->first();
            $input['nim'] = $mahasiswa->nim;
            $input['nip'] = $user->nip;
            if ($request->has('file_surat_permohonan_kegiatan')) {
                $imageFieldName = 'file_surat_permohonan_kegiatan';
                $uploadPath = 'upload_surat_permohonan_kegiatan';
                $input[$imageFieldName] = $this->uploadImage($imageFieldName, $request, $uploadPath);
            }
            PengajuanSuratKegiatanMahasiswa::create($input);
            NotifikasiMahasiswa::create([
                'nim'=>$input['nim'],
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Surat Kegiatan Mahasiswa telah diterima.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa')
            ]);
            NotifikasiUser::create([
                'nip'=>$dekan->nip,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Disposisi surat kegiatan mahasiswa.',
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

    public function edit(SuratKegiatanMahasiswa $suratKegiatan){
        $userList[$suratKegiatan->nip] = 'DEKAN - '.$suratKegiatan->user->nama;
        $kodeSurat = $this->generateKodeSurat();
        $ormawaList = Ormawa::pluck('nama','id')->toArray();
        return view('user.'.$this->segmentUser.'.edit_surat_kegiatan_mahasiswa',compact('kodeSurat','userList','ormawaList','suratKegiatan'));
    }

    public function update(SuratKegiatanMahasiswaRequest $request, SuratKegiatanMahasiswa $suratKegiatan){
        $input = $request->all();
        if ($request->has('file_surat_permohonan_kegiatan')) {
            $imageFieldName = 'file_surat_permohonan_kegiatan';
            $uploadPath = 'upload_surat_permohonan_kegiatan';
            $this->deleteImage($imageFieldName,$suratKegiatan->pengajuanSuratKegiatanMahasiswa->file_surat_permohonan_kegiatan);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName, $request, $uploadPath);
        }
        $suratKegiatan->pengajuanSuratKegiatanMahasiswa->update($input);
        $suratKegiatan->update($input);
        $this->setFlashData('success','Berhasil','Surat kegiatan mahasiswa diubah');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function destroy(SuratKegiatanMahasiswa $suratKegiatan){
        $imageFieldName = 'file_surat_masuk'; 
        $this->deleteImage($imageFieldName,$suratKegiatan->file_surat_permohonan_kegiatan);
        $suratKegiatan->pengajuanSuratKegiatanMahasiswa->delete();
        $this->setFlashData('success','Berhasil','Surat kegiatan mahasiswa berhasil dihapus');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function tandaTanganKegiatan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
        }
        $suratKegiatan = SuratKegiatanMahasiswa::where('id_pengajuan_kegiatan',$request->id)->first();
        
        $suratKegiatan->pengajuanSuratKegiatanMahasiswa->update([
            'status'=>'selesai',
        ]);
        NotifikasiUser::create([
            'nip'=>$suratKegiatan->pengajuanSuratKegiatanMahasiswa->nip,
            'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
            'isi_notifikasi'=>'Surat Kegiatan Mahasiswa telah selesai.',
            'link_notifikasi'=>url('pegawai/surat-kegiatan-mahasiswa')
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$suratKegiatan->pengajuanSuratKegiatanMahasiswa->nim,
            'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
            'isi_notifikasi'=>'Surat Kegiatan Mahasiswa telah selesai.',
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa')
        ]);
        $this->setFlashData('success','Berhasil','Tanda tangan surat kegiatan mahasiswa berhasil');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function cetakSuratKegiatan(SuratKegiatanMahasiswa $suratKegiatan){
        $data = $suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->ormawa->nama.' - '.$suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",3,3);
        if(Session::has('nim')){
            if($suratKegiatan->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Kegiatan Mahasiswa','Anda telah mencetak surat kegiatan mahasiswa sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-kegiatan-mahasiswa');
            }
        }
        $jumlahCetak = ++$suratKegiatan->jumlah_cetak;
        $suratKegiatan->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_kegiatan_mahasiswa',compact('suratKegiatan','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-kegiatan-mahasiswa'.' - '.$suratKegiatan->created_at->format('dmY-Him').'.pdf');
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

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat kegiatan mahasiswa')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generateNomorSuratKegiatan($status){
        $suratKegiatanList = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan_kegiatan')
                            ->whereIn('status',$status)
                            ->get();
        $nomorSuratList = [];
        foreach ($suratKegiatanList as $suratKegiatan) {
            $nomorSuratList[$suratKegiatan->nomor_surat] = $suratKegiatan->nomor_surat.'/'.$suratKegiatan->kodeSurat->kode_surat.'/'.$suratKegiatan->created_at->year;
        }
        return $nomorSuratList;
    }
}
