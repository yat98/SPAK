<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanSuratKegiatanMahasiswaRequest extends FormRequest
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
            $fileSuratPermohonanRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048'; 
            $fileProposalKegiatanRules = 'sometimes|mimes:pdf|max:2048';
        }else{
            $fileSuratPermohonanRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:2048'; 
            $fileProposalKegiatanRules = 'required|mimes:pdf|max:2048';
        }
        return [
            'id_ormawa'=>'required|numeric',
            'nomor_surat_permohonan_kegiatan'=>'required|string',
            'nama_kegiatan'=>'required|string',
            'file_surat_permohonan_kegiatan'=>$fileSuratPermohonanRules,
            'file_proposal_kegiatan'=>$fileProposalKegiatanRules,
        ];
    }
}
