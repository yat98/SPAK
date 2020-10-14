<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToDisposisiSuratKegiatanMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disposisi_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->primary(['id_pengajuan','nip']);

            $table->foreign('id_pengajuan')
                  ->references('id')
                  ->on('pengajuan_surat_kegiatan_mahasiswa')
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disposisi_surat_kegiatan_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['id_pengajuan']);
            $table->dropForeign(['nip']);
            $table->dropForeign(['nip_disposisi']);
        });
    }
}
