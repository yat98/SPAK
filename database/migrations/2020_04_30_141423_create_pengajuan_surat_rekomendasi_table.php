<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSuratRekomendasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_surat_rekomendasi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_operator')->unsigned();
            $table->string('nama_kegiatan',100);
            $table->date('tanggal_awal_kegiatan');
            $table->date('tanggal_akhir_kegiatan');
            $table->string('tempat_kegiatan',100);
            $table->enum('status',['diajukan','selesai','ditolak','verifikasi kasubag','verifikasi kabag','menunggu tanda tangan'])->default('diajukan');
            $table->string('keterangan')->default('-');
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
        Schema::dropIfExists('pengajuan_surat_rekomendasi');
    }
}
