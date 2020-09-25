<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratRekomendasi extends Model
{
    public $table = 'pengajuan_surat_rekomendasi';

    protected $fillable = [
        'id_operator',
        'nama_kegiatan',
        'tanggal_awal_kegiatan',
        'tanggal_akhir_kegiatan',
        'tempat_kegiatan',
        'keterangan',
        'status',
    ];

    protected $dates = ['tanggal_awal_kegiatan','tanggal_akhir_kegiatan'];

    public function operator(){
        return $this->belongsTo('App\Operator', 'id_operator');
    }

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','daftar_rekomendasi_mahasiswa','id_pengajuan','nim')->withTimestamps();
    }

    public function suratRekomendasi(){
        return $this->hasOne('App\SuratRekomendasi','id_pengajuan');
    }
}
