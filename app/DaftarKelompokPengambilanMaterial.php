<?php

namespace App;

use App\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class DaftarKelompokPengambilanMaterial extends Model
{
    use CompositeKey;
    
    protected $table = 'daftar_kelompok_pengambilan_material';

    protected $primaryKey = ['nim','id_pengajuan'];

    public $incrementing = false;

    protected $fillable = [
        'id_pengajuan',
        'nim',
    ];
}
