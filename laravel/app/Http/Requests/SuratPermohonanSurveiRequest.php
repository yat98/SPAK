<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratPermohonanSurveiRequest extends FormRequest
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
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat,'.$this->get('id_pengajuan').',id_pengajuan|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat';
        }else{
            $fileRekomendasiJurusanRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_keterangan_lulus,nomor_surat|unique:surat_permohonan_pengambilan_material,nomor_surat|unique:surat_permohonan_survei,nomor_surat|unique:surat_rekomendasi_penelitian,nomor_surat|unique:surat_permohonan_pengambilan_data_awal,nomor_surat';

        }
        return [
            'nim'=>'required',
            'file_rekomendasi_jurusan'=>$fileRekomendasiJurusanRules,
            'kepada'=>'required|string',
            'mata_kuliah'=>'required|string',
            'id_kode_surat'=>'required',
            'nip'=>'required',
            'nomor_surat'=>$nomorSuratRules,
            'data_survei'=>'required|string'
        ];
    }
}
