<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratKeteranganBebasPerlengkapan extends Model
{
    protected $table = 'pengajuan_surat_keterangan_bebas_perlengkapan';

    protected $fillable = [
        'nim',
        'id_operator',
        'status',
        'keterangan',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function suratKeteranganBebasPerlengkapan(){
        return $this->hasOne('App\SuratKeteranganBebasPerlengkapan','id_pengajuan');
    }
}
