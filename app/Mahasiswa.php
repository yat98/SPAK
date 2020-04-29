<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $primaryKey = 'nim';

    protected $keyType = 'string';
    
    protected $fillable = [
        'nim',
        'nama',
        'sex',
        'angkatan',
        'strata',
        'ipk',
        'password',
        'id_prodi'
    ];

    public function setNamaAttribute($value){
        $this->attributes['nama'] = strtolower($value);
    }

    public function getNamaAttribute($nama){
        return ucwords($nama);
    }
    
    public function prodi(){
        return $this->belongsTo('App\ProgramStudi','id_prodi');
    }

    public function tahunAkademik(){
        return $this->belongsToMany('App\TahunAkademik','status_mahasiswa','nim','id_tahun_akademik')->withPivot('status')->withTimestamps();
    }

    public function pengajuanSuratKeterangan(){
        return $this->hasMany('App\PengajuanSuratKeterangan','nim');
    }

    public function pimpinanOrmawa(){
        return $this->hasOne('App\PimpinanOrmawa','nim');
    }

    public function notifikasiMahasiswa(){
        return $this->hasMany('App\Mahasiswa','nim','nim');
    }

    public function statusMahasiswa(){
        return $this->hasMany('App\StatusMahasiswa','nim');
    }

    public function suratDispensasi(){
        return $this->belongsToMany('App\SuratDispensasi','daftar_dispensasi_mahasiswa','id_surat_dispensasi','nim')->withTimeStamps();
    }
}
