<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuratKegiatanMahasiswa extends Model
{
    protected $table = 'surat_kegiatan_mahasiswa';

    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'id_pengajuan',
        'id_kode_surat',
        'id_operator',
        'nip',
        'nomor_surat',
        'menimbang',
        'mengingat',
        'memperhatikan',
        'menetapkan',
        'kesatu',
        'kedua',
        'ketiga',
        'keempat',
        'jumlah_cetak',
    ];

    public function kodeSurat(){
        return $this->belongsTo('App\KodeSurat','id_kode_surat');
    }

    public function pengajuanSuratKegiatanMahasiswa(){
        return $this->belongsTo('App\PengajuanSuratKegiatanMahasiswa','id_pengajuan');
    }

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }
}
