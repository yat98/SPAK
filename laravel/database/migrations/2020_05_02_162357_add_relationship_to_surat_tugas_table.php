<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToSuratTugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            $table->foreign('id_pengajuan')
                  ->references('id')
                  ->on('pengajuan_surat_tugas')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_kode_surat')
                  ->references('id')
                  ->on('kode_surat')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('nip')
                  ->references('nip')
                  ->on('user')
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
        Schema::table('surat_tugas', function (Blueprint $table) {
            $table->dropForeign(['id_pengajuan']);
            $table->dropForeign(['id_kode_surat']);
            $table->dropForeign(['nip']);
        });
    }
}
