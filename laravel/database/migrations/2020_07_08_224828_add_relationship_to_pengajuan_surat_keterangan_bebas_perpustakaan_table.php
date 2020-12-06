<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToPengajuanSuratKeteranganBebasPerpustakaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_surat_keterangan_bebas_perpustakaan', function (Blueprint $table) {
            $table->foreign('nim')
                  ->references('nim')
                  ->on('mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_operator','pengajuan_surat_perpustakaan_id_operator_foreign')
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
        Schema::table('pengajuan_surat_keterangan_bebas_perpustakaan', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign('pengajuan_surat_perpustakaan_id_operator_foreign');
        });
    }
}
