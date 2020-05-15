<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratPengantarBeasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_pengantar_beasiswa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_kode_surat')->unsigned();
            $table->integer('id_surat_masuk')->unsigned();
            $table->char('nip',18)->index();
            $table->char('nip_kasubag',18)->index();
            $table->char('nomor_surat',6);
            $table->string('hal',100);
            $table->enum('status',['menunggu tanda tangan','selesai']);
            $table->integer('jumlah_cetak')->default('0');
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
        Schema::dropIfExists('surat_pengantar_beasiswa');
    }
}
