<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MahasiswaRequest extends FormRequest
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
        // dd($this->get('status_mahasiswa'));
        if($this->method() == 'PATCH' || $this->method() == 'PUT'){
            $nimRules = 'required|numeric|unique:mahasiswa,nim,'.$this->get('nim').',nim';
            $passwordRules = 'sometimes|string|max:60';
        }else{
            $nimRules = 'required|numeric|unique:mahasiswa,nim';
            $passwordRules = 'required|string|max:60';
        }
        return [
            'nim'=>$nimRules,
            'nama'=>'required|alpha_spaces',
            'sex'=>'required|in:L,P',
            'angkatan'=>'required|numeric|digits:4',
            'ipk'=>'required|numeric|min:0|max:4|regex:/^\d+(\.\d{1,2})?$/',
            'password'=>$passwordRules,
            'id_prodi'=>'required|numeric',
            'tempat_lahir'=>'required|string',
            'tanggal_lahir'=>'required|date',
        ];
    }
}
