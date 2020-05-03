<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToDaftarRekomendasiMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daftar_rekomendasi_mahasiswa', function (Blueprint $table) {
            $table->primary(['nim','id_surat_rekomendasi']);
            
            $table->foreign('id_surat_rekomendasi')
                  ->references('id')
                  ->on('surat_rekomendasi')
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
        Schema::table('daftar_rekomendasi_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['id_surat_rekomendasi']);
            $table->dropForeign(['nim']);
        });
    }
}
