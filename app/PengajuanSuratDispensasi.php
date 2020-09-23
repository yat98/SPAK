<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratDispensasi extends Model
{
    protected $table = 'pengajuan_surat_dispensasi';

    protected $primaryKey = 'id_surat_masuk';

    protected $fillable = [
        'id_surat_masuk',
        'id_operator',
        'nama_kegiatan',
        'status',
        'keterangan',
    ];

    public function suratDispensasi(){
        return $this->hasOne('App\SuratDispensasi','id_pengajuan');
    }

    public function suratMasuk(){
        return $this->belongsTo('App\SuratMasuk', 'id_surat_masuk');
    }

    public function operator(){
        return $this->belongsTo('App\Operator', 'id_operator');
    }

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','daftar_dispensasi_mahasiswa','id_pengajuan','nim')->withTimeStamps();
    }

    public function tahapanKegiatanDispensasi(){
        return $this->hasMany('App\TahapanKegiatanDispensasi','id_pengajuan');
    }
}
