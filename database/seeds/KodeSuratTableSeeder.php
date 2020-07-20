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
            'kode_surat'=>'UN47.B5',
            'status_aktif'=>'aktif',
        ]);

        $this->command->info('Berhasil menambahkan 1 data kode surat');
    }
}
