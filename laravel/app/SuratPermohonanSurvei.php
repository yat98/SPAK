<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratPermohonanSurvei extends Model
{
    protected $table = 'surat_permohonan_survei';

    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'id_pengajuan',
        'nip',
        'id_kode_surat',
        'id_operator',
        'nomor_surat',
        'jumlah_cetak',
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function pengajuanSuratPermohonanSurvei(){
        return $this->belongsTo('App\PengajuanSuratPermohonanSurvei','id_pengajuan');
    }
}
