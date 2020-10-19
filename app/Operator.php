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

    public function pengajuanSuratPersetujuanPindah(){
        return $this->hasMany('App\PengajuanSuratPersetujuanPindah','id_operator');
    }

    public function pendaftaranCuti(){
        return $this->hasMany('App\PendaftaranCuti','id_operator');
    }

    public function suratPengantarCuti(){
        return $this->hasMany('App\SuratPengantarCuti','id_operator');
    }

    public function suratPengantarBeasiswa(){
        return $this->hasMany('App\SuratPengantarBeasiswa','id_operator');
    }

    public function pengajuanSuratKegiatanMahasiswa(){
        return $this->hasMany('App\SuratKegiatanMahasiswa','nim');
    }

    public function suratKegiatanMahasiswa(){
        return $this->hasMany('App\SuratKegiatanMahasiswa','id_operator');
    }

    public function pengajuanSuratKeteranganLulus(){
        return $this->hasMany('App\PengajuanSuratKeteranganLulus','id_operator');
    }

    public function pengajuanSuratPermohonanPengambilanMaterial(){
        return $this->hasMany('App\PengajuanSuratPermohonanPengambilanMaterial','id_operator');
    }

    public function pengajuanSuratPermohonanSurvei(){
        return $this->hasMany('App\PengajuanSuratPermohonanSurvei','id_operator');
    }

    public function pengajuanSuratRekomendasiPenelitian(){
        return $this->hasMany('App\PengajuanSuratRekomendasiPenelitian','id_operator');
    }

    public function pengajuanSuratPermohonanPengambilanDataAwal(){
        return $this->hasMany('App\PengajuanSuratPermohonanPengambilanDataAwal','id_operator');
    }
}
