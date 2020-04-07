<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodeSurat extends Model
{
    protected $table = 'kode_surat';
    
    protected $fillable = [
        'kode_surat',
        'status_aktif',
        'jenis_surat'
    ];
}
