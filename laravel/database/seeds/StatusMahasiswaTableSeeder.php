<?php

use App\Mahasiswa;
use App\TahunAkademik;
use App\StatusMahasiswa;
use Illuminate\Database\Seeder;

class StatusMahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mahasiswa = Mahasiswa::where('nim','531416055')->first();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif','aktif')->first();
        StatusMahasiswa::create([
            'nim'=>$mahasiswa->nim,
            'id_tahun_akademik'=>$tahunAkademikAktif->id,
            'status'=>'aktif'
        ]);
        $this->command->info('Berhasil menambahkan 1 data status mahasiswa');
    }
}
