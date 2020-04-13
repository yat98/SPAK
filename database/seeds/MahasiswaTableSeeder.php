<?php

use App\Mahasiswa;
use App\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prodi = ProgramStudi::where('nama_prodi','Sistem Informasi')->first();
        Mahasiswa::create([
            'nim'=>'531416055',
            'nama'=>'Hidayat Chandra',
            'sex'=>'L',
            'angkatan'=>'2016',
            'ipk'=>'3.76',
            'password'=>Hash::make('531416055'),
            'id_prodi'=>$prodi->id
        ]);
        $this->command->info('Berhasil menambahkan 1 data mahasiswa');
    }
}
