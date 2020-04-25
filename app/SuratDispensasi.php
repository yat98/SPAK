<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratDispensasi extends Model
{
    protected $table = 'surat_dispensasi';

    protected $primaryKey = 'id_surat_masuk';

    protected $fillable = [
        'id_surat_masuk',
        'nomor_surat',
        'nip',
        'id_kode_surat',
        'nama_kegiatan',
        'jumlah_cetak',
        'status'
    ];

    public function suratMasuk(){
        return $this->belongsTo('App\SuratMasuk','id_surat_masuk');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','daftar_dispensasi_mahasiswa','id_surat_dispensasi','nim')->withTimestamps();
    }

    public function tahapanKegiatanDispensasi(){
        return $this->hasMany('App\TahapanKegiatanDispensasi','id_surat_dispensasi');
    }
}
