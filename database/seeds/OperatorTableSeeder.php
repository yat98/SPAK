<?php

use App\Operator;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OperatorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $bagianList = [
                        'subbagian kemahasiswaan',
                        'subbagian pendidikan dan pengajaran',
                        'subbagian umum & bmn',
                        'front office',
                        'operator perpustakaan'
        ];

        foreach ($bagianList as $bagian) {
            Operator::create([
                'nama'=>$faker->name,
                'username'=>$faker->userName,
                'password'=>'12345678',
                'bagian'=>$bagian
            ]);
        }
        $this->command->info('Berhasil menambahkan '.count($bagianList).' data pada table operator');
    }
}
