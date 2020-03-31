<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    protected $fillable = [
        'nim',
        'nama',
        'sex',
        'angkatan',
        'strata',
        'ipk',
        'password',
        'id_prodi'
        ];

    public function setNamaAttribute($value){
        $this->attributes['nama'] = strtolower($value);
    }

    public function getNamaAttribute($nama){
        return ucwords($nama);
    }

    public function getDetailMahasiswa($nim){
        return DB::table($this->table)->join('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id')
                               ->join('jurusan', 'jurusan.id', '=', 'prodi.id_jurusan')
                               ->where('nim',$nim)
                               ->get();Mahasiswa::select('*')
                               ->join('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id')
                               ->join('jurusan', 'jurusan.id', '=', 'prodi.id_jurusan')
                               ->where('nim',$nim)
                               ->get();
    }

    public function prodi(){
        return $this->belongsTo('App\ProgramStudi','id_prodi');
    }
}
