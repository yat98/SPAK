<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratTugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->integer('id_pengajuan')->unsigned()->primary();
            $table->integer('id_kode_surat')->unsigned();
            $table->char('nip',18)->index();
            $table->char('nomor_surat',6);
            $table->enum('status',['menunggu tanda tangan','selesai'])->default('menunggu tanda tangan');
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
        Schema::dropIfExists('surat_tugas');
    }
}
