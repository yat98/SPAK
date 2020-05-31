<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTableSeeder::class);
        $this->call(UserTableSeeder::class);
        // $this->call(JurusanTableSeeder::class);
        // $this->call(ProgramStudiTableSeeder::class);
        // $this->call(TahunAkademikTableSeeder::class);
        // $this->call(MahasiswaTableSeeder::class);
        // $this->call(StatusMahasiswaTableSeeder::class);
        // $this->call(KodeSuratTableSeeder::class);
        // $this->call(WaktuCutiTableSeeder::class);
        // $this->call(OrmawaTableSeeder::class);
        // $this->call(PimpinanOrmawaTableSeeder::class);
    }
}
