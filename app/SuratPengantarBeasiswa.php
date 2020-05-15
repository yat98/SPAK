<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratPengantarBeasiswa extends Model
{
    protected $table = 'surat_pengantar_beasiswa';

    protected $fillable = [
        'id',
        'id_kode_surat',
        'id_surat_masuk',
        'nip',
        'nip_kasubag',
        'nomor_surat',
        'hal',
        'status',
        'jumlah_cetak',
    ];

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function suratMasuk(){
        return $this->belongsTo('App\SuratMasuk','id_surat_masuk');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function kasubag(){
        return $this->belongsTo('App\User','nip_kasubag');
    }

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','daftar_beasiswa_mahasiswa','id_surat_beasiswa','nim');
    }
}
