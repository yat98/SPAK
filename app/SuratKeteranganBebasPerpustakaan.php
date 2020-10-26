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
        'nip',
        'id_operator',
        'nokta',
        'kode_surat',
        'kewajiban',
        'jumlah_cetak'
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function pengajuanSuratKeteranganBebasPerpustakaan(){
        return $this->belongsTo('App\PengajuanSuratKeteranganBebasPerpustakaan','id_pengajuan');
    }
}
