<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    protected $table = 'surat_tugas';

    protected $fillable = [
        'id_kode_surat',
        'nip',
        'nip_kasubag',
        'nomor_surat',
        'nama_kegiatan',
        'jenis_kegiatan',
        'tempat_kegiatan',
        'tanggal_awal_kegiatan',
        'tanggal_akhir_kegiatan',
        'status',
        'jumlah_cetak',
    ];

    protected $dates = ['tanggal_awal_kegiatan','tanggal_akhir_kegiatan'];

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','daftar_tugas_mahasiswa','id_surat_tugas','nim');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function kasubag(){
        return $this->belongsTo('App\User','nip');
    }
}
