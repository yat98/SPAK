<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodeSurat extends Model
{
    protected $table = 'kode_surat';
    
    protected $fillable = [
        'kode_surat',
        'status_aktif',
        'jenis_surat'
    ];

    public function suratKeterangan(){
        return $this->hasMany('App\SuratKeterangan','id_kode_surat');
    }

    public function suratDispensasi(){
        return $this->hasMany('App\SuratDispensasi','id_kode_surat');
    }

    public function suratRekomendasi(){
        return $this->hasMany('App\SuratRekomendasi','id_kode_surat');
    }

    public function suratTugas(){
        return $this->hasMany('App\SuratTugas','id_kode_surat');
    }

    public function suratPersetujuanPindah(){
        return $this->hasMany('App\SuratPersetujuanPindah','id_kode_surat');
    }

    public function suratPengantarCuti(){
        return $this->hasMany('App\SuratPengantarCuti','id_kode_surat');
    }

    public function suratPengantarBeasiswa(){
        return $this->hasMany('App\SuratPengantarBeasiswa','id_kode_surat');
    }

    public function suratPermohonanPengambilanMaterial(){
        return $this->hasMany('App\SuratPermohonanPengambilanMaterial','id_kode_surat');
    }

    public function suratPermohonanSurvei(){
        return $this->hasMany('App\SuratPermohonanSurvei','id_kode_surat');
    }

    public function suratRekomendasiPenelitian(){
        return $this->hasMany('App\SuratRekomendasiPenelitian','id_kode_surat');
    }

    public function suratPermohonanPengambilanDataAwal(){
        return $this->hasMany('App\SuratPermohonanPengambilanDataAwal','id_kode_surat');
    }

    public function suratKeteranganBebasPerpustakaan(){
        return $this->hasMany('App\SuratKeteranganBebasPerpustakaan','id_kode_surat');
    }

    public function suratKeteranganBebasPerlengkapan(){
        return $this->hasMany('App\SuratKeteranganBebasperlengkapan','id_kode_surat');
    }
}
