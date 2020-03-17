<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class JurusanRequest extends FormRequest
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
            $namaJurusanRules = 'required|string|unique:jurusan,nama_jurusan,'.$this->get('id');
        }else{
            $namaJurusanRules = 'required|string|unique:jurusan,nama_jurusan';
        }
        return [
            'nama_jurusan'=>$namaJurusanRules
        ];
    }
}
