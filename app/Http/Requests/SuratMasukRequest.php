<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratMasukRequest extends FormRequest
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
            $fileSuratMasukRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
        }else{
            $fileSuratMasukRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
        }
        return [
            'nomor_surat'=>'required|string|max:50',
            'perihal'=>'required|string|max:100',
            'instansi'=>'required|string|max:100',
            'tanggal_surat_masuk'=>'required|date_format:Y-m-d',
            'file_surat_masuk'=>$fileSuratMasukRules,
            'bagian'=>'required|string|in:subbagian kemahasiswaan,subbagian pendidikan dan pengajaran',
        ];
    }
}
