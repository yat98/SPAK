<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToPengajuanSuratPermohonanPengambilanDataAwalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_surat_permohonan_pengambilan_data_awal', function (Blueprint $table) {
            $table->foreign('nim')
                  ->references('nim')
                  ->on('mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_operator','pengajuan_surat_data_awal_id_operator_foreign')
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
        Schema::table('pengajuan_surat_permohonan_pengambilan_data_awal', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign(['id_operator']);
        });
    }
}
