<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';

    protected $fillable = [
        'tahun_akademik',
        'semester',
        'status_aktif'
    ];
}
