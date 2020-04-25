<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratKeteranganRequest extends FormRequest
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
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_keterangan,nomor_surat,'.$this->get('nomor_surat').',nomor_surat';
        }else{
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_keterangan,nomor_surat';
        }
        return [
            'nomor_surat'=>$nomorSuratRules,
            'nim'=>'required|numeric',
            'nip'=>'sometimes|numeric',
            'id_tahun_akademik'=>'required|numeric',
            'id_kode_surat'=>'required|numeric',
            'jenis_surat'=>'required|string|in:surat keterangan aktif kuliah,surat keterangan kelakuan baik',
            'jumlah_cetak'=>'sometimes|numeric',
            'status'=>'sometimes|in:ditolak,diajukan,selesai',
        ];
    }
}
