<?php

namespace App;

use App\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class DisposisiSuratKegiatanMahasiswa extends Model
{
    use CompositeKey;
    
    protected $table = 'disposisi_surat_kegiatan_mahasiswa';

    protected $primaryKey = ['id_pengajuan', 'nip'];

    public $incrementing = false;

    protected $fillable = [
        'id_pengajuan',
        'nip',
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
