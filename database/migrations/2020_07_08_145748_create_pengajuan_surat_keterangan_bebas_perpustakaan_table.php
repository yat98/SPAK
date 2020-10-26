<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSuratKeteranganBebasPerpustakaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_surat_keterangan_bebas_perpustakaan', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nim',25)->index();
            $table->integer('id_operator')->unsigned()->nullable(); 
            $table->enum('status',['diajukan','menunggu tanda tangan','selesai','ditolak'])->default('diajukan');
            $table->string('alamat');
            $table->string('telp');
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
        Schema::dropIfExists('pengajuan_surat_keterangan_bebas_perpustakaan');
    }
}
