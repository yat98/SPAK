<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratPermohonanPengambilanDataAwal extends Model
{
    protected $table = 'surat_permohonan_pengambilan_data_awal';

    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'id_pengajuan',
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

    public function pengajuanSuratPermohonanPengambilanDataAwal(){
        return $this->belongsTo('App\PengajuanSuratPermohonanPengambilanDataAwal','id_pengajuan');
    }
}
