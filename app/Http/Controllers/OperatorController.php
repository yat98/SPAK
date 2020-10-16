<?php

namespace App\Http\Controllers;

use Session;
use DataTables;
use App\Operator;
use App\WaktuCuti;
use Carbon\Carbon;
use App\SuratMasuk;
use App\SuratTugas;
use App\TahunAkademik;
use App\PendaftaranCuti;
use App\SuratDispensasi;
use App\SuratKeterangan;
use App\SuratRekomendasi;
use Illuminate\Http\Request;
use App\SuratKegiatanMahasiswa;
use App\SuratPersetujuanPindah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\OperatorRequest;

class OperatorController extends Controller
{
    public function  index(){
        $perPage = $this->perPage;
        $countAllOperator = Operator::count();
        return view('user.'.$this->segmentUser.'.operator',compact('countAllOperator','perPage'));
    }

    public function indexOperator(){
        $perPageDashboard = $this->perPageDashboard;

        $tgl = Carbon::now();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        $waktuCuti = isset($tahunAkademikAktif) ? WaktuCuti::where('id_tahun_akademik',$tahunAkademikAktif->id)->first():null;

        $countAllSuratMasuk  = SuratMasuk::count();

        $countAllSuratAktif = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                               ->where('jenis_surat','surat keterangan aktif kuliah')
                                               ->whereNotIn('status',['diajukan'])
                                               ->count();

        $countAllSuratBaik = SuratKeterangan::join('pengajuan_surat_keterangan','surat_keterangan.id_pengajuan','=','pengajuan_surat_keterangan.id')
                                              ->where('jenis_surat','surat keterangan kelakuan baik')
                                              ->whereNotIn('status',['diajukan'])
                                              ->count();
        
        $countAllSuratDispensasi = SuratDispensasi::join('pengajuan_surat_dispensasi','surat_dispensasi.id_pengajuan','=','pengajuan_surat_dispensasi.id_surat_masuk')
                                                    ->whereNotIn('status',['diajukan'])
                                                    ->count();

        $countAllSuratRekomendasi = SuratRekomendasi::join('pengajuan_surat_rekomendasi','surat_rekomendasi.id_pengajuan','=','pengajuan_surat_rekomendasi.id')
                                                      ->whereNotIn('status',['diajukan'])
                                                      ->count();

        $countAllSuratTugas = SuratTugas::join('pengajuan_surat_tugas','surat_tugas.id_pengajuan','=','pengajuan_surat_tugas.id')
                                          ->whereNotIn('pengajuan_surat_tugas.status',['diajukan'])
                                          ->count();
        
        $countAllSuratPindah = SuratPersetujuanPindah::join('pengajuan_surat_persetujuan_pindah','surat_persetujuan_pindah.id_pengajuan','=','pengajuan_surat_persetujuan_pindah.id')
                                                       ->whereNotIn('status',['diajukan'])
                                                       ->count();

        $countAllSuratKegiatan = SuratKegiatanMahasiswa::join('pengajuan_surat_kegiatan_mahasiswa','surat_kegiatan_mahasiswa.id_pengajuan','=','pengajuan_surat_kegiatan_mahasiswa.id')
                                                        ->where('status','selesai')
                                                        ->count();
        
        $countAllWaktuCuti = WaktuCuti::count();

        $countAllPendaftaran = PendaftaranCuti::where('status','selesai')
                                                ->count();

        return view($this->segmentUser.'.dashboard',compact('perPageDashboard','tgl','tahunAkademikAktif','waktuCuti','countAllSuratMasuk','countAllSuratAktif','countAllSuratBaik','countAllSuratDispensasi','countAllSuratRekomendasi','countAllSuratTugas','countAllSuratPindah','countAllSuratKegiatan','countAllPendaftaran','countAllWaktuCuti'));
    }

    public function getAllOperator(){
        return DataTables::of(Operator::select(['*']))
                ->editColumn("bagian", function ($data) {
                    return ucwords($data->bagian);
                })        
                ->editColumn("status_aktif", function ($data) {
                    return ucwords($data->status_aktif);
                })
                ->addColumn('aksi', function ($data) {
                    return $data->id;
                })
                ->make(true);
    }

    public function getLimitOperator(){
        return DataTables::collection(Operator::all()->take(5)->sortByDesc('updated_at'))
                    ->editColumn("bagian", function ($data) {
                        return ucwords($data->bagian);
                    })        
                    ->editColumn("status_aktif", function ($data) {
                        return ucwords($data->status_aktif);
                    })
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->diffForHumans();
                    })
                    ->editColumn("updated_at", function ($data) {
                        return $data->updated_at->diffForHumans();
                    })
                    ->toJson();
    }

    public function create(){
        $formPassword = true;
        return view('user.'.$this->segmentUser.'.tambah_operator',compact('formPassword'));
    }

    public function store(OperatorRequest $request){
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data operator dengan nama '.strtolower($input['nama']).' berhasil ditambahkan');
        Operator::create($input);
        return redirect($this->segmentUser.'/operator');
    }

    public function show(Operator $operator){
        $data = collect($operator);
        $data->put('created_at',$operator->created_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('updated_at',$operator->updated_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('status_aktif',ucwords($operator->status_aktif));
        $data->put('bagian',ucwords($operator->bagian));

        return $data->toJson();
    }

    public function edit(Operator $operator){
        $formPassword = false;
        return view('user.'.$this->segmentUser.'.edit_operator',compact('operator','formPassword'));
    }

    public function update(OperatorRequest $request, Operator $operator){
        $input = $request->all();
        $operator->update($input);
        $this->setFlashData('success','Berhasil','Data operator '.strtolower($operator->nama).' berhasil diubah');
        return redirect($this->segmentUser.'/operator');
    }

    public function destroy(Operator $operator){
        $operator->delete();
        $this->setFlashData('success','Berhasil','Data operator '.$operator->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/operator');
    }

    public function profil(){
        $id = Auth::user()->id;
        $operator = Operator::findOrFail($id);
        $status = $operator->status_aktif;
        return view($this->segmentUser.'.profil',compact('operator','status'));
    }

    public function profilPassword(){
        return view($this->segmentUser.'.profil_password');
    }
    
    public function updateProfil(Request $request,Operator $operator){
        $this->validate($request,[
            'nama'=>'required|string',
            'username'=>'required|string',
        ]);
        $operator->update($request->all());
        $this->setFlashData('success','Berhasil','Profil berhasil diubah');
        return redirect($this->segmentUser);
    }

    public function updatePassword(Request $request){
        $id =  Auth::user()->id;
        $operator = Operator::findOrFail($id);  
        $this->validate($request,[
            'password_lama'=>function($attr,$val,$fail) use($operator){
                if (!Hash::check($val, $operator->password)) {
                    $fail('password lama tidak sesuai.');
                }
            },
            'password'=>'required|string|max:60|confirmed',
            'password_confirmation'=>'required|string|max:60'
       ]);
       $operator->update([
           'password'=>$request->password
       ]);
       Session::flush();
       $this->setFlashData('success','Berhasil','Password  berhasil diubah');
       return redirect($this->segmentUser);
    }
}
