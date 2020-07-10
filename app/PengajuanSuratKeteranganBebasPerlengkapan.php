<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratKeteranganBebasPerlengkapan extends Model
{
    protected $table = 'pengajuan_surat_keterangan_bebas_perlengkapan';

    protected $fillable = [
        'nim',
        'status',
        'keterangan',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function suratKeteranganBebasPerlengkapan(){
        return $this->hasOne('App\SuratKeteranganBebasPerlengkapan','id_pengajuan');
    }
}
