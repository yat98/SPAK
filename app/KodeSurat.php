<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodeSurat extends Model
{
    protected $table = 'kode_surat';
    
    protected $fillable = [
        'kode_surat',
        'status_aktif',
        'jenis_surat'
    ];

    public function suratKeterangan(){
        return $this->hasMany('App\SuratKeterangan','id_kode_surat');
    }

    public function suratDispensasi(){
        return $this->hasMany('App\SuratDispensasi','id_kode_surat');
    }
}
