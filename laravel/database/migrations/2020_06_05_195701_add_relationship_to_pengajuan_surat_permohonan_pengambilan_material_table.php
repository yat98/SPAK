<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToPengajuanSuratPermohonanPengambilanMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_surat_permohonan_pengambilan_material', function (Blueprint $table) {
            $table->foreign('nim','pengajuan_surat_permohonan_pengambilan_material_nim_foreign')
                  ->references('nim')
                  ->on('mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_operator','pengajuan_surat_material_id_operator_foreign')
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
        Schema::table('pengajuan_surat_permohonan_pengambilan_material', function (Blueprint $table) {
            $table->dropForeign('pengajuan_surat_permohonan_pengambilan_material_nim_foreign');
            $table->dropForeign('pengajuan_surat_material_id_operator_foreign');
        });
    }
}
