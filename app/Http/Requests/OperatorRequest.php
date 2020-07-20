<?php

namespace App\Http\Requests;

use App\Operator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class OperatorRequest extends FormRequest
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
            $namaRules = 'required|unique:operator,nama,'.$this->get('id');
            $usernameRules = 'required|unique:operator,username,'.$this->get('id');
            $passwordRules = 'sometimes|string|max:60';
        }else{
            $namaRules = 'required|unique:operator,nama';
            $usernameRules = 'required|unique:operator,username';
            $passwordRules = 'required|string|max:60';
        }
        
        return [
            'nama'=>$namaRules,
            'username'=>$usernameRules,
            'password'=>$passwordRules,
            'bagian'=>'required|string|in:subbagian kemahasiswaan,subbagian pengajaran dan pendidikan,subbagian umum & bmn,front office',
            'status_aktif'=>'required|string|in:aktif,non aktif',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $error = 'operator dengan bagian '.$this->get('bagian').' dan status aktif sudah ada.';
        $operatorAktif = Operator::where('bagian',$this->get('bagian'))->where('status_aktif','aktif');
        $validator->after(function($validator) use($operatorAktif,$error) {
                if($operatorAktif->exists() && $this->get('status_aktif') == 'aktif'){
                    if($this->method == 'PATCH' || $this->method == 'PUT'){
                        if($this->get('id') != $operatorAktif->first()->id){
                            $validator->errors()->add('status_aktif',$error);
                            $validator->errors()->add('bagian',$error);
                        }
                    }else{
                        $validator->errors()->add('status_aktif',$error);
                        $validator->errors()->add('bagian',$error);
                    }
                }
        });
    } 
}
