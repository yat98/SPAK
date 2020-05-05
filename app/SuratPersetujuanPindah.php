<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratPersetujuanPindah extends Model
{
    protected $table = 'surat_persetujuan_pindah';

    protected $primaryKey = 'id_pengajuan_persetujuan_pindah';

    protected $fillable = [
        'id_pengajuan_persetujuan_pindah',
        'nomor_surat',
        'nip',
        'id_kode_surat',
        'jumlah_cetak',
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function pengajuanSuratPersetujuanPindah(){
        return $this->belongsTo('App\PengajuanSuratPersetujuanPindah','id_pengajuan_persetujuan_pindah');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }
}
