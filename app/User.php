<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    protected $primaryKey = 'nip';

    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'status_aktif',
        'pangkat',
        'golongan',
        'tanda_tangan',
        'password',
    ];

    public function suratKeterangan(){
        return $this->hasMany('App\SuratKeterangan','nip','nip');
    }

    public function notifikasiUser(){
        return $this->hasMany('App\NotifikasiUser','nip','nip');
    }

    public function suratDispensasi(){
        return $this->hasMany('App\SuratDispensasi','nip','nip_kasubag');
    }

    public function suratTugas(){
        return $this->hasMany('App\SuratTugas','nip','nip_kasubag');
    }

    public function suratPersetujuanPindah(){
        return $this->hasMany('App\SuratPersetujuanPindah','nip');
    }
}
