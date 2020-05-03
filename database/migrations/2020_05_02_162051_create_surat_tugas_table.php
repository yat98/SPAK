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
            $table->increments('id');
            $table->integer('id_kode_surat')->unsigned();
            $table->char('nip',18)->index();
            $table->char('nip_kasubag',18)->index();
            $table->char('nomor_surat',6);
            $table->string('nama_kegiatan',100);
            $table->string('jenis_kegiatan',100);
            $table->string('tempat_kegiatan',100);
            $table->date('tanggal_awal_kegiatan');
            $table->date('tanggal_akhir_kegiatan');
            $table->enum('status',['diajukan','selesai'])->default('diajukan');
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
