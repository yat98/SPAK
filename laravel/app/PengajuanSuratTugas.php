<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratTugas extends Model
{
    public $table = 'pengajuan_surat_tugas';

    protected $fillable = [
        'id_operator',
        'nim',
        'nama_kegiatan',
        'jenis_kegiatan',
        'tempat_kegiatan',
        'tanggal_awal_kegiatan',
        'tanggal_akhir_kegiatan',
        'status',
        'keterangan',
    ];

    protected $dates = ['tanggal_awal_kegiatan','tanggal_akhir_kegiatan'];

    public function operator(){
        return $this->belongsTo('App\Operator', 'id_operator');
    }

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','daftar_tugas_mahasiswa','id_pengajuan','nim')->withTimestamps();
    }

    public function suratTugas(){
        return $this->hasOne('App\SuratTugas','id_pengajuan');
    }

    public function mhs(){
        return $this->belongsTo('App\Mahasiswa', 'nim');
    }
}
