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
            $table->char('nim')->index()->nullable();
            $table->integer('id_ormawa')->unsigned();
            $table->integer('id_operator')->unsigned()->nullable();
            $table->string('nomor_surat_permohonan_kegiatan');
            $table->string('nama_kegiatan');
            $table->string('file_surat_permohonan_kegiatan');
            $table->string('file_proposal_kegiatan');
            $table->text('lampiran_panitia');
            $table->enum('status',['diajukan','disposisi dekan','disposisi wd1','disposisi wd2','disposisi wd3','disposisi selesai','selesai','ditolak','verifikasi kasubag','verifikasi kabag','menunggu tanda tangan'])->default('diajukan');
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
