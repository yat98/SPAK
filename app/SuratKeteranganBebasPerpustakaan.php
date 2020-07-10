<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratKeteranganBebasPerpustakaan extends Model
{
    protected $table = 'surat_keterangan_bebas_perpustakaan';

    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'id_pengajuan',
        'nomor_surat',
        'id_kode_surat',
        'jumlah_cetak'
    ];

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function pengajuanSuratKeteranganBebasPerpustakaan(){
        return $this->belongsTo('App\PengajuanSuratKeteranganBebasPerpustakaan','id_pengajuan');
    }
}
