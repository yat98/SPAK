<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeteranganLulusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keterangan_lulus', function (Blueprint $table) {
            $table->integer('id_pengajuan_surat_lulus')->unsigned()->primary();
            $table->char('nip',18)->nullable();
            $table->integer('id_kode_surat')->unsigned();
            $table->char('nomor_surat',6);
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
        Schema::dropIfExists('surat_keterangan_lulus');
    }
}
