<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    protected $primaryKey = 'nip';
    
    protected $casts = [
        'nip' => 'String',
    ];

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'status_aktif',
        'tanda_tangan',
        'password',
    ];
}
