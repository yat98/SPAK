<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratPersetujuanPindahRequest extends FormRequest
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
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat,'.$this->get('id').',id_pengajuan_persetujuan_pindah|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi|unique:surat_keterangan,nomor_surat';
            $fileSuratKeteranganLulusButuhRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileIjazahTerakhirRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratRekomendasiJurusanRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratKeteranganBebasPerlengkapanUniversitasRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratKeteranganBebasPerlengkapanFakultasRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratKeteranganBebasPerpustakaanUniversitasRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratKeteranganBebasPerpustakaanFakultasRules='sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024';
        }else{
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat';
            $fileSuratKeteranganLulusButuhRules='required|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileIjazahTerakhirRules='required|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratRekomendasiJurusanRules='required|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratKeteranganBebasPerlengkapanUniversitasRules='required|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratKeteranganBebasPerlengkapanFakultasRules='required|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratKeteranganBebasPerpustakaanUniversitasRules='required|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $fileSuratKeteranganBebasPerpustakaanFakultasRules='required|image|mimes:jpg,jpeg,bmp,png|max:1024';
        }
        return [
            'nomor_surat'=>$nomorSuratRules,
            'nip'=>'required|numeric',
            'id_kode_surat'=>'required|numeric',
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
