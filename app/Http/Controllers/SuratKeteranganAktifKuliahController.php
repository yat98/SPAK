<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\KodeSurat;
use App\Mahasiswa;
use App\SuratTugas;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use App\NotifikasiMahasiswa;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SuratKeteranganRequest;

class SuratKeteranganAktifKuliahController extends Controller
{
    public function index()
    {
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $tahunAkademik = $this->generateAllTahunAkademik();
        $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                        ->orderByDesc('surat_keterangan.updated_at')
                                        ->where('jenis_surat','surat keterangan aktif kuliah')
                                        ->paginate($perPage,['*'],'page');

        $pengajuanSuratKeteranganAktifList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                            ->whereNotIn('status',['selesai'])
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
        
        $countAllSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $countSuratKeteranganAktif = $suratKeteranganAktifList->count();

        $countPengajuanSuratKeterangan = $pengajuanSuratKeteranganAktifList->count();
        $countAllPengajuanSuratKeterangan = $pengajuanSuratKeteranganAktifList->count();

        $nomorSurat = $this->generateNomorSurat('surat keterangan aktif kuliah');

        return view('user.'.$this->segmentUser.'.surat_keterangan_aktif_kuliah',compact('tahunAkademik','suratKeteranganAktifList','countAllSuratKeteranganAktif','countSuratKeteranganAktif','mahasiswa','perPage','nomorSurat','countPengajuanSuratKeterangan','pengajuanSuratKeteranganAktifList','countAllPengajuanSuratKeterangan'));
    }

    public function indexMahasiswa(){
        $perPage = $this->perPage;
        $pengajuanSuratKeteranganAktifList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                            ->where('nim',Session::get('nim'))
                                            ->orderByDesc('created_at')
                                            ->orderBy('status')
                                            ->paginate($perPage,['*'],'page_pengajuan');
        $countAllPengajuan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')->where('nim',Session::get('nim'))->count();
        $countPengajuanSuratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                            ->where('nim',Session::get('nim'))
                                            ->count();
        return view($this->segmentUser.'.pengajuan_surat_keterangan_aktif_kuliah',compact('countAllPengajuan','countPengajuanSuratKeterangan','perPage','pengajuanSuratKeteranganAktifList'));
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['tahun_akademik']) || isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $tahunAkademik = $this->generateAllTahunAkademik();
            $nomorSurat = $this->generateNomorSurat('surat keterangan aktif kuliah');
            $countAllSuratKeteranganAktif = SuratKeterangan::all()->count();
            $countAllPengajuanSuratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                                    ->whereNotIn('status',['selesai'])
                                                    ->orderByDesc('created_at')
                                                    ->orderBy('status')
                                                    ->count();
            $suratKeteranganAktifList = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan_surat_keterangan','=','pengajuan_surat_keterangan.id')
                                            ->where('jenis_surat','surat keterangan aktif kuliah');
            (isset($keyword['nomor_surat'])) ? $suratKeteranganAktifList = $suratKeteranganAktifList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratKeteranganAktifList = $suratKeteranganAktifList->where('nim',$keyword['keywords']):'';
            (isset($keyword['tahun_akademik'])) ? $suratKeteranganAktifList = $suratKeteranganAktifList->where('id_tahun_akademik',$keyword['tahun_akademik']):'';
            $suratKeteranganAktifList = $suratKeteranganAktifList->paginate($perPage)->appends($request->except('page'));
            $countSuratKeteranganAktif = $suratKeteranganAktifList->count();
            if($countSuratKeteranganAktif < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat keterangan aktif kuliah tidak ditemukan!');
            }

            $pengajuanSuratKeteranganAktifList = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                                    ->whereNotIn('status',['selesai'])
                                                    ->orderByDesc('created_at')
                                                    ->orderBy('status')
                                                    ->paginate($perPage,['*'],'page_pengajuan');
           
            $countPengajuanSuratKeterangan = $pengajuanSuratKeteranganAktifList->count();

            return view('user.'.$this->segmentUser.'.surat_keterangan_aktif_kuliah',compact('tahunAkademik','suratKeteranganAktifList','countAllSuratKeteranganAktif','countSuratKeteranganAktif','mahasiswa','perPage','nomorSurat','pengajuanSuratKeteranganAktifList','countPengajuanSuratKeterangan','countAllPengajuanSuratKeterangan'));
        }else{
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }
    }

    public function create(){
        if(!$this->isKodeSuratAktifExists() || !$this->isKodeSuratExists() || !$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }
        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->pluck('kode_surat','id');
        $mahasiswa = $this->generateMahasiswa();
        $tahunAkademik = $this->generateAllTahunAkademik();
        return view('user.'.$this->segmentUser.'.tambah_surat_keterangan_aktif_kuliah',compact('mahasiswa','tahunAkademik','kodeSurat','nomorSuratBaru'));
    }

    public function store(SuratKeteranganRequest $request){
        $input = $request->all();

        $statusMahasiswa = Mahasiswa::where('nim',$request->nim)->with(['tahunAkademik'=>function($query) use($request){
            $query->where('id',$request->id_tahun_akademik);
        }])->get()->first();

        if(count($statusMahasiswa->tahunAkademik) == 0){
            $statusMahasiswaTerakhir = Mahasiswa::where('nim',$request->nim)->with(['tahunAkademik'=>function($query){
                $query->orderByDesc('created_at');
            }]);
            if(count($statusMahasiswaTerakhir->first()->tahunAkademik) == 0){
                $this->setFlashData('info','Data Status Mahasiswa','Data status mahasiswa dengan nim '.$request->nim.' belum ada');
            }else{
                $status = $statusMahasiswaTerakhir->first()->tahunAkademik->first()->pivot->status;
                $this->setFlashData('info','Data Status Mahasiswa','Data status mahasiswa terakhir, mahasiswa dengan nim '.$request->nim.' adalah '.$status);
            }    
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }else{
            $status = $statusMahasiswa->tahunAkademik->first()->pivot->status;
            $tahunAkademik = $statusMahasiswa->tahunAkademik->first()->tahun_akademik.' - '.ucwords($statusMahasiswa->tahunAkademik->first()->semester);
            if($status != 'aktif'){
                $this->setFlashData('info','Data Status Mahasiswa','Status mahasiswa dengan nama '.strtolower($statusMahasiswa->nama).' pada tahun akademik '.$tahunAkademik.' adalah '.$status);
                return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
            }
        }

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
                'judul_notifikasi'=>'Surat Keterangan Aktif Kuliah',
                'isi_notifikasi'=>'Surat keterangan aktif kuliah telah selesai di buat.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-aktif-kuliah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Menambahkan Data','Surat keterangan gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Surat keterangan aktif kuliah mahasiswa dengan nim '.$input['nim'].' berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function show($id)
    {
        //
    }

    public function edit(SuratKeterangan $suratKeterangan){
        $mahasiswa = $this->generateMahasiswa();
        $kodeSurat[$suratKeterangan->KodeSurat->id] = $suratKeterangan->kodeSurat->kode_surat;
        $tahunAkademik[$suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->id] = $suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->semester); 
        return view('user.'.$this->segmentUser.'.edit_surat_keterangan_aktif_kuliah',compact('suratKeterangan','mahasiswa','kodeSurat','tahunAkademik'));
    }

    public function update(SuratKeteranganRequest $request,SuratKeterangan $suratKeterangan){
        $input = $request->all();
        $statusMahasiswa = Mahasiswa::where('nim',$request->nim)->with(['tahunAkademik'=>function($query) use($request){
            $query->where('id',$request->id_tahun_akademik);
        }])->get()->first();    
        
        if(count($statusMahasiswa->tahunAkademik) == 0){
            $statusMahasiswaTerakhir = Mahasiswa::where('nim',$request->nim)->with(['tahunAkademik'=>function($query){
                $query->orderByDesc('created_at');
            }]);
            if(count($statusMahasiswaTerakhir->first()->tahunAkademik) == 0){
                $this->setFlashData('info','Data Status Mahasiswa','Data status mahasiswa dengan nim '.$request->nim.' belum ada');
            }else{
                $status = $statusMahasiswaTerakhir->first()->tahunAkademik->first()->pivot->status;
                $this->setFlashData('info','Data Status Mahasiswa','Data status mahasiswa terakhir, mahasiswa dengan nim '.$request->nim.' adalah '.$status);
            }    
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }else{
            $status = $statusMahasiswa->tahunAkademik->first()->pivot->status;
            $tahunAkademik = $statusMahasiswa->tahunAkademik->first()->tahun_akademik.' - '.ucwords($statusMahasiswa->tahunAkademik->first()->semester);
            if($status != 'aktif'){
                $this->setFlashData('info','Data Status Mahasiswa','Status mahasiswa dengan nama '.strtolower($statusMahasiswa->nama).' pada tahun akademik '.$tahunAkademik.' adalah '.$status);
                return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
            }
        }

        DB::beginTransaction();
        try{
            $suratKeterangan->pengajuanSuratKeterangan->update($input);;
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Pengajuan surat keterangan gagal diubah.');
        }

        try{
            $suratKeterangan->update($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat keterangan gagal diubah.');
        }

        DB::commit();

        $this->setFlashData('success','Berhasil','Surat keterangan aktif kuliah mahasiswa dengan nim '.$input['nim'].' berhasil diubah');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function destroy(SuratKeterangan $suratKeterangan){
        $suratKeterangan->pengajuanSuratKeterangan->delete();
        $suratKeterangan->delete();
        $this->setFlashData('success','Berhasil','Surat keterangan aktif kuliah dengan nomor surat B/'.$suratKeterangan->nomor_surat.'/'.$suratKeterangan->kodeSurat->kode_surat.'/'.$suratKeterangan->created_at->year.' berhasil dihapus');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function cetak(SuratKeterangan $suratKeterangan){
        if(Session::has('nim')){
            if($suratKeterangan->jumlah_cetak >= 3){
                $this->setFlashData('info','Cetak Surat Keterangan','Anda telah mencetak surat keterangan aktif kuliah sebanyak 3 kali.');
                return redirect('mahasiswa/pengajuan/surat-keterangan-aktif-kuliah');
            }
        }
        $jumlahCetak = ++$suratKeterangan->jumlah_cetak;
        $suratKeterangan->update([
            'jumlah_cetak'=>$jumlahCetak
        ]);
        $pdf = PDF::loadview('surat.surat_keterangan_aktif_kuliah',compact('suratKeterangan'))->setPaper('a4', 'potrait');
        return $pdf->stream($suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama.' - '.$suratKeterangan->created_at->format('dmY-Him').'.pdf');
    }

    public function tandaTangan(Request $request){
        if(!$this->isKodeSuratAktifExists() || !$this->isKodeSuratExists() || !$this->isTandaTanganExists()){
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }
        $pengajuanSuratKeterangan = PengajuanSuratKeterangan::findOrFail($request->id);

        $statusMahasiswa = Mahasiswa::where('nim',$pengajuanSuratKeterangan->nim)->with(['tahunAkademik'=>function($query) use($pengajuanSuratKeterangan){
            $query->where('id',$pengajuanSuratKeterangan->id_tahun_akademik);
        }])->get()->first();

        if(count($statusMahasiswa->tahunAkademik) == 0){
            $statusMahasiswaTerakhir = Mahasiswa::where('nim',$pengajuanSuratKeterangan->nim)->with(['tahunAkademik'=>function($query){
                $query->orderByDesc('created_at');
            }]);
            if(count($statusMahasiswaTerakhir->first()->tahunAkademik) == 0){
                $this->setFlashData('info','Data Status Mahasiswa','Data status mahasiswa dengan nim '.$pengajuanSuratKeterangan->nim.' belum ada');
            }else{
                $status = $statusMahasiswaTerakhir->first()->tahunAkademik->first()->pivot->status;
                $this->setFlashData('info','Data Status Mahasiswa','Data status mahasiswa terakhir, mahasiswa dengan nim '.$pengajuanSuratKeterangan->nim.' adalah '.$status);
            }    
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }else{
            $status = $statusMahasiswa->tahunAkademik->first()->pivot->status;
            $tahunAkademik = $statusMahasiswa->tahunAkademik->first()->tahun_akademik.' - '.ucwords($statusMahasiswa->tahunAkademik->first()->semester);
            if($status != 'aktif'){
                $this->setFlashData('info','Data Status Mahasiswa','Status mahasiswa dengan nama '.strtolower($statusMahasiswa->nama).' pada tahun akademik '.$tahunAkademik.' adalah '.$status);
                return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
            }
        }

        $nomorSuratBaru = $this->generateNomorSuratBaru();
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->first();
        $input = [
            'id_pengajuan_surat_keterangan'=>$pengajuanSuratKeterangan->id,
            'nomor_surat'=>$nomorSuratBaru,
            'id_kode_surat'=>$kodeSurat->id,
            'nip' => Session::get('nip'),
        ];
        SuratKeterangan::create($input);
        $pengajuanSuratKeterangan->update([
            'status'=>'selesai'
        ]);
        NotifikasiMahasiswa::create([
            'nim'=>$pengajuanSuratKeterangan->nim,
            'judul_notifikasi'=>'Pengajuan Surat Keterangan',
            'isi_notifikasi'=>'Surat keterangan aktif kuliah telah selesai di buat.',
            'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-aktif-kuliah')
        ]);
        $this->setFlashData('success','Berhasil','Tanda tangan surat keterangan aktif kuliah mahasiswa dengan nama '.$pengajuanSuratKeterangan->mahasiswa->nama.' berhasil');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
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
                'judul_notifikasi'=>'Pengajuan Surat Keterangan',
                'isi_notifikasi'=>'Pengajuan surat keterangan aktif kuliah di tolak.',
                'link_notifikasi'=>url('mahasiswa/pengajuan/surat-keterangan-aktif-kuliah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Mengubah Data','Surat keterangan aktif kuliah gagal ditolak.');
        }
        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan aktif kuliah mahasiswa dengan nama '.$pengajuanSuratKeterangan->mahasiswa->nama.' ditolak');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
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
