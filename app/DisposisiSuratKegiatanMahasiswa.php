<?php

namespace App;

use App\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class DisposisiSuratKegiatanMahasiswa extends Model
{
    use CompositeKey;
    
    protected $table = 'disposisi_surat_kegiatan_mahasiswa';

    protected $primaryKey = ['id_pengajuan', 'nip'];

    public $incrementing = false;

    protected $fillable = [
        'id_pengajuan',
        'nip',
        'catatan',
    ];
}
