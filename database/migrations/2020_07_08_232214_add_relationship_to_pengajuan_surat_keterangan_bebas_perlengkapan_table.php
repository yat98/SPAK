<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToPengajuanSuratKeteranganBebasPerlengkapanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('pengajuan_surat_keterangan_bebas_perlengkapan', function (Blueprint $table) {
        //     $table->foreign('nim')
        //           ->references('nim')
        //           ->on('mahasiswa')
        //           ->onUpdate('cascade')
        //           ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('pengajuan_surat_keterangan_bebas_perlengkapan', function (Blueprint $table) {
        //     $table->dropForeign(['nim']);
        // });
    }
}
