<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratDispensasi extends Model
{
    protected $table = 'pengajuan_surat_dispensasi';

    protected $primaryKey = 'id_surat_masuk';

    protected $fillable = [
        'id_surat_masuk',
        'nip',
        'status',
        'keterangan',
    ];

    public function suratDispensasi(){
        return $this->hasOne('App\SuratDispensasi','id_pengajuan_dispensasi');
    }

    public function suratMasuk(){
        return $this->belongsTo('App\SuratMasuk','id_surat_masuk');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function mahasiswa(){
        return $this->belongsToMany('App\Mahasiswa','daftar_dispensasi_mahasiswa','id_pengajuan_dispensasi','nim')->withTimeStamps();
    }

    public function tahapanKegiatanMahasiswa(){
        return $this->hasOne('App\TahapanKegiatanDispensasi','id_pengajuan_dispensasi');
    }
}
