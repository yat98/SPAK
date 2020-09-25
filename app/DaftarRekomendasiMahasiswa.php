<?php

namespace App;

use App\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class DaftarRekomendasiMahasiswa extends Model
{
    use CompositeKey;
    
    protected $table = 'daftar_rekomendasi_mahasiswa';

    protected $primaryKey = ['nim','id_pengajuan'];

    public $incrementing = false;

    protected $fillable = [
        'id_pengajuan',
        'nim',
    ];
}
