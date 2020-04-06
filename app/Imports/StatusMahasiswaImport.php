<?php

namespace App\Imports;

use App\Mahasiswa;
use App\TahunAkademik;
use App\StatusMahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StatusMahasiswaImport implements ToModel, WithValidation, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    use Importable;
    
    private $mahasiswa,$tahunAkademikAktif;

    public function __construct()
    {
       set_time_limit(0);
       $this->mahasiswa = Mahasiswa::all();
       $this->tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
    }

    public function model(array $row)
    {
        if($row['status'] == 'Non-Aktif'){
            $row['status'] = str_replace("-"," ",$row['status']);
        }
        $statusMahasiswa = [
            'nim'=>$row['nim'],
            'id_tahun_akademik'=>$this->tahunAkademikAktif->id,
            'status'=>$row['status']
        ];
        StatusMahasiswa::updateOrCreate([
            'nim'=>$row['nim'],
            'id_tahun_akademik'=>$this->tahunAkademikAktif->id
        ],$statusMahasiswa);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            '*.nim' => function($attribute, $value, $onFailure) {
                $countMahasiswa = $this->countMahasiswa($value);
                if ($countMahasiswa == 0) {
                    $onFailure('Data mahasiswa dengan nim '.$value.' belum terinput');
                }
            },
        ];
    }

    public function batchSize():int
    {
        return 100;
    }

    private function countMahasiswa($nim){
        $nim = trim($nim,"0xC2".chr(0xC2).chr(0xA0));
        $mahasiswa = $this->mahasiswa->filter(function ($mahasiswa) use ($nim) {
            return $mahasiswa->nim == '531419002';
        });
        return $mahasiswa->count();
    }
}
