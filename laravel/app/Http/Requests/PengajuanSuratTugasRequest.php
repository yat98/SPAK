<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanSuratTugasRequest extends FormRequest
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
            'nama_kegiatan'=>'required|string|max:100',
            'jenis_kegiatan'=>'required|string|max:100',
            'tempat_kegiatan'=>'required|string|max:100',
            'tanggal_awal_kegiatan'=>'required|date',
            'tanggal_akhir_kegiatan'=>'required|date',
        ];
    }
}
