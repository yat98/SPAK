<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendaftaranCuti extends Model
{
    protected $table = 'pendaftaran_cuti';

    protected $fillable = [
        'id_waktu_cuti',
        'id_operator',
        'nim',
        'status',
        'file_surat_permohonan_cuti',
        'file_krs_sebelumnya',
        'file_slip_ukt',
        'keterangan',
        'alasan_cuti',
    ];

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa','nim');
    }

    public function waktuCuti(){
        return $this->belongsTo('App\WaktuCuti','id_waktu_cuti');
    }

    public function operator(){
        return $this->belongsTo('App\Operator','id_operator');
    }
}
