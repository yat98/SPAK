<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            $nipRules = 'required|unique:user,nip,'.$this->get('nip').',nip';
            $passwordRules = 'sometimes|string|max:60';
        }else{
            $nipRules = 'required|unique:user,nip';
            $passwordRules = 'required|string|max:60';
        }
        
        return [
            'nip'=> $nipRules,
            'nama'=>'required|string|alpha_spaces',
            'jabatan'=>'required|string|in:dekan,wd1,wd2,wd3,kasubag kemahasiswaan,kasubag pendidikan dan pengajaran',
            'status_aktif'=>'required|string|in:aktif,non aktif',
            'pangkat'=>'required|string|in:penata muda,penata muda tkt. I,penata,penata tkt. I,pembina,pembina tkt. I,pembina utama muda,pembina utama madya,pembina utama',
            'golongan'=>'required|string|in:III/a,III/b,III/c,III/d,IV/a,IV/b,IV/c,IV/d,IV/e',
            'tanda_tangan'=>'sometimes|string',
            'password'=>$passwordRules
        ];
    }
}
