<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToPengajuanSuratKegiatanMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->foreign('id_ormawa')
                  ->references('id')
                  ->on('ormawa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
            $table->foreign('id_operator')
                  ->references('id')
                  ->on('operator')
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
        Schema::table('pengajuan_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign(['id_ormawa']);
            $table->dropForeign(['id_operator']);
        });
    }
}
