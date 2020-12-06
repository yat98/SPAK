<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTahapanKegiatanDispensasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tahapan_kegiatan_dispensasi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pengajuan')->unsigned();
            $table->string('tahapan_kegiatan',100);
            $table->string('tempat_kegiatan',100);
            $table->date('tanggal_awal_kegiatan');
            $table->date('tanggal_akhir_kegiatan');
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
        Schema::dropIfExists('tahapan_kegiatan_dispensasi');
    }
}
