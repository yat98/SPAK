<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratPermohonanSurvei extends Model
{
    protected $table = 'pengajuan_surat_permohonan_survei';

    protected $fillable = [
        'nim',
        'id_operator',
        'mata_kuliah',
        'kepada',
        'file_rekomendasi_jurusan',
        'status',
        'data_survei',
        'keterangan',
    ];

    public function setDataSurveiAttribute($value){
        $this->attributes['data_survei'] = strtolower($value);
    }

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function suratPermohonanSurvei(){
        return $this->hasOne('App\SuratPermohonanSurvei','id_pengajuan');
    }
}
