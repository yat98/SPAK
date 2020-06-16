<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\User;
use App\KodeSurat;
use App\SuratKeterangan;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SuratKeteranganRequest;

class SuratKeteranganKelakuanBaikController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $tahunAkademik = $this->generateAllTahunAkademik();
        $suratKeteranganList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan kelakuan baik')
                                        ->paginate($perPage,['*'],'page');
        
        $pengajuanSuratKeteranganList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                            ->whereNotIn('status',['selesai'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
        
        $countAllSuratKeterangan = $suratKeteranganList->count();
        $countSuratKeterangan = $suratKeteranganList->count();

        $countPengajuanSuratKeterangan = $pengajuanSuratKeteranganList->count();
        $countAllPengajuanSuratKeterangan = $pengajuanSuratKeteranganList->count();

        $nomorSurat = $this->generateNomorSurat('surat keterangan kelakuan baik');

        return view('user.'.$this->segmentUser.'.surat_keterangan_kelakuan_baik',compact('tahunAkademik','suratKeteranganList','countAllSuratKeterangan','countSuratKeterangan','mahasiswa','perPage','nomorSurat','countPengajuanSuratKeterangan','pengajuanSuratKeteranganList','countAllPengajuanSuratKeterangan'));
    }

    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $pengajuanSuratKeteranganList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                            ->where('nim',Session::get('nim'))
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
        $countAllPengajuan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')->where('nim',Session::get('nim'))->count();
        $countPengajuanSuratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                            ->where('nim',Session::get('nim'))
                                            ->count();
        return view($this->segmentUser.'.pengajuan_surat_keterangan_kelakuan_baik',compact('countAllPengajuan','countPengajuanSuratKeterangan','perPage','pengajuanSuratKeteranganList'));
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['tahun_akademik']) || isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $tahunAkademik = $this->generateAllTahunAkademik();
            $nomorSurat = $this->generateNomorSurat('surat keterangan kelakuan baik');
            $countAllSuratKeterangan = SuratKeterangan::all()->count();
            $countAllPengajuanSuratKeterangan = $pengajuanSuratKeteranganList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                                    ->whereNotIn('status',['selesai'])
                                                    ->orderByDesc('created_at')
                                                    ->orderBy('status')
                                                    ->count();
            $suratKeteranganList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                            ->where('jenis_surat','surat keterangan kelakuan baik');
            (isset($keyword['nomor_surat'])) ? $suratKeteranganList = $suratKeteranganList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratKeteranganList = $suratKeteranganList->where('nim',$keyword['keywords']):'';
            (isset($keyword['tahun_akademik'])) ? $suratKeteranganList = $suratKeteranganList->where('id_tahun_akademik',$keyword['tahun_akademik']):'';
            $suratKeteranganList = $suratKeteranganList->paginate($perPage)->appends($request->except('page'));
            $countSuratKeterangan = $suratKeteranganList->count();
            if($countSuratKeterangan < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat keterangan kelakuan baik tidak ditemukan!');
            }

            $pengajuanSuratKeteranganList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')
                                                    ->whereNotIn('status',['selesai'])
                                                    ->orderByDesc('created_at')
                                                    ->orderBy('status')
                                                    ->paginate($perPage,['*'],'page_pengajuan');
           
            $countPengajuanSuratKeterangan = $pengajuanSuratKeteranganList->count();

            return view('user.'.$this->segmentUser.'.surat_keterangan_kelakuan_baik',compact('tahunAkademik','suratKeteranganList','countAllSuratKeterangan','countSuratKeterangan','mahasiswa','perPage','nomorSurat','countPengajuanSuratKeterangan','pengajuanSuratKeteranganList','countAllPengajuanSuratKeterangan'));
        }else{
            return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
        }
    }

    public function create(){
        if(!$this->isKodeSuratKelakuanExists() || !$this->isKodeSuratExists() || !$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
        }
        $nomorSuratBaru = $this->generateNomorSuratbaru();
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->pluck('kode_surat','id');
        $mahasiswa = $this->generateMahasiswa();
        $tahunAkademik = $this->generateAllTahunAkademik();
        return view('user.'.$this->segmentUser.'.tambah_surat_keterangan_kelakuan_baik',compact('mahasiswa','tahunAkademik','kodeSurat','nomorSuratBaru'));
    }

    public function store(SuratKeteranganRequest $request){
        $input = $request->all();
        $input['nip'] = Session::get('nip');

        DB::beginTransaction();
        try{
            $pengajuanSuratKeterangan = PengajuanSuratKeterangan::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Pengajuan surat keterangan gagal ditambahkan.');
        }

        try{
             $input['id_pengajuan_surat_keterangan'] = $pengajuanSuratKeterangan->id;
             SuratKeterangan::create($input);
             $pengajuanSuratKeterangan->update([
                 'status'=>'selesai'
             ]);
             NotifikasiMahasiswa::create([
                'nim'=>$request->nim,
                'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
                'isi_notifikasi'=>'Surat keterangan kelakuan baik telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-kelakuan-baik')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat keterangan kelakuan baik mahasiswa dengan nim '.$input['nim'].' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
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
        if(!$this->isKodeSuratKelakuanExists() || !$this->isKodeSuratExists() || !$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-kelakuan-baik');
        }
        $jenisSurat = ucwords($pengajuanSuratKeterangan->jenis_surat);
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $userList =$this->generatePimpinan();
        $kodeSurat = $this->generateKodeSurat();
        return view('user.pegawai.buat_surat_keterangan',compact('pengajuanSuratKeterangan','jenisSurat','nomorSuratBaru','userList','kodeSurat'));
    }

    public function storeSurat(Request $request){
        $this->validate($request,[
            'id_pengajuan_surat_keterangan'=>'required',
            'id_kode_surat'=>'required',
            'nomor_surat'=>'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat',
            'nip'=>'required',
        ]);

        $pengajuanSuratKeterangan = PengajuanSuratKeterangan::findOrFail($request->id_pengajuan_surat_keterangan);
        $input = [
            'id_pengajuan_surat_keterangan'=>$pengajuanSuratKeterangan->id,
            'nomor_surat'=>$request->nomor_surat,
            'id_kode_surat'=>$request->id_kode_surat,
            'nip' => $request->nip,
        ];
        SuratKeterangan::create($input);
        $pengajuanSuratKeterangan->update([
            'status'=>'selesai'
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pengajuanSuratKeterangan->nim,
            'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
            'isi_notifikasi'=>'Surat keterangan kelakuan baik telah selesai di buat.',
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-kelakuan-baik')
        ]);
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
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-kelakuan-baik')
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
        if(Session::has('nim')){
            if($suratKeterangan->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Keterangan','Anda telah mencetak surat keterangan kelakuan baik sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-keterangan-kelakuan-baik');
            }
        }
        $jumlahCetak = ++$suratKeterangan->jumlah_cetak;
        $suratKeterangan->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_keterangan_kelakuan_baik',compact('suratKeterangan'))->setPaper('a4', 'potrait');
        return $pdf->stream($suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama.' - '.$suratKeterangan->created_at->format('dmY-Him').'.pdf');
    }

    private function isKodeSuratKelakuanExists(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->first();
        if(empty($kodeSurat)){
            $this->setFlashData('info','Kode Surat Aktif Tidak Ada','Aktifkan kode surat terlebih dahulu!');
            return false;
        }
        return true;
    }

    private function generateKodeSurat(){
        $kode = [];
        $kodeSuratList = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->get();
        foreach ($kodeSuratList as $kodeSurat) {
            $kode[$kodeSurat->id] = $kodeSurat->kode_surat;
        }
        return $kode;
    }

    private function generatePimpinan(){
        $user = [];
        $pimpinan = User::where('jabatan','kasubag kemahasiswaan')->where('status_aktif','aktif')->first();
        $user[$pimpinan->nip] = strtoupper($pimpinan->jabatan).' - '.$pimpinan->nama;
        return $user;
    }
}
