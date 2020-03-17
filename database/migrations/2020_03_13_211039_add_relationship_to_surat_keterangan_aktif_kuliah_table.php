<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToSuratKeteranganAktifKuliahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat_keterangan_aktif_kuliah', function (Blueprint $table) {
            $table->string('nim')->index();
            $table->char('nip',18)->index();
            $table->integer('id_tahun_akademik')->unsigned();

            $table->foreign('nim')
                  ->references('nim')
                  ->on('mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('nip')
                  ->references('nip')
                  ->on('user')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_tahun_akademik')
                  ->references('id')
                  ->on('tahun_akademik')
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
        Schema::table('surat_keterangan_aktif_kuliah', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign(['nip']);
            $table->dropForeign(['id_tahun_akademik']);
        });
    }
}
