<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToSuratDispensasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->foreign('id_surat_masuk')
                  ->references('id')
                  ->on('surat_masuk')
                  ->onUpdate('cascade')
                  ->onCascade('cascade');

            $table->foreign('nip')
                  ->references('nip')
                  ->on('user')
                  ->onUpdate('cascade')
                  ->onCascade('cascade');

            $table->foreign('id_kode_surat')
                  ->references('id')
                  ->on('kode_surat')
                  ->onUpdate('cascade')
                  ->onCascade('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->dropForeign(['id_surat_masuk']);
            $table->dropForeign(['nip']);
            $table->dropForeign(['id_kode_surat']);
        });
    }
}
