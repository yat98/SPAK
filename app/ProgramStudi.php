<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'prodi';

    protected $fillable = [
        'nama_prodi',
        'strata',
        'id_jurusan',
    ];

    public function jurusan(){
        return $this->belongsTo('App\Jurusan','id_jurusan');
    }
}
