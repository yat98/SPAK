<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKegiatanMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->integer('id_pengajuan')->unsigned()->primary();
            $table->integer('id_kode_surat')->unsigned();
            $table->integer('id_operator')->unsigned();
            $table->char('nip',18)->index();
            $table->char('nomor_surat',6);
            $table->text('menimbang');
            $table->text('mengingat');
            $table->text('memperhatikan');
            $table->text('menetapkan');
            $table->text('kesatu');
            $table->text('kedua');
            $table->text('ketiga');
            $table->text('keempat');
            $table->integer('jumlah_cetak')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_kegiatan_mahasiswa');
    }
}
