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
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
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
                     $onFailure('Terdapat data program studi yang belum terinput');
                }
            },
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize():int
    {
        return 500;
    }

    private function getIDProdi($namaProdi){
        $namaProdi = trim($namaProdi,"0xC2".chr(0xC2).chr(0xA0));
        $prodi = ProgramStudi::where('nama_prodi',$namaProdi)->first();
        $idProdi = ($prodi) ? $prodi->id : null;
        return $idProdi;
    }
}
