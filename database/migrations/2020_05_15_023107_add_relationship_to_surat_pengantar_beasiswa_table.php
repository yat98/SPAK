<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToSuratPengantarBeasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat_pengantar_beasiswa', function (Blueprint $table) {
            $table->foreign('id_surat_masuk')
                  ->references('id')
                  ->on('surat_masuk')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_kode_surat')
                  ->references('id')
                  ->on('kode_surat')
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surat_pengantar_beasiswa', function (Blueprint $table) {
            $table->dropForeign(['id_kode_surat']);
            $table->dropForeign(['id_surat_masuk']);
            $table->dropForeign(['nip']);
        });
    }
}
