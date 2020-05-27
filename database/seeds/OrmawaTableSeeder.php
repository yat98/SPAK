<?php

use App\Ormawa;
use App\Jurusan;
use Illuminate\Database\Seeder;

class OrmawaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jurusan = Jurusan::where('nama_jurusan','Teknik Informatika')->first();
        Ormawa::create([
            'id_jurusan'=>$jurusan->id,
            'nama'=>'Himpunan Mahasiswa Basica',
        ]);

        Ormawa::create([
            'id_jurusan'=>$jurusan->id,
            'nama'=>'Kelompok Studi Linux',
        ]);

        $this->command->info('Berhasil menambahkan 2 data pada tabel ormawa');
    }
}
