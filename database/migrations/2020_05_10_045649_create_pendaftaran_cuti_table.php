<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftaranCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftaran_cuti', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_waktu_cuti')->unsigned();
            $table->integer('id_operator')->unsigned()->nullable();
            $table->char('nim',25)->index();
            $table->enum('status',['diajukan','selesai','ditolak'])->default('diajukan');
            $table->string('file_surat_permohonan_cuti');
            $table->string('file_krs_sebelumnya');
            $table->string('file_slip_ukt');
            $table->string('keterangan')->default('-');
            $table->string('alasan_cuti',100);
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
        Schema::dropIfExists('pendaftaran_cuti');
    }
}
