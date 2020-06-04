<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratKeteranganLulus extends Model
{
    protected $table = 'pengajuan_surat_keterangan_lulus';

    protected $fillable = [
        'nim',
        'status',
        'file_rekomendasi_jurusan',
        'file_berita_acara_ujian',
        'tanggal_wisuda',
        'ipk',
        'keterangan',
        'status'
    ];

    protected $dates = ['tanggal_wisuda'];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function suratKeteranganLulus(){
        return $this->hasOne('App\SuratKeteranganLulus','id_pengajuan_surat_lulus');
    }
}
