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
        
        $this->command->info('Berhasil menambahkan 2 data kode surat');
    }
}
