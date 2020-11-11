<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use Storage;
use App\User;
use App\Ormawa;
use DataTables;
use App\KodeSurat;
use App\NotifikasiUser;
use App\PimpinanOrmawa;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\SuratKegiatanMahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\PengajuanSuratKegiatanMahasiswa;
use App\DaftarDisposisiSuratKegiatanMahasiswa;
use App\Http\Requests\SuratKegiatanMahasiswaRequest;
use App\Http\Requests\PengajuanSuratKegiatanMahasiswaRequest;

class SuratKegiatanMahasiswaController extends Controller
{
    public function index(){

        $perPage = $this->perPage;
        
        $countAllSurat = PengajuanSuratKegiatanMahasiswa::join('surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                                          ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                                          ->count();
        
        $countAllVerifikasi = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                                      ->where('pengajuan_surat_kegiatan_mahasiswa.status','verifikasi kasubag')
                                                      ->count();

        $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::whereIn('status',['disposisi kasubag','disposisi selesai'])
                                                      ->count();

        return view('user.'.$this->segmentUser.'.surat_kegiatan_mahasiswa',compact('perPage','countAllSurat','countAllVerifikasi','countAllDisposisi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;
                                   
        $countAllPengajuan = PengajuanSuratKegiatanMahasiswa::whereIn('status',['diajukan','ditolak'])
                                ->count();

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        if(Auth::user()->bagian == 'subbagian kemahasiswaan'){
            $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::whereIn('status',['disposisi','disposisi dekan','disposisi wd1','disposisi wd2','disposisi wd3','disposisi kabag','disposisi kasubag','disposisi selesai'])
                                    ->count();
        }else{
            $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::count();
        }

        $countAllSurat = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                            ->whereNotIn('pengajuan_surat_kegiatan_mahasiswa.status',['diajukan'])
                                            ->count();
                                                                         
        return view($this->segmentUser.'.surat_kegiatan_mahasiswa',compact('countAllPengajuan','countAllDisposisi','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;
        $countAllDisposisi = '';

        $countAllVerifikasi = PengajuanSuratKegiatanMahasiswa::where('status','verifikasi kabag')
                                ->count();

        if(Auth::user()->jabatan != 'kabag tata usaha'){
            $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::whereIn('status',['disposisi dekan','disposisi wd1','disposisi wd2','disposisi wd3','disposisi kabag','disposisi kasubag','disposisi selesai','verifikasi kasubag','verifikasi kabag'])
                                        ->count();
        }else{
            $countAllDisposisi = PengajuanSuratKegiatanMahasiswa::whereIn('status',['disposisi dekan','disposisi wd1','disposisi wd2','disposisi wd3','disposisi kabag','disposisi kasubag','disposisi selesai'])
                                        ->count();
        }

        $countAllSurat = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                            ->where('status','selesai')
                            ->count();
        
        $countAllTandaTangan = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                ->where('status','menunggu tanda tangan')
                                ->where('nip',Auth::user()->nip)
                                ->count();

        return view('user.pimpinan.surat_kegiatan_mahasiswa',compact('countAllVerifikasi','countAllDisposisi','perPage','countAllSurat','countAllTandaTangan'));
    }

    public function getAllSurat(){
        $suratKegiatan = PengajuanSuratKegiatanMahasiswa::join('ormawa','pengajuan_surat_kegiatan_mahasiswa.id_ormawa','=','ormawa.id')
                                ->join('surat_kegiatan_mahasiswa','pengajuan_surat_kegiatan_mahasiswa.id','=','surat_kegiatan_mahasiswa.id_pengajuan')
                                ->select(['pengajuan_surat_kegiatan_mahasiswa.*','ormawa.nama']) 
                                ->with(['suratKegiatanMahasiswa.kodeSurat','ormawa']);

        if(isset(Auth::user()->id)){
            $suratKegiatan = $suratKegiatan->whereIn('pengajuan_surat_kegiatan_mahasiswa.status',['verifikasi kasubag','verifikasi kabag','menunggu tanda tangan','selesai']);
        }else if(isset(Auth::user()->nip)){
            if(Auth::user()->jabatan == 'kasubag kemahasiswaan'){
                $suratKegiatan = $suratKegiatan->whereIn('pengajuan_surat_kegiatan_mahasiswa.status',['selesai','verifikasi kabag','menunggu tanda tangan']);
            } else{
                $suratKegiatan = $suratKegiatan->where('pengajuan_surat_kegiatan_mahasiswa.status','selesai');
            }
        }
        
        return DataTables::of($suratKegiatan)
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();
                        })
                        ->editColumn("status", function ($data) {
                            switch($data->status){
                                case 'disposisi wd1':
                                    return 'Disposisi WD1';
                                    break;
                                case 'disposisi wd2':
                                    return 'Disposisi WD2';
                                    break;
                                case 'disposisi wd3':
                                    return 'Disposisi WD3';
                                    break;
                                default:
                                    return ucwords($data->status);
                                    break;
                            }
                        })
                        ->make(true);
    }
        
    public function show(SuratKegiatanMahasiswa $suratKegiatan){
        if(isset(Auth::user()->id) || isset(Auth::user()->nim)) return view($this->segmentUser.'.detail_surat_kegiatan_mahasiswa',compact('suratKegiatan'));
        return view('user.'.$this->segmentUser.'.detail_surat_kegiatan_mahasiswa',compact('suratKegiatan'));
    }

    public function progress(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $data = collect($pengajuanKegiatan);
        $data->put('status',ucwords($pengajuanKegiatan->status));

        if($pengajuanKegiatan->status == 'selesai'){
            $tanggalSelesai = $pengajuanKegiatan->suratKegiatanMahasiswa->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalSelesai);
        }else if($pengajuanKegiatan->status == 'ditolak'){
            $tanggalDitolak = $pengajuanKegiatan->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggalDitolak);
        }else{
            $tanggal = $pengajuanKegiatan->updated_at->isoFormat('D MMMM Y HH:mm:ss');
            $data->put('tanggal',$tanggal);
        }

        return json_encode($data->toArray(),JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    public function createSurat(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view('operator.tambah_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan','nomorSuratBaru','userList','kodeSurat'));
    }

    public function storeSurat(SuratKegiatanMahasiswaRequest $request){
        $input = $request->all();
        $pengajuanKegiatan = PengajuanSuratKegiatanMahasiswa::findOrFail($request->id_pengajuan);
        $user = User::where('status_aktif','aktif')
                          ->where('jabatan','kasubag kemahasiswaan')
                          ->first();

        DB::beginTransaction();
        try {
            SuratKegiatanMahasiswa::create($input);
            
            $pengajuanKegiatan->update([
                'status'=>'verifikasi kasubag'
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Verifikasi surat kegiatan mahasiswa.',
                'link_notifikasi'=>url('pegawai/surat-kegiatan-mahasiswa')
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

    
    public function tolakPengajuan(Request $request, PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanKegiatan->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan
            ]);

            foreach($pengajuanKegiatan->ormawa->pimpinanOrmawa as $pimpinanOrmawa){
                NotifikasiMahasiswa::create([
                    'nim'=>$pimpinanOrmawa->nim,
                    'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                    'isi_notifikasi'=>'Pengajuan surat Kegiatan Mahasiswa telah ditolak.',
                    'link_notifikasi'=>url('mahasiswa/surat-kegiatan-mahasiswa')
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat keterangan kegiatan mahasiswa gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat kegiatan mahasiswa ditolak');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
        }

        $suratKegiatan = SuratKegiatanMahasiswa::findOrFail($request->id);
        
        $suratKegiatan->pengajuanSuratKegiatanMahasiswa->update([
            'status'=>'selesai',
        ]);

        foreach($suratKegiatan->pengajuanSuratKegiatanMahasiswa->ormawa->pimpinanOrmawa as $mahasiswa){
            NotifikasiMahasiswa::create([
                'nim'=>$mahasiswa->nim,
                'judul_notifikasi'=>'Surat Kegiatan Mahasiswa',
                'isi_notifikasi'=>'Surat kegiatan mahasiswa telah di tanda tangani.',
                'link_notifikasi'=>url('mahasiswa/surat-kegiatan-mahasiswa')
            ]);
        }

        $this->setFlashData('success','Berhasil','Tanda tangan surat kegiatan mahasiswa berhasil');
        return redirect($this->segmentUser.'/surat-kegiatan-mahasiswa');
    }

    public function cetak(SuratKegiatanMahasiswa $suratKegiatan){
        if(isset(Auth::user()->nim)){
            $nim = $suratKegiatan->pengajuanSuratKegiatanMahasiswa->ormawa->pimpinanOrmawa->map(function ($mahasiswa) {
                return strtoupper($mahasiswa->nim);
            })->toArray();

            if(!in_array(Auth::user()->nim,$nim)){ 
                abort(404);
            }

            if($suratKegiatan->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat kegiatan mahasiswa sebanyak 3 kali.');
                return redirect('mahasiswa/surat-kegiatan-mahasiswa');
            }
        }

        $data = $suratKegiatan->pengajuanSuratKegiatanMahasiswa->ormawa->nama.' - '.$suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",3,3);

        if (isset(Auth::user()->id) || isset(Auth::user()->nim)) {
            if (Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)) {
                $jumlahCetak = ++$suratKegiatan->jumlah_cetak;
                $suratKegiatan->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }
        }   

        $pdf = PDF::loadview('surat.surat_kegiatan_mahasiswa',compact('suratKegiatan','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream('surat-kegiatan-mahasiswa'.' - '.$suratKegiatan->created_at->format('dmY-Him').'.pdf');
    }

    public function cetakDisposisi(PengajuanSuratKegiatanMahasiswa $pengajuanKegiatan){
        $daftarDisposisi = DaftarDisposisiSuratKegiatanMahasiswa::where('id_disposisi',$pengajuanKegiatan->id)
                                ->orderBy('created_at')
                                ->get();
        if($daftarDisposisi->count() > 0){
            $dekan = DaftarDisposisiSuratKegiatanMahasiswa::join('user','daftar_disposisi_surat_kegiatan_mahasiswa.nip','=','user.nip')
                        ->where('id_disposisi',$pengajuanKegiatan->id)
                        ->where('user.jabatan','dekan')
                        ->select(['daftar_disposisi_surat_kegiatan_mahasiswa.*'])
                        ->orderBy('created_at')
                        ->first();
            $dekan = $dekan->user;
        }else{
            $dekan = User::where('jabatan','dekan')
                           ->where('status_aktif','aktif')
                           ->first();
        }
        
        $pdf = PDF::loadview('lampiran.lampiran_disposisi_surat_kegiatan_mahasiswa',compact('pengajuanKegiatan','daftarDisposisi','dekan'))->setPaper('a4', 'potrait');
        return $pdf->stream('lampiran-disposisi'.' - '.$pengajuanKegiatan->created_at->format('dmY-Him').'.pdf');
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
}
