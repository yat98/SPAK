<?php

namespace App\Imports;

use App\Mahasiswa;
use App\ProgramStudi;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MahasiswaImport implements ToModel, WithValidation, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    use Importable;
    
    private $prodi;

    public function __construct()
    {
       set_time_limit(0);
       $this->prodi = ProgramStudi::all();
    }
    
    public function model(array $row)
    {  
        $idProdi = $this->getIDProdi($row['prodi']);
        $mahasiswa = [
            'nim'=>$row['nim'],
            'nama'=>$row['nama'],
            'sex'=>$row['sex'],
            'angkatan'=>$row['angkatan'],
            'ipk'=>$row['ipk'],
            'password'=>Hash::make($row['nim']),
            'id_prodi'=> $idProdi
        ];
        Mahasiswa::updateOrCreate(['nim'=>$mahasiswa['nim']],$mahasiswa);
    }

    public function rules(): array
    {
        return [
            '*.prodi' => function($attribute, $value, $onFailure) {
                $idProdi = $this->getIDProdi($value);
                if ($idProdi == null) {
                     $onFailure('Data program studi '.$value.' belum terinput');
                }
            },
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize():int
    {
        return 100;
    }

    private function getIDProdi($namaProdi){
        $namaProdi = trim($namaProdi,"0xC2".chr(0xC2).chr(0xA0));
        $prodi = $this->prodi->filter(function ($prodi) use ($namaProdi) {
            return strtolower($prodi->nama_prodi) == strtolower($namaProdi);
        });
        $idProdi = (count($prodi) > 0) ? $prodi->first()->id : null;
        return $idProdi;
    }
}
