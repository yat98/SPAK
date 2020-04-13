<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PimpinanOrmawa extends Model
{
    protected $table = 'pimpinan_ormawa';

    protected $primaryKey = 'nim';

    protected $fillable = [
        'nim',
        'jabatan',
        'status_aktif',
        'id_ormawa'
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim','nim');
    }
}
