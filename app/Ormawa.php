<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ormawa extends Model
{
    protected $table = 'ormawa';

    protected $fillable = [
        'id_jurusan',
        'nama'
    ];

    public function jurusan(){
        return $this->belongsTo('App\Jurusan','id_jurusan');
    }
}