<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranCutiRequest extends FormRequest
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
            $fileSuratPermohonanRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
            $fileKrsSebelumnyaRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
            $fileSlipUktRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
        }else{
            $fileSuratPermohonanRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
            $fileKrsSebelumnyaRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
            $fileSlipUktRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
        }
        return [
            'id_waktu_cuti'=>'required|numeric',
            'nim'=>'required|numeric',
            'file_surat_permohonan_cuti'=>$fileSuratPermohonanRules,
            'file_krs_sebelumnya'=>$fileKrsSebelumnyaRules,
            'file_slip_ukt'=>$fileSlipUktRules,
            'alasan_cuti'=>'required|string|max:100' 
        ];
    }
}
