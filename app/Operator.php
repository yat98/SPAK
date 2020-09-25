<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class Operator extends Authenticable
{
    use Notifiable;

    protected $guard = 'operator';

    protected $table = 'operator';

    protected $fillable = [
       'nama',
       'username',
       'password',
       'bagian',
       'status_aktif'
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function notifikasiOperator(){
        return $this->hasMany('App\NotifikasiOperator','id_operator');
    }

    public function pengajuanSuratKeterangan(){
        return $this->hasMany('App\PengajuanSuratKeterangan','id_operator');
    }

    public function suratKeterangan(){
        return $this->hasMany('App\SuratKeterangan','id_operator');
    }

    public function pengajuanSuratDispensasi(){
        return $this->hasMany('App\PengajuanSuratDispensasi','id_operator');
    }

    public function pengajuanSuratRekomendasi(){
        return $this->hasMany('App\PengajuanSuratRekomendasi','id_operator');
    }
}
