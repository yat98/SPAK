<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    protected $table = 'surat_tugas';

    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'id_pengajuan',
        'id_kode_surat',
        'nip',
        'nomor_surat',
        'status',
        'jumlah_cetak',
    ];

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    
    public function pengajuanSuratTugas(){
        return $this->belongsTo('App\PengajuanSuratTugas','id_pengajuan');
    }
}
