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
        $data = [
            'nip'=>'197208282005011002',
            'nama'=>'Albert H. Mohammad, S.Sos',
            'jabatan'=>'kasubag kemahasiswaan',
            'status_aktif'=>'aktif',
            'tanda_tangan'=>null,
            'password'=>Hash::make('197208282005011002')
        ];
        User::create($data);
        $this->command->info('Berhasil menambahkan 1 data pada table admin');
    }
}
