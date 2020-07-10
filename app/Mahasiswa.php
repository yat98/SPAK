<?php

namespace App;

use Carbon\Carbon;
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
        'id_prodi',
        'tanggal_lahir',
        'tempat_lahir',
    ];

    protected $dates = ['tanggal_lahir'];

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

    public function suratRekomendasi(){
        return $this->belongsToMany('App\SuratRekomendasi','daftar_rekomendasi_mahasiswa','id_surat_rekomendasi','nim');
    }

    public function suratTugas(){
        return $this->belongsToMany('App\SuratTugas','daftar_tugas_mahasiswa','id_surat_tugas','nim');
    }

    public function pendaftaranCuti(){
        return $this->hasMany('App\PendaftaranCuti','nim');
    }

    public function suratPengantarBeasiswa(){
        return $this->belongsToMany('App\SuratPengantarBeasiswa','daftar_beasiswa_mahasiswa','id_surat_beasiswa','nim');
    }

    public function pengajuanSuratKegiatanMahasiswa(){
        return $this->hasMany('App\PengajuanSuratKegiatanMahasiswa','nim');
    }

    public function pengajuanSuratKeteranganLulus(){
        return $this->hasMany('App\PengajuanSuratKeteranganLulus','nim');
    }

    public function pengajuanSuratPermohonanPengambilanMaterial(){
        return $this->hasMany('App\PengajuanSuratPermohonanPengambilanMaterial', 'nim');
    }

    public function daftarKelompok(){
        return $this->belongsToMany('App\PengajuanSuratPermohonanPengambilanMaterial','daftar_kelompok_pengambilan_material','id_pengajuan','nim')->withTimeStamps();
    }

    public function pengajuanSuratPermohonanSurvei(){
        return $this->hasMany('App\PengajuanSuratPermohonanSurvei', 'nim');
    }

    public function pengajuanSuratRekomendasiPenelitian(){
        return $this->hasMany('App\PengajuanSuratRekomendasiPenelitian', 'nim');
    }

    public function pengajuanSuratKeteranganBebasPerpustakaan(){
        return $this->hasMany('App\PengajuanSuratKeteranganBebasPerpustakaan', 'nim');
    }

    public function pengajuanSuratKeteranganBebasPerlengkapan(){
        return $this->hasMany('App\PengajuanSuratKeteranganBebasPerlengkapan', 'nim');
    }
}
