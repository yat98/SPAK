<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TahapanKegiatanDispensasi extends Model
{
    protected $table = 'tahapan_kegiatan_dispensasi';

    protected $fillable = [
        'id_pengajuan',
        'tahapan_kegiatan',
        'tempat_kegiatan',
        'tanggal_awal_kegiatan',
        'tanggal_akhir_kegiatan',
    ];

    protected $dates = [
        'tanggal_awal_kegiatan',
        'tanggal_akhir_kegiatan',
    ];

    public function pengajuanSuratDispensasi(){
        return $this->belongsTo('App\PengajuanSuratDispensasi','id_pengajuan');
    }
}
