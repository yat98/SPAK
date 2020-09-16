<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSuratKeteranganBebasPerlengkapanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('pengajuan_surat_keterangan_bebas_perlengkapan', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->char('nim',25)->index();
        //     $table->enum('status',['diajukan','selesai','ditolak'])->default('diajukan');
        //     $table->string('keterangan')->default('-');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('pengajuan_surat_keterangan_bebas_perlengkapan');
    }
}
