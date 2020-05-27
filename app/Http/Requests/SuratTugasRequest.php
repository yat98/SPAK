<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratTugasRequest extends FormRequest
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
        if($this->method() == 'PATCH' || $this->method == 'PUT'){
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat,'.$this->get('id').'|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat';
        }else{
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat';
        }
        return [
            'nim'=>'required',
            'nip'=>'required|numeric',
            'id_kode_surat'=>'required|numeric',
            'nomor_surat'=>$nomorSuratRules,
            'nama_kegiatan'=>'required|string|max:100',
            'jenis_kegiatan'=>'required|string|max:100',
            'tempat_kegiatan'=>'required|string|max:100',
            'tanggal_awal_kegiatan'=>'required|date',
            'tanggal_akhir_kegiatan'=>'required|date',
        ];
    }
}
