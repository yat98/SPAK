<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratKeterangan extends Model
{
    protected $table = 'surat_keterangan';

    protected $primaryKey = 'id_pengajuan_surat_keterangan';
    
    protected $fillable = [
        'id_pengajuan_surat_keterangan',
        'nomor_surat',
        'nip',
        'id_kode_surat',
        'jumlah_cetak',
    ];
    
    public function user(){
        return $this->belongsTo('App\User','nip','nip');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function pengajuanSuratKeterangan(){
        return $this->belongsTo('App\PengajuanSuratKeterangan','id_pengajuan_surat_keterangan');
    }
}
