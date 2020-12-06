<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use App\KodeSurat;
use App\Mahasiswa;
use App\SuratTugas;
use App\NotifikasiUser;
use App\SuratDispensasi;
use App\SuratKeterangan;
use Milon\Barcode\DNS2D;
use App\SuratRekomendasi;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SuratKeteranganRequest;

class SuratKeteranganAktifKuliahController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                                        ->where('status','verifikasi kasubag')
                                                        ->count();

        $countAllSurat = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                          ->where('jenis_surat','surat keterangan aktif kuliah')
                                          ->whereIn('status',['selesai','verifikasi kabag','menunggu tanda tangan'])
                                          ->count();

        return view('user.'.$this->segmentUser.'.surat_keterangan_aktif_kuliah',compact('countAllSurat','perPage','countAllVerifikasi'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;
                                   
        $countAllPengajuan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                                       ->whereIn('status',['diajukan','ditolak']);

        if(Auth::user()->bagian == 'front office'){
            $countAllPengajuan = $countAllPengajuan->where('id_operator',Auth::user()->id);                             
        }

        $countAllPengajuan = $countAllPengajuan->count();

        $countAllSurat = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                            ->where('jenis_surat','surat keterangan aktif kuliah')
                                            ->whereNotIn('status',['diajukan'])
                                            ->count();
                                                                         
        return view($this->segmentUser.'.surat_keterangan_aktif_kuliah',compact('countAllPengajuan','perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;

        $countAllVerifikasi = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                            ->where('status','verifikasi kabag')
                                            ->count();

        $countAllSurat = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                            ->where('jenis_surat','surat keterangan aktif kuliah')
                                            ->where('status','selesai')
                                            ->count();
        
        $countAllTandaTangan = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                            ->where('jenis_surat','surat keterangan aktif kuliah')
                                            ->where('status','menunggu tanda tangan')
                                            ->where('nip',Auth::user()->nip)
                                            ->count();

        return view('user.pimpinan.surat_keterangan_aktif_kuliah',compact('countAllVerifikasi','perPage','countAllSurat','countAllTandaTangan'));
    }

    public function cetak(SuratKeterangan $suratKeterangan){
        if(isset(Auth::user()->nim)){
            if(Auth::user()->nim != $suratKeterangan->pengajuanSuratKeterangan->nim){
                abort(404);
            }

            if($suratKeterangan->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat','Anda telah mencetak surat keterangan aktif kuliah sebanyak 3 kali.');
                return redirect('mahasiswa/surat-keterangan-aktif-kuliah');
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

        $pdf = PDF::loadview('surat.surat_keterangan_aktif_kuliah',compact('suratKeterangan','qrCode'))->setPaper('a4', 'potrait');
        return $pdf->stream($suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama.' - '.$suratKeterangan->created_at->format('dmY-Him').'.pdf');
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan'=>'required',
            'id_operator'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat|unique:surat_keterangan_bebas_perlengkapan,nomor_surat',
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
            
            NotifikasiUser::create([
                'nip'=>$user->nip,
                'judul_notifikasi'=>'Surat Keterangan Aktif Kuliah',
                'isi_notifikasi'=>'Verifikasi surat keterangan aktif kuliah mahasiswa dengan nama '.$pengajuanSuratKeterangan->mahasiswa->nama,
                'link_notifikasi'=>url('pegawai/surat-keterangan-aktif-kuliah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat keterangan aktif kuliah gagal ditambahkan.');
        }

        DB::commit();
        
        $this->setFlashData('success','Berhasil','Surat keterangan aktif kuliah mahasiswa dengan nama '.$pengajuanSuratKeterangan->mahasiswa->nama.' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function createSurat(PengajuanSuratKeterangan $pengajuanSuratKeterangan){
        if(!$this->isKodeSuratExists()){
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }
        $jenisSurat = ucwords($pengajuanSuratKeterangan->jenis_surat);
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generateTandaTanganKemahasiswaan();
        $kodeSurat = KodeSurat::pluck('kode_surat','id');
        return view('operator.tambah_surat_keterangan',compact('pengajuanSuratKeterangan','jenisSurat','nomorSuratBaru','userList','kodeSurat'));
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
                'judul_notifikasi'=>'Surat Keterangan Aktif Kuliah',
                'isi_notifikasi'=>'Pengajuan surat keterangan aktif kuliah di tolak.',
                'link_notifikasi'=>url('mahasiswa/surat-keterangan-aktif-kuliah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal','Pengajuan surat keterangan aktif kuliah gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan aktif kuliah mahasiswa dengan nama '.$pengajuanSuratKeterangan->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function tandaTangan(Request $request){
        if(!$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }

        $suratKeterangan = SuratKeterangan::findOrFail($request->id);
        
        $suratKeterangan->pengajuanSuratKeterangan->update([
            'status'=>'selesai',
        ]);

        NotifikasiMahasiswa::create([
            'nim'=>$suratKeterangan->pengajuanSuratKeterangan->nim,
            'judul_notifikasi'=>'Surat Keterangan Aktif Kuliah',
            'isi_notifikasi'=>'Surat keterangan aktif kuliah telah di tanda tangani.',
            'link_notifikasi'=>url('mahasiswa/surat-keterangan-aktif-kuliah')
        ]);

        $this->setFlashData('success','Berhasil','Tanda tangan surat keterangan aktif kuliah berhasil');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }
}
