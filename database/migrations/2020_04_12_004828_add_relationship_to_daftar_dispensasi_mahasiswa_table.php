<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToDaftarDispensasiMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daftar_dispensasi_mahasiswa', function (Blueprint $table) {
            $table->primary(['id_surat_dispensasi','nim']);

            $table->foreign('id_surat_dispensasi')
                  ->references('id_surat_masuk')
                  ->on('surat_dispensasi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('nim')
                  ->references('nim')
                  ->on('mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daftar_dispensasi_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['id_surat_dispensasi']);
            $table->dropForeign(['nim']);
        });
    }
}
