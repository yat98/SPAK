<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratPersetujuanPindah extends Model
{
    protected $table = 'pengajuan_surat_persetujuan_pindah';

    protected $fillable = [
        'nim',
        'nama_prodi',
        'strata',
        'nama_kampus',
        'file_surat_keterangan_lulus_butuh',
        'file_ijazah_terakhir',
        'file_surat_rekomendasi_jurusan',
        'file_surat_keterangan_bebas_perlengkapan_universitas',
        'file_surat_keterangan_bebas_perlengkapan_fakultas',
        'file_surat_keterangan_bebas_perpustakaan_universitas',
        'file_surat_keterangan_bebas_perpustakaan_fakultas',
        'status',
        'keterangan',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function suratPersetujuanPindah(){
        return $this->hasOne('App\SuratPersetujuanPindah','id_pengajuan');
    }
}
