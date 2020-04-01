<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramStudiRequest extends FormRequest
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
            $namaProdiRules = 'required|string|unique_with:prodi,strata,'.$this->get('id');
            $strataRules = 'required|in:D3,S1,S2,S3|unique_with:prodi,nama_prodi,'.$this->get('id');
        }else{
            $namaProdiRules = 'required|string|unique_with:prodi,strata';
            $strataRules = 'required|in:D3,S1,S2,S3|unique_with:prodi,nama_prodi';
        }
        return [
            'id_jurusan'=>'required|numeric',
            'nama_prodi'=>$namaProdiRules,
            'strata'=>$strataRules,
        ];
    }
}
