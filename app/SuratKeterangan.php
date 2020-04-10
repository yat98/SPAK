<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratKeterangan extends Model
{
    protected $table = 'surat_keterangan';

    protected $primaryKey = 'nomor_surat';
    
    protected $fillable = [
        'nomor_surat',
        'nim',
        'nip',
        'id_tahun_akademik',
        'id_kode_surat',
        'jenis_surat',
        'jumlah_cetak',
        'status'
    ];

    public function getNipAttribute($nip){
        return (string) $nip;
    }
    
    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function tahunAkademik(){
        return $this->belongsTo('App\TahunAkademik','id_tahun_akademik');
    }

}
