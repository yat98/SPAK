<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->char('nim',25)->primary();
            $table->integer('id_prodi')->unsigned();
            $table->string('nama',100);
            $table->enum('sex',['L','P']);
            $table->year('angkatan');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->double('ipk',3,2);
            $table->string('password',60);
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
        Schema::dropIfExists('mahasiswa');
    }
}
