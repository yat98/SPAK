<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WaktuCutiRequest extends FormRequest
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
            $idTahunAkademikRules = 'required|unique:waktu_cuti,id_tahun_akademik,'.$this->get('id');
        }else{
            $idTahunAkademikRules = 'required|unique:waktu_cuti,id_tahun_akademik';
        }
        return [
            'id_tahun_akademik'=>$idTahunAkademikRules,
            'tanggal_awal_cuti'=>'required|date',
            'tanggal_akhir_cuti'=>'required|date',
        ];
    }
}
