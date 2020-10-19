<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSuratPermohonanPengambilanMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_surat_permohonan_pengambilan_material', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nim',25)->index()->nullable();
            $table->integer('id_operator')->unsigned()->nullable();
            $table->string('kepada');
            $table->string('nama_kegiatan');
            $table->string('nama_kelompok');
            $table->string('file_rekomendasi_jurusan');
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
        Schema::dropIfExists('pengajuan_surat_permohonan_pengambilan_material');
    }
}
