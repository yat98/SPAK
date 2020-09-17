<?php

namespace App\Http\Controllers;

use Session;
use Storage;
use DataTables;
use Carbon\Carbon;
use App\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SuratMasukRequest;

class SuratMasukController extends Controller
{
    public function index(){
        $perPage = $this->perPage;
        
        $countAllSurat  = SuratMasuk::count();
  
        return view('user.'.$this->segmentUser.'.surat_masuk',compact('perPage','countAllSurat'));
    }

    public function indexOperator(){
        $perPage = $this->perPage;
        
        $countAllSurat  = SuratMasuk::count();
  
        return view($this->segmentUser.'.surat_masuk',compact('perPage','countAllSurat'));
    }

    public function indexPimpinan(){
        $perPage = $this->perPage;
        
        $countAllSurat  = SuratMasuk::count();
  
        return view('user.'.$this->segmentUser.'.surat_masuk',compact('perPage','countAllSurat'));
    }

    public function show(SuratMasuk $suratMasuk){
        $surat = collect($suratMasuk);
        $surat->put('tanggal_surat_masuk',$suratMasuk->created_at->isoFormat('D MMMM Y'));
        $surat->put('created',$suratMasuk->created_at->isoFormat('D MMMM Y H:m:s'));
        $surat->put('updated',$suratMasuk->updated_at->isoFormat('D MMMM Y H:m:s'));
        $surat->put('nama_file',explode('.',$suratMasuk->file_surat_masuk)[0]);
        $surat->put('link_file',asset('upload_surat_masuk/'.$suratMasuk->file_surat_masuk));
        return $surat->toJson();
    }

    public function create(){
        $formFile = true;
        if(isset(Auth::user()->id)){
            return view($this->segmentUser.'.tambah_surat_masuk',compact('formFile'));
        }else{
            return view('user.'.$this->segmentUser.'.tambah_surat_masuk',compact('formFile'));
        }
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
        if(isset(Auth::user()->id)){
            return view($this->segmentUser.'.edit_surat_masuk',compact('suratMasuk','formFile'));
        }else{
            return view('user.'.$this->segmentUser.'.edit_surat_masuk',compact('suratMasuk','formFile'));
        }
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

    public function getAllSuratMasuk(){
        return DataTables::of(SuratMasuk::all())
                    ->addColumn('aksi', function ($data) {
                        return $data->id;
                    })
                    ->editColumn("tanggal_surat_masuk", function ($data) {
                        return $data->tanggal_surat_masuk->isoFormat('D MMMM YYYY');
                    })
                    ->make(true);
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
