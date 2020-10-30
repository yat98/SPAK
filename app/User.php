<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticable
{
    use Notifiable;

    protected $guard = 'user';

    protected $table = 'user';

    protected $primaryKey = 'nip';

    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'status_aktif',
        'pangkat',
        'golongan',
        'tanda_tangan',
        'password',
    ];

    protected $hidden = ['password'];

    public function suratKeterangan(){
        return $this->hasMany('App\SuratKeterangan','nip','nip');
    }

    public function notifikasiUser(){
        return $this->hasMany('App\NotifikasiUser','nip','nip');
    }

    public function suratDispensasi(){
        return $this->hasMany('App\SuratDispensasi','nip','nip_kasubag');
    }

    public function suratTugas(){
        return $this->hasMany('App\SuratTugas','nip','nip_kasubag');
    }

    public function suratPersetujuanPindah(){
        return $this->hasMany('App\SuratPersetujuanPindah','nip');
    }

    public function suratPengantarCuti(){
        return $this->hasMany('App\SuratPengantarCuti','nip');
    }

    public function suratPengantarBeasiswa(){
        return $this->hasMany('App\SuratPengantarBeasiswa','nip','nip_kasubag');
    }

    public function suratKegiatanMahasiswa(){
        return $this->hasMany('App\SuratKegiatanMahasiswa','nip');
    }

    public function disposisiUser(){
        return $this->belongsToMany('App\PengajuanSuratKegiatanMahasiswa','disposisi_surat_kegiatan_mahasiswa','id_pengajuan','nip')->withPivot('catatan')->withTimestamps();
    }

    public function suratKeteranganLulus(){
        return $this->hasMany('App\SuratKeteranganLulus','nip');
    }

    public function suratKeteranganBebasPerlengkapan(){
        return $this->hasMany('App\SuratKeteranganBebasPerlengkapan','nip');
    }

    public function daftarDisposisiSuratKegiatanMahasiswa(){
        return $this->hasMany('App\DaftarDisposisiSuratKegiatanMahasiswa','nip');
    }

    public function daftarDisposisiSuratKegiatanMahasiswaPimpinan(){
        return $this->hasMany('App\DaftarDisposisiSuratKegiatanMahasiswa','nip_disposisi');
    }

    public function suratKeteranganBebasPerpustakaan(){
        return $this->hasMany('App\SuratKeteranganBebasPerpustakaan','nip');
    }
}
