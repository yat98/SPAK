<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanSuratKeteranganRequest extends FormRequest
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
        return [
            'nim'=>'required|numeric',
            'id_tahun_akademik'=>'required|numeric',
            'jenis_surat'=>'required|string|in:surat keterangan aktif kuliah,surat keterangan kelakuan baik,surat keterangan cuti',
        ];
    }
}
