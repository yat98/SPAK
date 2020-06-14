<?php

use App\WaktuCuti;
use App\TahunAkademik;
use Illuminate\Database\Seeder;

class WaktuCutiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tahunAkademik = TahunAkademik::where('status_aktif','aktif')->first();
        WaktuCuti::create([
            'id_tahun_akademik'=>$tahunAkademik->id,
            'tanggal_awal_cuti'=>'2020-06-01',
            'tanggal_akhir_cuti'=>'2020-06-16 23:59:59',
        ]);
        $this->command->info('Berhasil menambahkan 1 data pada tabel waktu cuti');
    }
}
