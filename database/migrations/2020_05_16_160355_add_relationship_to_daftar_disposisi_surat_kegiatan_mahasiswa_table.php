<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToDaftarDisposisiSuratKegiatanMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daftar_disposisi_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->primary(['id_disposisi','nip'],'daftar_disposisi_id_pengajuan_nip_primary');

            $table->foreign('id_disposisi')
                  ->references('id_pengajuan')
                  ->on('disposisi_surat_kegiatan_mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('nip')
                  ->references('nip')
                  ->on('user')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('nip_disposisi')
                  ->references('nip')
                  ->on('user')
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
        Schema::table('daftar_disposisi_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['id_disposisi']);
            $table->dropForeign(['nip']);
            $table->dropForeign(['nip_disposisi']);
            $table->dropForeign(['id_operator']);
        });
    }
}
