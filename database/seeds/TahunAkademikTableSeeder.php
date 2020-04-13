<?php

use App\TahunAkademik;
use Illuminate\Database\Seeder;

class TahunAkademikTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TahunAkademik::create([
            'tahun_akademik'=>'2019/2020',
            'semester'=>'ganjil',
            'status_aktif'=>'aktif'
        ]);
        $this->command->info('Berhasil menambahkan 2 data program-studi');
    }
}
