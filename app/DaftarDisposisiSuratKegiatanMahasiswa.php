<?php

namespace App;

use App\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class DaftarDisposisiSuratKegiatanMahasiswa extends Model
{
    use CompositeKey;
    
    protected $table = 'daftar_disposisi_surat_kegiatan_mahasiswa';

    protected $primaryKey = ['id_disposisi', 'nip'];

    public $incrementing = false;

    protected $fillable = [
        'id_disposisi',
        'nip',
        'id_operator',
        'nip_disposisi',
        'catatan',
    ];

    public function user(){
        return $this->belongsTo('App\User','nip');
    }

    public function userDisposisi(){
        return $this->belongsTo('App\User','nip_disposisi');
    }
}
