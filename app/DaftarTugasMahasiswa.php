<?php

namespace App;

use App\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class DaftarTugasMahasiswa extends Model
{
    use CompositeKey;
    
    protected $table = 'daftar_tugas_mahasiswa';

    protected $primaryKey = ['nim','id_pengajuan'];

    public $incrementing = false;

    protected $fillable = [
        'id_pengajuan',
        'nim',
    ];
}
