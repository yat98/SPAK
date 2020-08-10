<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSuratKeteranganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_surat_keterangan', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nim',25)->index();
            $table->integer('id_tahun_akademik')->unsigned();
            $table->integer('id_operator')->unsigned()->nullable();
            $table->enum('jenis_surat',['surat keterangan aktif kuliah','surat keterangan kelakuan baik']);
            $table->enum('status',['diajukan','selesai','ditolak','revisi','dibuat','diverifikasi'])->default('diajukan');
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
        Schema::dropIfExists('pengajuan_surat_keterangan');
    }
}
