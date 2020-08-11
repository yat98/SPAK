<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use DataTables;
use App\Operator;
use App\Mahasiswa;
use Carbon\Carbon;
use App\TahunAkademik;
use App\NotifikasiUser;
use App\StatusMahasiswa;
use App\NotifikasiOperator;
use Illuminate\Http\Request;
use App\PengajuanSuratKeterangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PengajuanSuratKeteranganRequest;

class PengajuanSuratKeteranganController extends Controller
{
    public function createPengajuanKeteranganAktif(){
        if(!$this->isSuratAktifDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
        }

        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $tahunAkademik[$tahunAkademikAktif->id] = $tahunAkademikAktif->tahun_akademik.' - '.ucwords($tahunAkademikAktif->semester);

        if($tahunAkademikAktif !=  null){
            $status = StatusMahasiswa::where('status','aktif')->where('id_tahun_akademik',$tahunAkademikAktif->id)->where('nim',Auth::user()->nim)->first();
            if($status == null){
                $this->setFlashData('info','Pengajuan Gagal','Maaf anda tidak dapat membuat pengajuan surat keterangan aktif kuliah karena status anda tidak aktif');
                return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
            }
        }else{
            $this->setFlashData('info','Pengajuan Gagal','Tahun akademik belum aktif');
            return redirect('mahasiswa/pengajuan/surat-keterangan-aktif-kuliah');   
        }

        return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_aktif_kuliah',compact('tahunAkademik'));
    }

    public function createPengajuanKeteranganAktifOperator(){
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $tahunAkademik = $this->generateAllTahunAkademik();
        if($tahunAkademikAktif !=  null){
           $mahasiswa = $this->generateMahasiswa();
        }else{
            $this->setFlashData('info','Pengajuan Gagal','Tahun akademik belum aktif');
            return redirect('operator/pengajuan/surat-keterangan-aktif-kuliah');   
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_aktif_kuliah',compact('tahunAkademik','mahasiswa'));
    }

    public function createPengajuanKelakuanBaik(){
        if(!$this->isSuratKelakuanDiajukanExists()){
            return redirect($this->segmentUser.'/pengajuan/surat-keterangan-kelakuan-baik');
        }
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $tahunAkademikTerakhir = ($tahunAkademikAktif != null) ? $tahunAkademikAktif:TahunAkademik::orderByDesc('created_at')->first();
        $tahunAkademik = [];
        if($tahunAkademikTerakhir != null){
            $tahunAkademik[$tahunAkademikTerakhir->id] = $tahunAkademikTerakhir->tahun_akademik.' - '.ucwords($tahunAkademikTerakhir->semester);
        }
        return view($this->segmentUser.'.tambah_pengajuan_surat_keterangan_kelakuan_baik',compact('tahunAkademik'));
    }

    
    public function storePengajuanKelakuanBaik(PengajuanSuratKeteranganRequest $request){
        $input = $request->all();
        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
        }

        DB::beginTransaction();
        try{
            $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Keterangan Kelakuan Baik',
                'isi_notifikasi'=>'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan kelakuan baik.',
                'link_notifikasi'=>url('operator/surat-keterangan-kelakuan-baik')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan kelakuan baik gagal ditambahkan.');
        }

        try{ 
            PengajuanSuratKeterangan::create($input);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan kelakuan baik gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan kelakuan baik berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-kelakuan-baik');
    }

    public function storePengajuanKeteranganAktif(PengajuanSuratKeteranganRequest $request){
        $input = $request->all();
        $operator = Operator::where('bagian','subbagian kemahasiswaan')->where('status_aktif','aktif')->first();

        if(isset(Auth::user()->nim)){
            $mahasiswa = Mahasiswa::where('nim',Auth::user()->nim)->first();
            $isiNotifikasi = 'Mahasiswa dengan nama '.$mahasiswa->nama.' membuat pengajuan surat keterangan aktif kuliah.';
        } else if(isset(Auth::user()->id)){
            $mahasiswa = Mahasiswa::where('nim',$request->nim)->first();
            $isiNotifikasi = 'Front office membuat pengajuan surat keterangan aktif kuliah dengan nama mahasiswa '.$mahasiswa->nama;
        }

        DB::beginTransaction();
        try{ 
            $input['id_operator'] = Auth::user()->id;
            PengajuanSuratKeterangan::create($input);
            NotifikasiOperator::create([
                'id_operator'=>$operator->id,
                'judul_notifikasi'=>'Surat Keterangan Aktif Kuliah',
                'isi_notifikasi'=>$isiNotifikasi,
                'link_notifikasi'=>url('operator/surat-keterangan-aktif-kuliah')
            ]);
        }catch(Exception $e){
            DB::rollback();
            $this->setFlashData('error','Gagal Melakukan Pengajuan Surat','Pengajuan surat keterangan aktif kuliah gagal ditambahkan.');
        }

        DB::commit();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan aktif kuliah berhasil ditambahkan.');
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
    }

    public function getAllPengajuanAktif(){
        if(isset(Auth::user()->nim)){

        } else if(isset(Auth::user()->id)){
            return DataTables::of(PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                    ->where('id_operator',Auth::user()->id)
                                    ->whereNotIn('status',['selesai'])
                                    ->select('mahasiswa.nama','tahun_akademik.tahun_akademik','tahun_akademik.semester','pengajuan_surat_keterangan.*')
                                    ->join('mahasiswa','pengajuan_surat_keterangan.nim','=','mahasiswa.nim')
                                    ->join('tahun_akademik','pengajuan_surat_keterangan.id_tahun_akademik','=','tahun_akademik.id')
                                    ->with(['mahasiswa','tahunAkademik']))
                        ->addColumn('aksi', function ($data) {
                            return $data->id;
                        })
                        ->addColumn('tahun', function ($data) {
                            return $data->tahunAkademik->tahun_akademik.' - '.ucwords($data->tahunAkademik->semester);
                        })
                        ->editColumn("status", function ($data) {
                            return ucwords($data->status);
                        })
                        ->editColumn("created_at", function ($data) {
                            return $data->created_at->isoFormat('D MMMM YYYY hh:mm:ss');
                        })
                        ->addColumn("waktu_pengajuan", function ($data) {
                            return $data->created_at->diffForHumans();                            
                        })
                        ->make(true);
        }
    }

    public function show(PengajuanSuratKeterangan $pengajuanSurat){
        $data = collect($pengajuanSurat->load(['mahasiswa','tahunAkademik']));
        $data->put('created_at',$pengajuanSurat->created_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('updated_at',$pengajuanSurat->updated_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('status',ucwords($pengajuanSurat->status));
        $data->put('jenis_surat',ucwords($pengajuanSurat->jenis_surat));
        
        return $data->toJson();
    }

    public function edit(PengajuanSuratKeterangan $pengajuanSurat)
    {   
        $tahunAkademik = $this->generateAllTahunAkademik();
        $mahasiswa = $this->generateMahasiswa();
        return view($this->segmentUser.'.edit_pengajuan_surat_keterangan_aktif_kuliah',compact('tahunAkademik','mahasiswa','pengajuanSurat'));
    }

    public function update(PengajuanSuratKeteranganRequest $request, PengajuanSuratKeterangan $pengajuanSurat){
        $input = $request->all();
        $pengajuanSurat->update($input);
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan  aktif kuliah berhasil diubah');
        if($pengajuanSurat->jenis_surat == 'surat keterangan aktif kuliah'){
            return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
        }
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-kelakuan-baik');
    }

    public function destroy(PengajuanSuratKeterangan $pengajuanSurat)
    {
        $pengajuanSurat->delete();
        $this->setFlashData('success','Berhasil','Pengajuan surat keterangan  aktif kuliah berhasil dihapus');
        if($pengajuanSurat->jenis_surat == 'surat keterangan aktif kuliah'){
            return redirect($this->segmentUser.'/pengajuan/surat-keterangan-aktif-kuliah');
        }
        return redirect($this->segmentUser.'/pengajuan/surat-keterangan-kelakuan-baik');
    }
    
    private function isSuratAktifDiajukanExists(){
        $suratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')->where('nim',Auth::user()->nim)->where('status','diajukan')->exists();
        if($suratKeterangan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat keterangan aktif kuliah sementara diproses!');
            return false;
        }
        return true;
    }

    private function isSuratKelakuanDiajukanExists(){
        $suratKeterangan = PengajuanSuratKeterangan::where('jenis_surat','surat keterangan kelakuan baik')->where('nim',Auth::user()->nim)->where('status','diajukan')->exists();
        if($suratKeterangan){
            $this->setFlashData('info','Pengajuan Surat','Pengajuan surat keterangan kelakuan baik sementara diproses!');
            return false;
        }
        return true;
    }
}
