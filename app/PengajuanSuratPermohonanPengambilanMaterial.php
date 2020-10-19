<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratPermohonanPengambilanMaterial extends Model
{
    protected $table = 'pengajuan_surat_permohonan_pengambilan_material';

    protected $fillable = [
        'nim',
        'id_operator',
        'kepada',
        'nama_kegiatan',
        'nama_kelompok',
        'status',
        'keterangan',
        'file_rekomendasi_jurusan',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }

    public function suratPermohonanPengambilanMaterial(){
        return $this->hasOne('App\SuratPermohonanPengambilanMaterial','id_pengajuan');
    }

    public function daftarKelompok(){
        return $this->belongsToMany('App\Mahasiswa','daftar_kelompok_pengambilan_material','id_pengajuan','nim')->withTimeStamps();
    }
}
