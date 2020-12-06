<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';

    protected $fillable = [
        'nomor_surat',
        'perihal',
        'instansi',
        'file_surat_masuk',
        'tanggal_surat_masuk',
    ];

    protected $dates = ['tanggal_surat_masuk'];
    
    public function suratDispensasi(){
        return $this->hasOne('App\SuratDispensasi','id_surat_masuk');
    }

    public function suratPengantarBeasiswa(){
        return $this->hasOne('App\SuratPengantarBeasiswa','id_surat_masuk');
    }
}
