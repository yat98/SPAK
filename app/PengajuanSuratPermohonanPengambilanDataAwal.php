<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratPermohonanPengambilanDataAwal extends Model
{
    protected $table = 'pengajuan_surat_permohonan_pengambilan_data_awal';

    protected $fillable = [
        'nim',
        'kepada',
        'tempat_pengambilan_data',
        'file_rekomendasi_jurusan',
        'status',
        'keterangan',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function suratPermohonanPengambilanDataAwal(){
        return $this->hasOne('App\SuratPermohonanPengambilanDataAwal','id_pengajuan');
    }
}
