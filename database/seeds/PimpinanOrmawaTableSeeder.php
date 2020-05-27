<?php

use App\Ormawa;
use App\PimpinanOrmawa;
use Illuminate\Database\Seeder;

class PimpinanOrmawaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hmb = Ormawa::where('nama','Himpunan Mahasiswa Basica')->first();
        $ksl = Ormawa::where('nama','Kelompok Studi Linux')->first();
        
        PimpinanOrmawa::create([
            'nim'=>'531416020',
            'jabatan'=>'ketua',
            'status_aktif'=>'aktif',    
            'id_ormawa'=>$ksl->id,
        ]);

        PimpinanOrmawa::create([
            'nim'=>'531416042',
            'jabatan'=>'ketua',
            'status_aktif'=>'aktif',    
            'id_ormawa'=>$hmb->id,
        ]);

        PimpinanOrmawa::create([
            'nim'=>'531416038',
            'jabatan'=>'sekretaris',
            'status_aktif'=>'aktif',    
            'id_ormawa'=>$hmb->id,
        ]);

        PimpinanOrmawa::create([
            'nim'=>'531416055',
            'jabatan'=>'sekretaris',
            'status_aktif'=>'aktif',    
            'id_ormawa'=>$ksl->id,
        ]);

        PimpinanOrmawa::create([
            'nim'=>'531416037',
            'jabatan'=>'bendahara',
            'status_aktif'=>'aktif',    
            'id_ormawa'=>$hmb->id,
        ]);

        PimpinanOrmawa::create([
            'nim'=>'531417001',
            'jabatan'=>'bendahara',
            'status_aktif'=>'aktif',    
            'id_ormawa'=>$ksl->id,
        ]);
        $this->command->info('Berhasil menambahkan 6 data pada tabel pimpinan ormawa');
    }
}
