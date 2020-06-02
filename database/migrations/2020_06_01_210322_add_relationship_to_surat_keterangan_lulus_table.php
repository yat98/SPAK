<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToSuratKeteranganLulusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat_keterangan_lulus', function (Blueprint $table) {
            $table->foreign('id_pengajuan_surat_lulus')
                  ->references('id')
                  ->on('pengajuan_surat_keterangan_lulus')
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
        Schema::table('surat_keterangan_lulus', function (Blueprint $table) {
            $table->dropForeign(['id_pengajuan_surat_lulus']);
            $table->dropForeign(['nip']);
            $table->dropForeign(['id_kode_surat']);
        });
    }
}
