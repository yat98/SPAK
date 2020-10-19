<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratKeteranganLulus extends Model
{
    protected $table = 'pengajuan_surat_keterangan_lulus';

    protected $fillable = [
        'nim',
        'id_operator',
        'status',
        'file_rekomendasi_jurusan',
        'file_berita_acara_ujian',
        'tanggal_wisuda',
        'keterangan',
        'status'
    ];

    protected $dates = ['tanggal_wisuda'];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function suratKeteranganLulus(){
        return $this->hasOne('App\SuratKeteranganLulus','id_pengajuan');
    }
}
