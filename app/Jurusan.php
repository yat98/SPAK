<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = [
        'nama_jurusan'
    ];

    public function programStudi(){
        return $this->hasMany('App\ProgramStudi','id_jurusan');
    }
}
