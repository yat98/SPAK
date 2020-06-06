<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratPermohonanPengambilanMaterial extends Model
{
    protected $table = 'surat_permohonan_pengambilan_material';

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

    public function pengajuanSuratPermohonanPengambilanMaterial(){
        return $this->belongsTo('App\PengajuanSuratPermohonanPengambilanMaterial','id_pengajuan');
    }
}
