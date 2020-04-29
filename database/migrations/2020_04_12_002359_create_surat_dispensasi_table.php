<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratDispensasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_dispensasi', function (Blueprint $table) {
            $table->integer('id_surat_masuk')->unsigned()->primary();
            $table->char('nip',18)->index();
            $table->char('nip_kasubag',18)->index();
            $table->integer('id_kode_surat')->unsigned();
            $table->string('nama_kegiatan',100);
            $table->char('nomor_surat',6);
            $table->enum('status',['diajukan','selesai'])->default('diajukan');
            $table->integer('jumlah_cetak');
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
        Schema::dropIfExists('surat_dispensasi');
    }
}
