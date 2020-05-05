<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSuratpersetujuanPindahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_surat_persetujuan_pindah', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nim',25)->index();
            $table->string('nama_prodi',100);
            $table->enum('strata',['D3','S1','S2','S3']);
            $table->string('nama_kampus');
            $table->string('file_surat_keterangan_lulus_butuh');
            $table->string('file_ijazah_terakhir');
            $table->string('file_surat_rekomendasi_jurusan');
            $table->string('file_surat_keterangan_bebas_perlengkapan_universitas');
            $table->string('file_surat_keterangan_bebas_perlengkapan_fakultas');
            $table->string('file_surat_keterangan_bebas_perpustakaan_universitas');
            $table->string('file_surat_keterangan_bebas_perpustakaan_fakultas');
            $table->enum('status',['diajukan','menunggu tanda tangan','selesai','ditolak'])->default('diajukan');
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
        Schema::dropIfExists('pengajuan_surat_persetujuan_pindah');
    }
}
