<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotifikasiMahasiswa extends Model
{
    protected $table = 'notifikasi_mahasiswa';

    protected $fillable = [
        'nim',
        'judul_notifikasi',
        'isi_notifikasi',
        'link_notifikasi',
        'status'
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }
}
