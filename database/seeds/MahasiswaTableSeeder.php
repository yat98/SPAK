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
            'id_prodi'=>$prodi->id,
            'tanggal_lahir'=>'1998-08-23',
            'tempat_lahir'=>'Gorontalo',
        ]);

        Mahasiswa::create([
            'nim'=>'531416042',
            'nama'=>'Rivaldi Barabuat',
            'sex'=>'L',
            'angkatan'=>'2016',
            'ipk'=>'2.62',
            'password'=>Hash::make('531416042'),
            'id_prodi'=>$prodi->id,
            'tanggal_lahir'=>'1998-08-23',
            'tempat_lahir'=>'Gorontalo',
        ]);

        Mahasiswa::create([
            'nim'=>'531416038',
            'nama'=>'Amien Nurholiq Alam',
            'sex'=>'L',
            'angkatan'=>'2016',
            'ipk'=>'3.41',
            'password'=>Hash::make('531416038'),
            'id_prodi'=>$prodi->id,
            'tanggal_lahir'=>'1998-08-23',
            'tempat_lahir'=>'Gorontalo',
        ]);

        Mahasiswa::create([
            'nim'=>'531416037',
            'nama'=>'Sri Mulyani',
            'sex'=>'L',
            'angkatan'=>'2016',
            'ipk'=>'2.96',
            'password'=>Hash::make('531416038'),
            'id_prodi'=>$prodi->id,
            'tanggal_lahir'=>'1998-08-23',
            'tempat_lahir'=>'Gorontalo',
        ]);

        Mahasiswa::create([
            'nim'=>'531416020',
            'nama'=>'Adnan Kasim',
            'sex'=>'L',
            'angkatan'=>'2016',
            'ipk'=>'3.39',
            'password'=>Hash::make('531416020'),
            'id_prodi'=>$prodi->id,
            'tanggal_lahir'=>'1998-08-23',
            'tempat_lahir'=>'Gorontalo',
        ]);

        Mahasiswa::create([
            'nim'=>'531417001',
            'nama'=>'Shaniyah Alhayu Paudi',
            'sex'=>'L',
            'angkatan'=>'2016',
            'ipk'=>'3.32',
            'password'=>Hash::make('531417001'),
            'id_prodi'=>$prodi->id,
            'tanggal_lahir'=>'1998-08-23',
            'tempat_lahir'=>'Gorontalo',
        ]);
        
        Mahasiswa::create([
            'nim'=>'531416017',
            'nama'=>'Mohamad Rezkarnin Yahya',
            'sex'=>'L',
            'angkatan'=>'2016',
            'ipk'=>'3.02',
            'password'=>Hash::make('531416017'),
            'id_prodi'=>$prodi->id,
            'tanggal_lahir'=>'1998-08-23',
            'tempat_lahir'=>'Gorontalo',
        ]);
        $this->command->info('Berhasil menambahkan 7 data mahasiswa');
    }
}
