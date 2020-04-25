<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToTahapanKegiatanDispensasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tahapan_kegiatan_dispensasi', function (Blueprint $table) {
            $table->foreign('id_surat_dispensasi')
                  ->references('id_surat_masuk')
                  ->on('surat_dispensasi')
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
        Schema::table('tahapan_kegiatan_dispensasi', function (Blueprint $table) {
            $table->dropForeign(['id_surat_dispensasi']);
        });
    }
}
