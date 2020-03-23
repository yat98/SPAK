<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusMahasiswa extends Model
{
    protected $table = 'status_mahasiswa';
    
    protected $fillable = [
        'nim',
        'id_tahun_akademik',
        'status'
    ];
}
