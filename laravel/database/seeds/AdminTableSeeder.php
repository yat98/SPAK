<?php

use App\Admin;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'username'=>'admin',
            'password'=>'admin',
        ]);
        $this->command->info('Berhasil menambahkan 1 data pada table admin');
    }
}
