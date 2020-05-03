<?php

use App\KodeSurat;
use Illuminate\Database\Seeder;

class KodeSuratTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KodeSurat::create([
            'kode_surat'=>'UN47.B5/KM.00.00',
            'status_aktif'=>'aktif',
            'jenis_surat'=>'surat keterangan'
        ]);

        KodeSurat::create([
            'kode_surat'=>'UN47.B5/KM.00.01',
            'status_aktif'=>'aktif',
            'jenis_surat'=>'surat dispensasi'
        ]);

        KodeSurat::create([
            'kode_surat'=>'UN47.B5/KM.05.02',
            'status_aktif'=>'aktif',
            'jenis_surat'=>'surat rekomendasi'
        ]);

        KodeSurat::create([
            'kode_surat'=>'UN47.B5/KM.05.03',
            'status_aktif'=>'aktif',
            'jenis_surat'=>'surat tugas'
        ]);
        
        $this->command->info('Berhasil menambahkan 4 data kode surat');
    }
}
