<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratPengantarCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_pengantar_cuti', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_waktu_cuti')->unsigned();
            $table->integer('id_kode_surat')->unsigned();
            $table->integer('id_operator')->unsigned();
            $table->char('nip',18)->index();
            $table->char('nomor_surat',6);
            $table->enum('status',['selesai','verifikasi kasubag','verifikasi kabag','menunggu tanda tangan'])->default('verifikasi kasubag');
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
        Schema::dropIfExists('surat_pengantar_cuti');
    }
}
