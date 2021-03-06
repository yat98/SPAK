<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratPermohonanPengambilanDataAwal extends Model
{
    protected $table = 'pengajuan_surat_permohonan_pengambilan_data_awal';

    protected $fillable = [
        'nim',
        'id_operator',
        'kepada',
        'tempat_pengambilan_data',
        'file_rekomendasi_jurusan',
        'status',
        'keterangan',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function suratPermohonanPengambilanDataAwal(){
        return $this->hasOne('App\SuratPermohonanPengambilanDataAwal','id_pengajuan');
    }
}
