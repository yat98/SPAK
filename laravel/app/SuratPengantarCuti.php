<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratPengantarCuti extends Model
{
    protected $table = 'surat_pengantar_cuti';

    protected $fillable = [
        'id_waktu_cuti',
        'id_kode_surat',
        'id_operator',
        'nip',
        'nomor_surat',
        'jumlah_cetak',
        'status',
    ];

    public function waktuCuti(){
        return $this->belongsTo('App\WaktuCuti','id_waktu_cuti');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }
}
