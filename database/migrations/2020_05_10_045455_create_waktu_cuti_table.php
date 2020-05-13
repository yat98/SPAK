<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaktuCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waktu_cuti', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tahun_akademik')->unsigned();
            $table->date('tanggal_awal_cuti');
            $table->date('tanggal_akhir_cuti');
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
        Schema::dropIfExists('waktu_cuti');
    }
}
