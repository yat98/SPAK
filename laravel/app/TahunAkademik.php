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

    public function pengajuanSuratKeterangan(){
        return $this->hasMany('App\PengajuanSuratKeterangan','id_tahun_akademik');
    }

    public function statusMahasiswa(){
        return $this->hasMany('App\StatusMahasiswa','id_tahun_akademik');
    }

    public function waktuCuti(){
        return $this->hasMany('App\Waktu','id_tahun_akademik');
    }
}
