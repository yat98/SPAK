<?php

namespace App\Http\Controllers;

use Session;
use App\KodeSurat;
use App\Mahasiswa;
use App\StatusMahasiswa;
use App\SuratKeterangan;
use Illuminate\Http\Request;
use App\Http\Requests\SuratKeteranganRequest;

class SuratKeteranganController extends Controller
{
    public function indexSuratKeteranganAktifKuliah(){
        $perPage = $this->perPage;
        $mahasiswa = $this->generateMahasiswa();
        $tahunAkademik = $this->generateAllTahunAkademik();
        $suratKeteranganAktifList = SuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah')
                                        ->orderByDesc('updated_at')                                
                                        ->paginate($perPage);
        $countAllSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $countSuratKeteranganAktif = $suratKeteranganAktifList->count();
        $nomorSurat = $this->generateNomorSurat();
        return view('user.'.$this->segmentUser.'.surat_keterangan_aktif_kuliah',compact('tahunAkademik','suratKeteranganAktifList','countAllSuratKeteranganAktif','countSuratKeteranganAktif','mahasiswa','perPage','nomorSurat'));
    }

    public function createSuratKeteranganAktifKuliah(){
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->first();
        $nomorSuratTerakhir = SuratKeterangan::orderByDesc('nomor_surat')->where('status','selesai')->first();
        $nomorSuratBaru = (empty($nomorSuratTerakhir)) ? 1 : ++$nomorSuratTerakhir->nomor_surat;
        $kodeSurat = KodeSurat::where('jenis_surat','surat keterangan')->where('status_aktif','aktif')->pluck('kode_surat','id');
        $mahasiswa = $this->generateMahasiswa();
        $tahunAkademik = $this->generateTahunAkademikSemester();
        return view('user.'.$this->segmentUser.'.tambah_surat_keterangan_aktif_kuliah',compact('mahasiswa','tahunAkademik','kodeSurat','nomorSuratBaru'));
    }

    public function showSuratKeteranganAktifKuliah(SuratKeterangan $suratKeterangan){
        $surat = collect($suratKeterangan->load(['kodeSurat','mahasiswa.prodi.jurusan','tahunAkademik']));
        $tanggal = $suratKeterangan->created_at->format('d M Y - H:i:m');
        $surat->put('user',collect($suratKeterangan->user->first()));
        $surat->put('created',$tanggal);
        $surat->transform(function($item, $key) {
            if(is_string($item)){
                return ucwords($item);
            }
            return $item;
        });
        return $surat->toJson();
    }

    public function storeSuratKeteranganAktifKuliah(SuratKeteranganRequest $request){
        $input = $request->all();
        $statusMahasiswa = Mahasiswa::where('nim',$request->nim)->with(['tahunAkademik'=>function($query) use($request){
            $query->where('id',$request->id_tahun_akademik);
        }])->get()->first();

        if(count($statusMahasiswa->tahunAkademik) == 0){
            $this->setFlashData('info','Data Status Mahasiswa','Data status mahasiswa dengan nim '.$request->nim.' belum ada');
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
        $input['jumlah_cetak'] = 0;
        $input['status'] = 'selesai';
        $this->setFlashData('success','Berhasil','Surat keterangan aktif kuliah mahasiswa dengan nim '.$input['nim'].' berhasil ditambahkan');
        SuratKeterangan::create($input);
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function editSuratKeteranganAktifKuliah(SuratKeterangan $suratKeterangan){
        $mahasiswa = $this->generateMahasiswa();
        $kodeSurat[$suratKeterangan->KodeSurat->id] = $suratKeterangan->kodeSurat->kode_surat;
        $tahunAkademik[$suratKeterangan->tahunAkademik->id] = $suratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeterangan->tahunAkademik->semester); 
        return view('user.'.$this->segmentUser.'.edit_surat_keterangan_aktif_kuliah',compact('suratKeterangan','mahasiswa','kodeSurat','tahunAkademik'));
    }

    public function updateSuratKeteranganAktifKuliah(SuratKeteranganRequest $request,SuratKeterangan $suratKeterangan){
        $input = $request->all();
        $statusMahasiswa = Mahasiswa::where('nim',$request->nim)->with(['tahunAkademik'=>function($query) use($request){
            $query->where('id',$request->id_tahun_akademik);
        }])->get()->first();

        if(count($statusMahasiswa->tahunAkademik) == 0){
            $this->setFlashData('info','Data Status Mahasiswa','Data status mahasiswa dengan nim '.$request->nim.' belum ada');
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }else{
            $status = $statusMahasiswa->tahunAkademik->first()->pivot->status;
            $tahunAkademik = $statusMahasiswa->tahunAkademik->first()->tahun_akademik.' - '.ucwords($statusMahasiswa->tahunAkademik->first()->semester);
            if($status != 'aktif'){
                $this->setFlashData('info','Data Status Mahasiswa','Status mahasiswa dengan nama '.strtolower($statusMahasiswa->nama).' pada tahun akademik '.$tahunAkademik.' adalah '.$status);
                return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
            }
        }
        $this->setFlashData('success','Berhasil','Surat keterangan aktif kuliah mahasiswa dengan nim '.$input['nim'].' berhasil diubah');
        $suratKeterangan->update($input);
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function destroySuratKeteranganAktifKuliah(SuratKeterangan $suratKeterangan){
        $suratKeterangan->delete();
        $this->setFlashData('success','Berhasil','Surat keterangan aktif kuliah dengan nomor surat B/'.$suratKeterangan->nomor_surat.'/'.$suratKeterangan->kodeSurat->kode_surat.'/'.$suratKeterangan->created_at->year.' berhasil dihapus');
        return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
    }

    public function searchSuratKeteranganAktifKuliah(Request $request){
        $keyword = $request->all();
        if(isset($keyword['tahun_akademik']) || isset($keyword['keywords']) || isset($keyword['nomor_surat'])){
            $perPage = $this->perPage;
            $mahasiswa = $this->generateMahasiswa();
            $tahunAkademik = $this->generateAllTahunAkademik();
            $nomorSurat = $this->generateNomorSurat();
            $countAllSuratKeteranganAktif = SuratKeterangan::all()->count();
            $suratKeteranganAktifList = SuratKeterangan::where('jenis_surat','surat keterangan aktif kuliah');
            (isset($keyword['nomor_surat'])) ? $suratKeteranganAktifList = $suratKeteranganAktifList->where('nomor_surat',$keyword['nomor_surat']):'';
            (isset($keyword['keywords'])) ? $suratKeteranganAktifList = $suratKeteranganAktifList->where('nim',$keyword['keywords']):'';
            (isset($keyword['tahun_akademik'])) ? $suratKeteranganAktifList = $suratKeteranganAktifList->where('id_tahun_akademik',$keyword['tahun_akademik']):'';
            $suratKeteranganAktifList = $suratKeteranganAktifList->paginate($perPage)->appends($request->except('page'));
            $countSuratKeteranganAktif = $suratKeteranganAktifList->count();
            if($countSuratKeteranganAktif < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat keterangan aktif kuliah tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_keterangan_aktif_kuliah',compact('tahunAkademik','suratKeteranganAktifList','countAllSuratKeteranganAktif','countSuratKeteranganAktif','mahasiswa','perPage','nomorSurat'));
        }else{
            return redirect($this->segmentUser.'/surat-keterangan-aktif-kuliah');
        }
    }

    public function cetakSuratKeteranganAktifKuliah(SuratKeterangan $suratKeterangan){
        return view('surat.surat_keterangan_aktif_kuliah');
    }

    private function checkTandaTanganIsExists(){
        $nip = Session::get('nip');
        $tandaTangan = User::where('nip',$nip)->first();
        if(empty($tandaTangan)){
            $this->setFlashData('info','Tanda Tangan Kosong','Tambahkan tanda tangan anda terlebih dahulu!');
            return false;
        }
        return true;
    }
}
