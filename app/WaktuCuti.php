<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaktuCuti extends Model
{
    protected $table = 'waktu_cuti';

    protected $fillable = [
        'id_tahun_akademik',
        'tanggal_awal_cuti',
        'tanggal_akhir_cuti',
    ];

    protected $dates = ['tanggal_awal_cuti','tanggal_akhir_cuti'];

    public function tahunAkademik(){
        return $this->belongsTo('App\TahunAkademik','id_tahun_akademik');
    }

    public function pendaftaranCuti(){
        return $this->hasMany('App\PendaftaranCuti','id_waktu_cuti');
    }

    public function suratPengantarCuti(){
        return $this->hasMany('App\SuratPengantarCuti','id_waktu_cuti');
    }
}
