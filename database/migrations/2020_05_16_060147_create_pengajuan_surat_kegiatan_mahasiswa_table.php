<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSuratKegiatanMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nim',18)->index();
            $table->char('nip',)->index();
            $table->string('nomor_surat_permohonan_kegiatan');
            $table->string('nama_kegiatan');
            $table->string('file_surat_permohonan_kegiatan');
            $table->text('lampiran_panitia');
            $table->dateTime('tanggal_diterima')->nullable();
            $table->dateTime('tanggal_menunggu_tanda_tangan')->nullable();
            $table->enum('status',['diajukan','diterima','ditolak','disposisi dekan','disposisi wakil dekan I','disposisi wakil dekan II','disposisi wakil dekan III','menunggu tanda tangan','selesai'])->default('diajukan');
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
        Schema::dropIfExists('pengajuan_surat_kegiatan_mahasiswa');
    }
}
