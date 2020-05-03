<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\SuratMasuk;
use Illuminate\Http\Request;
use App\Http\Requests\SuratMasukRequest;
use Storage;

class SuratMasukController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        $nomorSurat = SuratMasuk::pluck('nomor_surat','nomor_surat');
        $instansi = $this->generateinstansi();
        $suratMasukList = SuratMasuk::orderByDesc('created_at')->paginate($perPage);
        $countAllSuratMasuk  = SuratMasuk::all()->count();
        $countSuratMasuk = count($suratMasukList);
        return view('user.'.$this->segmentUser.'.surat_masuk',compact('perPage','suratMasukList','countAllSuratMasuk','countSuratMasuk','perPage','nomorSurat','instansi'));
    }

    public function show(SuratMasuk $suratMasuk){
        $surat = collect($suratMasuk);
        $surat->put('tanggal_surat_masuk',$suratMasuk->created_at->isoFormat('D MMMM Y'));
        $surat->put('created',$suratMasuk->created_at->format('d F Y H:i:s'));
        $surat->put('updated',$suratMasuk->updated_at->format('d F Y H:i:s'));
        $surat->put('nama_file',explode('.',$suratMasuk->file_surat_masuk)[0]);
        $surat->put('link_file',asset('upload_surat_masuk/'.$suratMasuk->file_surat_masuk));
        return $surat->toJson();
    }

    public function search(Request $request){
        $keyword = $request->all();
        if(isset($keyword['keywords']) || isset($keyword['instansi'])){
            $nomorSurat = SuratMasuk::pluck('nomor_surat','nomor_surat');
            $instansi = $this->generateinstansi();
            $perPage = $this->perPage;
            $countAllSuratMasuk  = SuratMasuk::all()->count();
            $nomor = $keyword['keywords'] != null ? $keyword['keywords'] : '';
            $suratMasukList = SuratMasuk::where('nomor_surat','like',"%$nomor%");
            (isset($keyword['instansi'])) ? $suratMasukList = $suratMasukList->where('instansi',$keyword['instansi']):'';
            $suratMasukList = $suratMasukList->paginate($perPage)->appends($request->except('page'));
            $countSuratMasuk = count($suratMasukList);
            if($countSuratMasuk < 1){
                $this->setFlashData('search','Hasil Pencarian','Surat Masuk tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.surat_masuk',compact('perPage','suratMasukList','countAllSuratMasuk','countSuratMasuk','perPage','nomorSurat','instansi'));
        }else{
            return redirect($this->segmentUser.'/surat-masuk');
        }
    }

    public function create(){
        $formFile = true;
        return view('user.'.$this->segmentUser.'.tambah_surat_masuk',compact('formFile'));
    }

    public function store(SuratMasukRequest $request, SuratMasuk $suratMasuk){
        $input = $request->all();
        if($request->has('file_surat_masuk')){
            $imageFieldName = 'file_surat_masuk'; 
            $uploadPath = 'upload_surat_masuk';
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        SuratMasuk::create($input);
        $this->setFlashData('success','Berhasil','Surat masuk berhasil ditambahkan');
        return redirect($this->segmentUser.'/surat-masuk');
    }

    public function edit(Request $request,SuratMasuk $suratMasuk){
        $formFile = false;
        if($request->upload){
            $formFile = true;
        }
        return view('user.'.$this->segmentUser.'.edit_surat_masuk',compact('suratMasuk','formFile'));
    }

    public function update(SuratMasukRequest $request, SuratMasuk $suratMasuk){
        $input = $request->all();
        if($request->has('file_surat_masuk')){
            $imageFieldName = 'file_surat_masuk'; 
            $uploadPath = 'upload_surat_masuk';    
            $this->deleteImage($imageFieldName,$suratMasuk->file_surat_masuk);
            $input[$imageFieldName] = $this->uploadImage($imageFieldName,$request,$uploadPath);
        }
        $suratMasuk->update($input);
        $this->setFlashData('success','Berhasil','Surat masuk berhasil diubah');
        return redirect($this->segmentUser.'/surat-masuk');
    }

    public function destroy(SuratMasuk $suratMasuk){
        $imageFieldName = 'file_surat_masuk'; 
        $this->deleteImage($imageFieldName,$suratMasuk->file_surat_masuk);
        $suratMasuk->delete();
        $this->setFlashData('success','Berhasil','Surat masuk berhasil dihapus');
        return redirect($this->segmentUser.'/surat-masuk');
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

    private function generateinstansi(){
        $instansi = [];
        $suratMasukList = SuratMasuk::select('instansi')->groupBy('instansi')->get();
        foreach ($suratMasukList as $suratMasuk) {
            $instansi[$suratMasuk->instansi] = $suratMasuk->instansi;
        }
        return $instansi;
    }
}
