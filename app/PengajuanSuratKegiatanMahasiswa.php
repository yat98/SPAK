<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratKegiatanMahasiswa extends Model
{
    protected $table = 'pengajuan_surat_kegiatan_mahasiswa';

    protected $fillable = [
        'nim',
        'id_ormawa',
        'id_operator',
        'nomor_surat_permohonan_kegiatan',
        'nama_kegiatan',
        'file_surat_permohonan_kegiatan',
        'file_proposal_kegiatan',
        'lampiran_panitia',
        'status',
        'keterangan',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function ormawa(){
        return $this->belongsTo('App\Ormawa','id_ormawa');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function suratKegiatanMahasiswa(){
        return $this->hasOne('App\SuratKegiatanMahasiswa','id_pengajuan');
    }

    public function disposisiUser(){
        return $this->belongsToMany('App\User','disposisi_surat_kegiatan_mahasiswa','id_pengajuan','nip')->withPivot(['nip','catatan'])->withTimestamps();
    }
}
