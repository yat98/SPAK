<?php

use App\Jurusan;
use Illuminate\Database\Seeder;

class JurusanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jurusan::create([
            'nama_jurusan'=>'Teknik Informatika'
        ]);
        $this->command->info('Berhasil menambahkan 1 data pada tabel jurusan');
    }
}
