<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratKeteranganBebasPerpustakaan extends Model
{
    protected $table = 'pengajuan_surat_keterangan_bebas_perpustakaan';

    protected $fillable = [
        'nim',
        'id_operator',
        'status',
        'alamat',
        'telp',
        'keterangan',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function suratKeteranganBebasPerpustakaan(){
        return $this->hasOne('App\SuratKeteranganBebasPerpustakaan','id_pengajuan');
    }
}
