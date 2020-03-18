<?php

namespace App\Http\Requests;

use App\TahunAkademik;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class TahunAkademikRequest extends FormRequest
{
    // Pesan error status_aktif
    private $errorStatusAktif = 'Tahun akademik dengan status aktif sudah ada';

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
            $tahunAkademikRules = 'required|string|unique_with:tahun_akademik,semester,'.$this->get('id');
        }else{
            $tahunAkademikRules = 'required|string|unique_with:tahun_akademik,semester';
        }
        return [
            'tahun_akademik'=>$tahunAkademikRules,
            'semester'=>'required|string|in:genap,ganjil',
            'status_aktif'=>'required|string|in:aktif,non aktif'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif');
        $validator->after(function($validator) use($tahunAkademikAktif) {
                if($tahunAkademikAktif->exists() && $this->get('status_aktif') == 'aktif'){
                    if($this->method == 'PATCH' || $this->method == 'PUT'){
                        if($this->get('id') != $tahunAkademikAktif->first()->id){
                            $validator->errors()->add('status_aktif',$this->errorStatusAktif);
                        }
                    }else{
                        $validator->errors()->add('status_aktif',$this->errorStatusAktif);
                    }
                }
        });
    }   
}
