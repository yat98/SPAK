<?php

use App\Jurusan;
use App\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jurusan = Jurusan::where('nama_jurusan','Teknik Informatika')->first();
        ProgramStudi::create([
            'nama_prodi'=>'Sistem Informasi',
            'strata'=>'S1',
            'id_jurusan'=>$jurusan->id,
        ]);
        ProgramStudi::create([
            'nama_prodi'=>'Pend. Teknologi Informasi',
            'strata'=>'S1',
            'id_jurusan'=>$jurusan->id,
        ]);
        $this->command->info('Berhasil menambahkan 2 data program-studi');
    }
}
