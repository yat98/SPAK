<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotifikasiUser extends Model
{
    protected $table = 'notifikasi_user';

    protected $fillable = [
        'nip',
        'judul_notifikasi',
        'isi_notifikasi',
        'link_notifikasi',
        'status'
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }
}
