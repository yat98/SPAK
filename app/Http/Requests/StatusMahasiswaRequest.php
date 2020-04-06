<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusMahasiswaRequest extends FormRequest
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
            $nimRules = 'required|string|unique_with:status_mahasiswa,id_tahun_akademik,'.$this->get('nim').'=nim,'.$this->get('id_tahun_akademik').'=id_tahun_akademik';
            $idTahunAkademikRules = 'required|string|unique_with:status_mahasiswa,nim,'.$this->get('id_tahun_akademik').'=id_tahun_akademik,'.$this->get('nim').'=nim';
        }else{
            $nimRules = 'required|string|unique_with:status_mahasiswa,id_tahun_akademik';
            $idTahunAkademikRules = 'required|string|unique_with:status_mahasiswa,nim';
        }
        return [
            'nim'=>$nimRules,
            'id_tahun_akademik'=>$idTahunAkademikRules,
            'status'=>'required|string|in:aktif,non aktif,cuti,drop out,lulus,keluar'
        ];
    }
}
