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
            'kode_surat'=>'UN47/KM.00.00',
            'status_aktif'=>'aktif',
            'jenis_surat'=>'surat keterangan'
        ]);
        $this->command->info('Berhasil menambahkan 1 data kode surat');
    }
}
