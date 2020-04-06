<?php

namespace App;

use App\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class StatusMahasiswa extends Model
{
    use CompositeKey;

    protected $table = 'status_mahasiswa';

    protected $primaryKey = ['nim', 'id_tahun_akademik'];

    public $incrementing = false;
    
    protected $fillable = [
        'nim',
        'id_tahun_akademik',
        'status',
    ];
}
