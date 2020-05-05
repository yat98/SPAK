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
        return [
            'nim'=>'required',
            'nama_prodi'=>'required|string|max:100',
            'strata'=>'required|string|in:D3,S1,S2,S3',
            'nama_kampus'=>'required|string',
            'file_surat_keterangan_lulus_butuh'=>'required|image|mimes:jpg,jpeg,bmp,png|max:2048',
            'file_ijazah_terakhir'=>'required|image|mimes:jpg,jpeg,bmp,png|max:1024',
            'file_surat_rekomendasi_jurusan'=>'required|image|mimes:jpg,jpeg,bmp,png|max:1024',
            'file_surat_keterangan_bebas_perlengkapan_universitas'=>'required|image|mimes:jpg,jpeg,bmp,png|max:1024',
            'file_surat_keterangan_bebas_perlengkapan_fakultas'=>'required|image|mimes:jpg,jpeg,bmp,png|max:1024',
            'file_surat_keterangan_bebas_perpustakaan_universitas'=>'required|image|mimes:jpg,jpeg,bmp,png|max:1024',
            'file_surat_keterangan_bebas_perpustakaan_fakultas'=>'required|image|mimes:jpg,jpeg,bmp,png|max:1024',
        ];
    }
}
