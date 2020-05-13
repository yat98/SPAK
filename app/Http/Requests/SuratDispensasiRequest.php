<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SuratDispensasiRequest extends FormRequest
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
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_pengantar_cuti,nomor_surat,'.$this->get('nomor_surat').',nomor_surat|unique:surat_persetujuan_pindah,nomor_surat,'.$this->get('nomor_surat').',nomor_surat|unique:surat_rekomendasi,nomor_surat,'.$this->get('nomor_surat').',nomor_surat|unique:surat_tugas,nomor_surat,'.$this->get('nomor_surat').',nomor_surat|unique:surat_dispensasi,nomor_surat,'.$this->get('nomor_surat').',nomor_surat|unique:surat_keterangan,nomor_surat,'.$this->get('nomor_surat').',nomor_surat';
            $idSuratMasukRules = 'required|numeric|unique:surat_dispensasi,id_surat_masuk,'.$this->get('id').',id_surat_masuk';
        }else{
            $nomorSuratRules = 'required|numeric|min:1|unique:surat_pengantar_cuti,nomor_surat|unique:surat_persetujuan_pindah,nomor_surat|unique:surat_rekomendasi,nomor_surat|unique:surat_tugas,nomor_surat|unique:surat_dispensasi,nomor_surat|unique:surat_keterangan,nomor_surat';
            $idSuratMasukRules = 'required|numeric|unique:surat_dispensasi,id_surat_masuk';
        }
        return [
            'id_surat_masuk'=>$idSuratMasukRules,
            'nip'=>'required|numeric',
            'id_kode_surat'=>'required|numeric',
            'nomor_surat'=>$nomorSuratRules,
            'nama_kegiatan'=>'required|string|max:100',
            'status'=>'sometimes|in:diajukan,selesai',
            'nim'=>'required',
            'tahapan_kegiatan'=>'required',
            'tempat_kegiatan'=>'required',
            'tanggal_awal_kegiatan'=>'required',
            'tanggal_akhir_kegiatan'=>'required',
            'jumlah_cetak'=>'sometimes|numeric',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $jumlah = $this->get('jumlah');
        $tahapanKegiatan = $this->get('tahapan_kegiatan');
        $tempatKegiatan = $this->get('tempat_kegiatan');
        $tanggalAwalKegiatan = $this->get('tanggal_awal_kegiatan');
        $tanggalAkhirKegiatan = $this->get('tanggal_akhir_kegiatan');
        $i = 0;
        while($i < $jumlah){
            $validator->after(function($validator) use($tahapanKegiatan,$tempatKegiatan,$tanggalAwalKegiatan,$tanggalAkhirKegiatan,$i){
                if(trim($tahapanKegiatan[$i]) == null){
                    $validator->errors()->add('tahapan_kegiatan_'.$i,'tahapan kegiatan wajib diisi.');
                }
                if(trim($tempatKegiatan[$i]) == null){
                    $validator->errors()->add('tempat_kegiatan_'.$i,'tempat kegiatan wajib diisi.');
                }
                if(trim($tanggalAwalKegiatan[$i]) == null){
                    $validator->errors()->add('tanggal_awal_kegiatan_'.$i,'tanggal awal kegiatan wajib diisi.');
                }
                if(trim($tanggalAkhirKegiatan[$i]) == null){
                    $validator->errors()->add('tanggal_akhir_kegiatan_'.$i,'tanggal akhir kegiatan wajib diisi.');
                }
                if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",trim($tanggalAwalKegiatan[$i]))) {
                    $validator->errors()->add('tanggal_akhir_kegiatan_'.$i,'tanggal awal kegiatan bukan tanggal yang valid.');
                }
                if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",trim($tanggalAkhirKegiatan[$i]))) {
                    $validator->errors()->add('tanggal_akhir_kegiatan_'.$i,'tanggal akhir kegiatan bukan tanggal yang valid.');
                }
            });
            $i++;
        }
    }
}
