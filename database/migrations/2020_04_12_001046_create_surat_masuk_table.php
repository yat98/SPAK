<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('nomor_surat',50);
            $table->string('perihal',100);
            $table->string('instansi',100);
            $table->string('file_surat_masuk');
            $table->date('tanggal_surat_masuk');
            $table->enum('bagian',['subbagian kemahasiswaan','subbagian pendidikan dan pengajaran','subbagian umum & bkn']);
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
        Schema::dropIfExists('surat_masuk');
    }
}
