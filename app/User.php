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
        'tanda_tangan',
        'password',
    ];

    public function suratKeterangan(){
        return $this->hasMany('App\SuratKeterangan','nip','nip');
    }

    public function notifikasiUser(){
        return $this->hasMany('App\NotifikasiUser','nip','nip');
    }
}
