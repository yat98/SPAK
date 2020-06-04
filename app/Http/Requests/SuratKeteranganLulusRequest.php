<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratKeteranganLulusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
            if($this->method() == 'PATCH' || $this->method() == 'PUT'){
                $fileRekomendasiJurusanRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
                $fileBeritaAcaraUjianRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
            }else{
                $fileRekomendasiJurusanRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
                $fileBeritaAcaraUjianRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
            }
            return [
                'nim'=>'required',
                'id_kode_surat'=>'required',
                'file_rekomendasi_jurusan'=>$fileRekomendasiJurusanRules,
                'file_berita_acara_ujian'=>$fileBeritaAcaraUjianRules,
                'tanggal_wisuda'=>'required|date',
                'ipk'=>'required',
                'nip'=>'required',
            ];
    }
}
