<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratKeteranganBebasPerlengkapan extends Model
{
    protected $table = 'surat_keterangan_bebas_perlengkapan';

    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'id_pengajuan',
        'nomor_surat',
        'nip',
        'id_kode_surat',
        'id_operator',
        'jumlah_cetak',
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function pengajuanSuratKeteranganBebasPerlengkapan(){
        return $this->belongsTo('App\PengajuanSuratKeteranganBebasPerlengkapan','id_pengajuan');
    }
}
