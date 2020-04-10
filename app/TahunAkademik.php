<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';

    protected $fillable = [
        'tahun_akademik',
        'semester',
        'status_aktif'
    ];

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','status_mahasiswa','id_tahun_akademik','nim')->withPivot('status')->withTimeStamps();
    }

    public function suratKeterangan(){
        return $this->hasMany('App\SuratKeterangan','id_tahun_akademik');
    }
}
