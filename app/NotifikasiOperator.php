<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotifikasiOperator extends Model
{
    protected $table = 'notifikasi_operator';

    protected $fillable = [
        'id_operator',
        'judul_notifikasi', 
        'isi_notifikasi',
        'link_notifikasi',
        'status',
    ];

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }
}
