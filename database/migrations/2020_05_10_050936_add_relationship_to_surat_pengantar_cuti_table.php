<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToSuratPengantarCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat_pengantar_cuti', function (Blueprint $table) {
            $table->foreign('id_waktu_cuti')
                  ->references('id')
                  ->on('waktu_cuti')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_operator')
                  ->references('id')
                  ->on('operator')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('nip')
                  ->references('nip')
                  ->on('user')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_kode_surat')
                  ->references('id')
                  ->on('kode_surat')
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
        Schema::table('surat_pengantar_cuti', function (Blueprint $table) {
            $table->dropForeign(['id_waktu_cuti']);
            $table->dropForeign(['id_operator']);
            $table->dropForeign(['nip']);
            $table->dropForeign(['id_kode_surat']);
        });
    }
}
