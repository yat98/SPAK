<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    protected $fillable = [
        'nim',
        'nama',
        'sex',
        'angkatan',
        'strata',
        'ipk',
        'password',
        'id_prodi'
    ];

    public function setNamaAttribute($value){
        $this->attributes['nama'] = strtolower($value);
    }

    public function getNamaAttribute($nama){
        return ucwords($nama);
    }
}
