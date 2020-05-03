<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratRekomendasi extends Model
{
    protected $table = 'surat_rekomendasi';

    protected $fillable = [
        'nip',
        'nip_kasubag',
        'id_kode_surat',
        'nomor_surat',
        'nama_kegiatan',
        'tanggal_awal_kegiatan',
        'tanggal_akhir_kegiatan',
        'tempat_kegiatan',
        'status',
    ];

    protected $dates = ['tanggal_awal_kegiatan','tanggal_akhir_kegiatan'];

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','daftar_rekomendasi_mahasiswa','id_surat_rekomendasi','nim')->withTimestamps();
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function kasubag(){
        return $this->belongsTo('App\User','nip_kasubag');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }
}
