<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisposisiSuratKegiatanMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposisi_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->integer('id_pengajuan')->unsigned()->primary();
            $table->integer('nomor_agenda');
            $table->string('hal');
            $table->date('tanggal_terima');
            $table->date('tanggal_surat');
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('disposisi_surat_kegiatan_mahasiswa');
    }
}
