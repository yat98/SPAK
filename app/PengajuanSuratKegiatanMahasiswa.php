<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratKegiatanMahasiswa extends Model
{
    protected $table = 'pengajuan_surat_kegiatan_mahasiswa';

    protected $fillable = [
        'nim',
        'nip',
        'nomor_surat_permohonan_kegiatan',
        'nama_kegiatan',
        'file_surat_permohonan_kegiatan',
        'lampiran_panitia',
        'tanggal_diterima',
        'tanggal_menunggu_tanda_tangan',
        'status',
        'keterangan',
    ];

    protected $dates = [
        'tanggal_diterima',
        'tanggal_menunggu_tanda_tangan',
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function suratKegiatanMahasiswa(){
        return $this->hasOne('App\SuratKegiatanMahasiswa','id_pengajuan_kegiatan');
    }

    public function disposisiUser(){
        return $this->belongsToMany('App\User','disposisi_surat_kegiatan_mahasiswa','id_pengajuan','nip')->withPivot('catatan')->withTimestamps();
    }
}
