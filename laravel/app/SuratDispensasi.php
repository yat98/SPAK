<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratDispensasi extends Model
{
    protected $table = 'surat_dispensasi';

    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'id_pengajuan',
        'nip',
        'id_kode_surat',
        'id_operator',
        'nomor_surat',
        'jumlah_cetak',
    ];

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function pengajuanSuratDispensasi(){
        return $this->belongsTo('App\PengajuanSuratDispensasi','id_pengajuan');
    }
}
