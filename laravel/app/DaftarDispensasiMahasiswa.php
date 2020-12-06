<?php

namespace App;

use App\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class DaftarDispensasiMahasiswa extends Model
{
    use CompositeKey;
    
    protected $table = 'daftar_dispensasi_mahasiswa';

    protected $primaryKey = ['nim','id_pengajuan'];

    public $incrementing = false;

    protected $fillable = [
        'id_pengajuan',
        'nim',
    ];
}
