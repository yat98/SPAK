<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanSuratPersetujuanPindahRequest extends FormRequest
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
            $fileSuratKeteranganLulusButuhRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileIjazahTerakhirRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratRekomendasiJurusanRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratKeteranganBebasPerlengkapanUniversitasRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratKeteranganBebasPerlengkapanFakultasRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratKeteranganBebasPerpustakaanUniversitasRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratKeteranganBebasPerpustakaanFakultasRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:2048';
           
        }else{
            $fileSuratKeteranganLulusButuhRules='required|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileIjazahTerakhirRules='required|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratRekomendasiJurusanRules='required|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratKeteranganBebasPerlengkapanUniversitasRules='required|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratKeteranganBebasPerlengkapanFakultasRules='required|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratKeteranganBebasPerpustakaanUniversitasRules='required|image|mimes:jpg,jpeg,bmp,png|max:2048';
            $fileSuratKeteranganBebasPerpustakaanFakultasRules='required|image|mimes:jpg,jpeg,bmp,png|max:2048';
        }
        return [
            'nim'=>'required',
            'nama_prodi'=>'required|string|max:100',
            'strata'=>'required|string|in:D3,S1,S2,S3',
            'nama_kampus'=>'required|string',
            'file_surat_keterangan_lulus_butuh'=>$fileSuratKeteranganLulusButuhRules,
            'file_ijazah_terakhir'=>$fileIjazahTerakhirRules,
            'file_surat_rekomendasi_jurusan'=>$fileSuratRekomendasiJurusanRules,
            'file_surat_keterangan_bebas_perlengkapan_universitas'=>$fileSuratKeteranganBebasPerlengkapanUniversitasRules,
            'file_surat_keterangan_bebas_perlengkapan_fakultas'=>$fileSuratKeteranganBebasPerlengkapanFakultasRules,
            'file_surat_keterangan_bebas_perpustakaan_universitas'=>$fileSuratKeteranganBebasPerpustakaanUniversitasRules,
            'file_surat_keterangan_bebas_perpustakaan_fakultas'=>$fileSuratKeteranganBebasPerpustakaanFakultasRules,
        ];
    }
}
