<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratKegiatanMahasiswaRequest extends FormRequest
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
            $fileSuratPermohonanRules = 'sometimes|image|mimes:jpg,jpeg,bmp,png|max:1024';
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat,'.$this->get('id_pengajuan_kegiatan').',id_pengajuan_kegiatan|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat';
            
        }else{
            $fileSuratPermohonanRules = 'required|image|mimes:jpg,jpeg,bmp,png|max:1024'; 
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_kegiatan_mahasiswa,nomor_surat|unique:surat_pengantar_beasiswa,nomor_surat|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat'; 
        }
        return [
            'nomor_surat_permohonan_kegiatan'=>'required|string',
            'nama_kegiatan'=>'required|string',
            'file_surat_permohonan_kegiatan'=>$fileSuratPermohonanRules,
            'lampiran_panitia'=>'required|string',
            'id_kode_surat'=>'required|numeric',
            'nip'=>'required|numeric',  
            'nomor_surat'=>$nomorSuratRules,
            'menimbang'=>'required|string',
            'mengingat'=>'required|string',
            'memperhatikan'=>'required|string',
            'menetapkan'=>'required|string',
            'kesatu'=>'required|string',
            'kedua'=>'required|string',
            'ketiga'=>'required|string',
            'keempat'=>'required|string',
            'ormawa'=>'required|numeric',
        ];
    }
}
