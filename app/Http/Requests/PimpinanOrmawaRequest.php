<?php

namespace App\Http\Requests;

use App\PimpinanOrmawa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PimpinanOrmawaRequest extends FormRequest
{
    private $errorStatusAktif = 'pimpinan ormawa dengan status aktif sudah ada.';
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
            $nimRules = 'required|numeric|unique:pimpinan_ormawa,nim,'.$this->get('nim').',nim';
        }else{
            $nimRules = 'required|numeric|unique:pimpinan_ormawa,nim';
        }
        return [
            'nim'=>$nimRules,
            'jabatan'=>'required|string|in:ketua,sekretaris,bendahara',
            'status_aktif'=>'required|string|in:aktif,non aktif',
            'id_ormawa'=>'required|numeric'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $pimpinanOrmawaAktif = PimpinanOrmawa::where('status_aktif','aktif')
                                               ->where('jabatan',$this->get('jabatan'))
                                               ->where('id_ormawa',$this->get('id_ormawa'));
        
        $validator->after(function($validator) use($pimpinanOrmawaAktif) {
                if($pimpinanOrmawaAktif->exists() && $this->get('status_aktif') == 'aktif'){
                    if($this->method == 'PATCH' || $this->method == 'PUT'){
                        if($this->get('nim') != $pimpinanOrmawaAktif->first()->nim){
                            $validator->errors()->add('status_aktif',$this->errorStatusAktif);
                            $validator->errors()->add('jabatan','pimpinan ormawa dengan jabatan '.$this->get('jabatan').' dan berstatus aktif sudah ada.');
                        }
                    }else{
                        $validator->errors()->add('status_aktif',$this->errorStatusAktif);
                        $validator->errors()->add('jabatan','pimpinan ormawa dengan jabatan '.$this->get('jabatan').' dan berstatus aktif sudah ada.');
                    }
                }
        });
    }   
}
