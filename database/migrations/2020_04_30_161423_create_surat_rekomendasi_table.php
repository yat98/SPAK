<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratRekomendasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_rekomendasi', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nip',18)->index();
            $table->char('nip_kasubag',18)->index();
            $table->integer('id_kode_surat')->unsigned();
            $table->char('nomor_surat',6);
            $table->string('nama_kegiatan',100);
            $table->date('tanggal_awal_kegiatan');
            $table->date('tanggal_akhir_kegiatan');
            $table->string('tempat_kegiatan',100);
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
        Schema::dropIfExists('surat_rekomendasi');
    }
}
