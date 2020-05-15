<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToDaftarBeasiswaMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daftar_beasiswa_mahasiswa', function (Blueprint $table) {
            $table->primary(['id_surat_beasiswa','nim']);
            $table->foreign('id_surat_beasiswa')
                  ->references('id')
                  ->on('surat_pengantar_beasiswa')
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
        Schema::table('daftar_beasiswa_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign(['id_surat_beasiswa']);
        });
    }
}
