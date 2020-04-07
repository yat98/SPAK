<?php

namespace App\Http\Requests;

use App\KodeSurat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class KodeSuratRequest extends FormRequest
{
    // Pesan error status_aktif
    private $errorKodeSuratAktif = 'kode surat dengan status aktif sudah ada.';

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
            $kodeSuratRules = 'required|string|regex:/.+\/+./|unique_with:kode_surat,jenis_surat,'.$this->get('id');
        }else{
            $kodeSuratRules = 'required|string|regex:/.+\/+./|unique_with:kode_surat,jenis_surat';
        }
        return[
            'kode_surat'=>$kodeSuratRules,
            'jenis_surat'=>'required|string|in:surat keterangan aktif kuliah,surat dispensasi,surat keterangan cuti,surat kelakuan baik',
            'status_aktif'=>'required|string|in:aktif,non aktif'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $kodeSuratAktif = KodeSurat::where('status_aktif','aktif')->where('jenis_surat',$this->get('jenis_surat'));
        $validator->after(function($validator) use($kodeSuratAktif) {
                if($kodeSuratAktif->exists() && $this->get('status_aktif') == 'aktif'){
                    if($this->method == 'PATCH' || $this->method == 'PUT'){
                        if($this->get('id') != $kodeSuratAktif->first()->id){
                            $validator->errors()->add('status_aktif',$this->errorKodeSuratAktif);
                        }
                    }else{
                        $validator->errors()->add('status_aktif',$this->errorKodeSuratAktif);
                    }
                }
        });
    }   
}
