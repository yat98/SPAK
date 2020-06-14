<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratRekomendasiPenelitian extends Model
{
    protected $table = 'surat_rekomendasi_penelitian';

    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'id_pengajuan',
        'nip',
        'id_kode_surat',
        'nomor_surat',
        'jumlah_cetak',
        'tembusan',
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function pengajuanSuratRekomendasiPenelitian(){
        return $this->belongsTo('App\PengajuanSuratRekomendasiPenelitian','id_pengajuan');
    }
}
