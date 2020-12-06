<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToPengajuanSuratTugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_surat_tugas', function (Blueprint $table) {
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
        Schema::table('pengajuan_surat_tugas', function (Blueprint $table) {
            $table->dropForeign(['id_operator']);
            $table->dropForeign(['nim']);
        });
    }
}
