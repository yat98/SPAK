<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToSuratKegiatanMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->foreign('id_pengajuan_kegiatan')
                  ->references('id')
                  ->on('pengajuan_surat_kegiatan_mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_kode_surat')
                  ->references('id')
                  ->on('kode_surat')
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
        Schema::table('surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['id_pengajuan_kegiatan']);
            $table->dropForeign(['id_kode_surat']);
            $table->dropForeign(['nip']);
        });
    }
}
