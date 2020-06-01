<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nip'=>'196807051997021001',
            'nama'=>'Dr. Sardi Salim, M.Pd',
            'jabatan'=>'dekan',
            'status_aktif'=>'aktif',
            'pangkat'=>'pembina tkt. I',
            'golongan'=>'IV/b',
            'tanda_tangan'=>null,
            'password'=>Hash::make('196807051997021001')
        ]);

        User::create([
            'nip'=>'196908071995012001',
            'nama'=>'Dr. Marike Mahmud, S.T., M.Si',
            'jabatan'=>'wd1',
            'status_aktif'=>'aktif',
            'pangkat'=>'pembina tkt. I',
            'golongan'=>'IV/b',
            'tanda_tangan'=>null,
            'password'=>Hash::make('196908071995012001')
        ]);

        User::create([
            'nip'=>'197410222005011002',
            'nama'=>'Idham Halid Lahay, ST., M.Sc',
            'jabatan'=>'wd2',
            'status_aktif'=>'aktif',
            'pangkat'=>'pembina tkt. I',
            'golongan'=>'IV/b',
            'tanda_tangan'=>null,
            'password'=>Hash::make('197410222005011002')
        ]);

        User::create([
            'nip'=>'197812082003121002',
            'nama'=>'Tajuddin Abdillah, S.Kom., M.Cs',
            'jabatan'=>'wd3',
            'status_aktif'=>'aktif',
            'pangkat'=>'pembina tkt. I',
            'golongan'=>'IV/b',
            'tanda_tangan'=>null,
            'password'=>Hash::make('197812082003121002')
        ]);

        User::create([
            'nip'=>'197208282005011002',
            'nama'=>'Albert H. Mohammad, S.Sos',
            'jabatan'=>'kasubag kemahasiswaan',
            'status_aktif'=>'aktif',
            'pangkat'=>'pembina',
            'golongan'=>'IV/a',
            'tanda_tangan'=>null,
            'password'=>Hash::make('197208282005011002')
        ]);

        User::create([
            'nip'=>'197602115685091002',
            'nama'=>'Siti Asna Sari Djafar Ishak, S.Pd',
            'jabatan'=>'kasubag pendidikan dan pengajaran',
            'status_aktif'=>'aktif',
            'pangkat'=>'pembina',
            'golongan'=>'IV/a',
            'tanda_tangan'=>null,
            'password'=>Hash::make('197602115685091002')
        ]);

        $this->command->info('Berhasil menambahkan 4 data pada table admin');
    }
}
