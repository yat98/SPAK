<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use App\KodeSurat;
use App\NotifikasiUser;
use App\SuratKeterangan;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SuratKeteranganRequest;

class SuratKeteranganKelakuanBaikController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        
        $countAllSurat = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                          ->where('jenis_surat','surat keterangan kelakuan baik')
                                          ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                          ->count();
        
        $countAllVerifikasi = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                                        ->where('status','verifikasi kasubag')
                                                        ->count();

        return view('user.'.$this->segmentUser.'.surat_keterangan_kelakuan_baik',compact('perPage','countAllSurat','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;
                                   
        $countAllPengajuan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                                       ->whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                            ->where('jenis_surat','surat keterangan kelakuan baik')
                                            ->whereNotIn('status',['diajukan'])
                                            ->count();
                                                                         
        return view($this->segmentUser.'.surat_keterangan_kelakuan_baik',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                            ->where('status','verifikasi kabag')
                                            ->count();
                                            
        $countAllSurat = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                          ->where('jenis_surat','surat keterangan kelakuan baik')
                                          ->where('status','selesai')
                                          ->count();
        
        $countAllTandaTangan = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                            ->where('jenis_surat','surat keterangan kelakuan baik')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.pimpinan.surat_keterangan_kelakuan_baik',compact('countAllVerifikasi','perPage','countAllSurat','countAllTandaTangan'));
    }

    public function create(){
        if(!$this->isKodeSuratAktifExists() || !$this->isKodeSuratExists() || !$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
        }
        $nomorSuratBaru = $this->generateNomorSuratbaru();
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->pluck('kode_surat','id');
        $mahasiswa = $this->generateMahasiswa();
        $tahunAkademik = $this->generateAllTahunAkademik();
        return view('user.'.$this->segmentUser.'.tambah_surat_keterangan_kelakuan_baik',compact('mahasiswa','tahunAkademik','kodeSurat','nomorSuratBaru'));
    }

    public function edit(SuratKeterangan $suratKeterangan){
        $mahasiswa = $this->generateMahasiswa();
        $kodeSurat[$suratKeterangan->KodeSurat->id] = $suratKeterangan->kodeSurat->kode_surat;
        $tahunAkademik[$suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->id] = $suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->semester); 
        return view('user.'.$this->segmentUser.'.edit_surat_keterangan_kelakuan_baik',compact('suratKeterangan','mahasiswa','kodeSurat','tahunAkademik'));
    }

    public function update(SuratKeteranganRequest $request,SuratKeterangan $suratKeterangan){
        $input = $request->all();
        DB::beginTransaction();
        try{
            $suratKeterangan->pengajuanSuratKeterangan->update($input);;
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Pengajuan surat keterangan gagal diubah.');
        }

        try{
            $suratKeterangan->update($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan gagal diubah.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat keterangan kelakuan baik mahasiswa dengan nim '.$input['nim'].' berhasil diubah');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
    }

    public function destroy(SuratKeterangan $suratKeterangan){
        $suratKeterangan->pengajuanSuratKeterangan->delete();
        $suratKeterangan->delete();
        $this->setFlashData('success','Berhasil','Surat keterangan kelakuan baik dengan nomor surat B/'.$suratKeterangan->nomor_surat.'/'.$suratKeterangan->kodeSurat->kode_surat.'/'.$suratKeterangan->created_at->year.' berhasil dihapus');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
    }

    public function createSurat(PengajuanSuratKeterangan $pengajuanSuratKeterangan){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
        }
        $jenisSurat = ucwords($pengajuanSuratKeterangan->jenis_surat);
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view('operator.tambah_pengajuan_surat_keterangan',compact('pengajuanSuratKeterangan','jenisSurat','nomorSuratBaru','userList','kodeSurat'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat',
            'nip'=>'required',
        ]);

        $pengajuanSuratKeterangan = PengajuanSuratKeterangan::findOrFail($request->id_pengajuan);

        $user = User::where('status_aktif','aktif')
                      ->where('jabatan','kasubag kemahasiswaan')
                      ->first();

        $input = $request->all();
        $input['id_pengajuan'] = $pengajuanSuratKeterangan->id;
        
        DB::beginTransaction();
        try{
            SuratKeterangan::create($input);

            $pengajuanSuratKeterangan->update([
                'status'=>'verifikasi kasubag'
            ]);

            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratKeterangan->nim,
                'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
                'isi_notifikasi'=>'Surat keterangan kelakuan baik telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/surat-keterangan-kelakuan-baik')
            ]);

            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
                'isi_notifikasi'=>'Verifikasi surat keterangan kelakuan baik mahasiswa dengan nama '.$pengajuanSuratKeterangan->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-keterangan-kelakuan-baik')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat keterangan kelakuan baik gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat keterangan kelakuan baik mahasiswa dengan nama '.$pengajuanSuratKeterangan->mahasiswa->nama.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
    }

    public function tolakPengajuan(Request $request, PengajuanSuratKeterangan $pengajuanSuratKeterangan){
        $keterangan = $request->keterangan ?? '-';
        DB::beginTransaction();
        try{
            $pengajuanSuratKeterangan->update([
                'status'=>'ditolak',
                'keterangan'=>$keterangan,
            ]);
            NotifikasiMahasiswa::create([
                'nim'=>$pengajuanSuratKeterangan->nim,
                'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
                'isi_notifikasi'=>'Pengajuan surat keterangan kelakuan baik di tolak.',
                'link_notifikasi'=>url('mahasiswa/surat-keterangan-kelakuan-baik')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat keterangan kelakuan baik gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan kelakuan baik mahasiswa dengan nama '.$pengajuanSuratKeterangan->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
    }

    public function cetak(SuratKeterangan $suratKeterangan){
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratKeterangan->pengajuanSuratKeterangan->nim){
                abort(404);
            }

            if($suratKeterangan->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat keterangan kelakuan baik sebanyak 3 kali.');
                return redirect('mahasiswa/surat-keterangan-kelakuan-baik');
            }
        }

        $data = $suratKeterangan->pengajuanSuratKeterangan->nim.' - '.$suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama.' - '.$suratKeterangan->pengajuanSuratKeterangan->mahasiswa->prodi->nama_prodi;
        $qrCode = \DNS2D::getBarcodeHTML($data, "QRCODE",5,5);
        
        if(isset(Auth::user()->id) || isset(Auth::user()->nim)){
            if(Auth::user()->bagian == 'front office' || isset(Auth::user()->nim)){
                $jumlahCetak = ++$suratKeterangan->jumlah_cetak;
                $suratKeterangan->update([
                    'jumlah_cetak'=>$jumlahCetak
                ]);
            }
        }

        $pdf = PDF::loadview('surat.surat_keterangan_kelakuan_baik',compact('suratKeterangan','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream($suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama.' - '.$suratKeterangan->created_at->format('dmY-Him').'.pdf');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
        }

        $suratKeterangan = SuratKeterangan::findOrFail($request->id);
        
        $suratKeterangan->pengajuanSuratKeterangan->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::create([
            'nim'=>$suratKeterangan->pengajuanSuratKeterangan->nim,
            'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
            'isi_notifikasi'=>'Surat keterangan kelakuan baik telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-keterangan-kelakuan-baik')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat keterangan kelakuan baik berhasil');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
    }

    private function isKodeSuratAktifExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }
}
