<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaftarDisposisiSuratKegiatanMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_disposisi_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->integer('id_disposisi')->unsigned();
            $table->char('nip',18)->index();
            $table->integer('id_operator')->unsigned()->nullable();
            $table->char('nip_disposisi',18)->index()->nullable();
            $table->string('catatan');
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
        Schema::dropIfExists('daftar_disposisi_surat_kegiatan_mahasiswa');
    }
}
