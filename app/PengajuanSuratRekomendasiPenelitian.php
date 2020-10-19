<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratRekomendasiPenelitian extends Model
{
    protected $table = 'pengajuan_surat_rekomendasi_penelitian';

    protected $fillable = [
        'nim',
        'id_operator',
        'kepada',
        'judul',
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

    public function suratRekomendasiPenelitian(){
        return $this->hasOne('App\SuratRekomendasiPenelitian','id_pengajuan');
    }
}
