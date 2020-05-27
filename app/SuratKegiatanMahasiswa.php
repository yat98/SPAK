<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratKegiatanMahasiswa extends Model
{
    protected $table = 'surat_kegiatan_mahasiswa';

    protected $primaryKey = 'id_pengajuan_kegiatan';

    protected $fillable = [
        'id_pengajuan_kegiatan',
        'id_kode_surat',
        'nip',
        'nomor_surat',
        'jumlah_cetak',
        'menimbang',
        'mengingat',
        'memperhatikan',
        'menetapkan',
        'kesatu',
        'kedua',
        'ketiga',
        'keempat',
    ];

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function pengajuanSuratKegiatanMahasiswa(){
        return $this->belongsTo('App\PengajuanSuratKegiatanMahasiswa','id_pengajuan_kegiatan');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }
}
