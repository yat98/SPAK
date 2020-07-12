<?php

namespace App\Http\Requests;

use App\Mahasiswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

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

    public function withValidator(Validator $validator)
    {
        $mahasiswa = Mahasiswa::where('nim',$this->get('nim'))->with(['tahunAkademik'=>function($query){
            $query->orderByDesc('created_at');
        }])->first();
        $validator->after(function($validator) use($mahasiswa) {
            if($mahasiswa->tahunAkademik->count() > 0){
                $status = $mahasiswa->tahunAkademik->first()->pivot->status;
                if ($this->method() != 'PATCH' && $this->method() != 'PUT') {
                    if ($status == 'lulus' || $status == 'drop out' || $status == 'keluar') {
                        $validator->errors()->add('nim', 'status mahasiswa terakhir '.$mahasiswa->nama.' adalah '.$status);
                    }
                }
            }
        });
    }
}
