<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanSuratPermohonanSurveiRequest extends FormRequest
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
            $fileRekomendasiJurusanRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048'; 
        }else{
            $fileRekomendasiJurusanRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:2048'; 
        }
        return [
            'nim'=>'required',
            'file_rekomendasi_jurusan'=>$fileRekomendasiJurusanRules,
            'kepada'=>'required|string',
            'mata_kuliah'=>'required|string',
            'data_survei'=>'required|string'
        ];
    }
}
