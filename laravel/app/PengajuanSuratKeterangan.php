<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratKeterangan extends Model
{
    protected $table = 'pengajuan_surat_keterangan';

    protected $fillable = [
        'nim',
        'id_tahun_akademik',
        'status',
        'jenis_surat',
        'keterangan',
        'id_operator'
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function tahunAkademik(){
        return $this->belongsTo('App\TahunAkademik','id_tahun_akademik');
    }

    public function suratKeterangan(){
        return $this->hasOne('App\SuratKeterangan','id_pengajuan');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }
}
