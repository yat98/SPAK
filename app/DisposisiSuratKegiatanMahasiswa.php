<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisposisiSuratKegiatanMahasiswa extends Model
{
    protected $table = 'disposisi_surat_kegiatan_mahasiswa';

    protected $primaryKey = 'id_pengajuan';

    protected $dates = ['tanggal_terima','tanggal_surat'];

    protected $fillable = [
        'id_pengajuan',
        'nomor_agenda',
        'hal',
        'tanggal_terima',
        'tanggal_surat',
        'keterangan',
    ];

    public function pengajuanSuratKegiatanMahasiswa(){
        return $this->belongsTo('App\PengajuanSuratKegiatanMahasiswa','id_pengajuan');
    }
}
