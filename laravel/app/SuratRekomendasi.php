<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratRekomendasi extends Model
{
    protected $table = 'surat_rekomendasi';

    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'id_pengajuan',
        'nip',
        'id_kode_surat',
        'id_operator',
        'nomor_surat',
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

    public function pengajuanSuratRekomendasi(){
        return $this->belongsTo('App\PengajuanSuratRekomendasi','id_pengajuan');
    }
}
