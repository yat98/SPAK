<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanSuratRekomendasiRequest extends FormRequest
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
            'nim'=>'required',
            'nama_kegiatan'=>'required',
            'tempat_kegiatan'=>'required',
            'tanggal_awal_kegiatan'=>'required',
            'tanggal_akhir_kegiatan'=>'required',
        ];
    }
}
