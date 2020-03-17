<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';

    protected $fillable = [
        'tahun_akademik',
        'status_aktif'
    ];

    public function getStatusAktifAttribute($status_aktif){
        return ucwords($status_aktif);
    }

    public function getTahunAkademikAttribute($tahun_akademik){
        return ucwords($tahun_akademik);
    }

    public function setTahunAkademikAttribute($value){
        $this->attributes['tahun_akademik'] = strtolower($value);
    }
}
