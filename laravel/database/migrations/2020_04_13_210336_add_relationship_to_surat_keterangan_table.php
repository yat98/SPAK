<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToSuratKeteranganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat_keterangan', function (Blueprint $table) {
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
            
            $table->foreign('id_pengajuan')
                  ->references('id')
                  ->on('pengajuan_surat_keterangan')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_operator')
                  ->references('id')
                  ->on('operator')
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
        Schema::table('surat_keterangan', function (Blueprint $table) {
            $table->dropForeign(['nip']);
            $table->dropForeign(['id_kode_surat']);
            $table->dropForeign(['id_pengajuan']);
            $table->dropForeign(['id_operator']);
        });
    }
}
