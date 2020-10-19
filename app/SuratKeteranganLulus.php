<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratKeteranganLulus extends Model
{
    protected $table = 'surat_keterangan_lulus';

    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'id_pengajuan_surat_lulus',
        'nip',
        'id_kode_surat',
        'nomor_surat',
        'jumlah_cetak',
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function pengajuanSuratKeteranganLulus(){
        return $this->belongsTo('App\PengajuanSuratKeteranganLulus','id_pengajuan');
    }
}
