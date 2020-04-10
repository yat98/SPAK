<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeteranganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keterangan', function (Blueprint $table) {
            $table->integer('nomor_surat')->unsigned()->primary();
            $table->string('nim')->index();
            $table->char('nip',18)->index();
            $table->integer('id_tahun_akademik')->unsigned();
            $table->integer('id_kode_surat')->unsigned();
            $table->enum('jenis_surat',['surat keterangan aktif kuliah','surat keterangan kelakukan baik','surat keterangan cuti']);
            $table->integer('jumlah_cetak');
            $table->enum('status',['ditolak','diproses','selesai']);
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
        Schema::dropIfExists('surat_keterangan');
    }
}
