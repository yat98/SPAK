<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrmawaRequest extends FormRequest
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
            $namaRules = 'required|string|max:100|unique:ormawa,nama,'.$this->get('id');
        }else{
            $namaRules = 'required|string|max:100|unique:ormawa,nama';
        }
        return [
            'nama'=>$namaRules,
            'id_jurusan'=>'required|numeric'
        ];
    }
}
