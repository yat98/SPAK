<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Menambahkan kode prodi dengan format index
            $table->char('kode_prodi',2)->index();
            // Menambahkan foreign key pada tabel mahasiswa
            $table->foreign('kode_prodi')
                  ->references('kode_prodi')
                  ->on('prodi')
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
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Menghapus foreign key kode_prodi
            $table->dropForeign(['kode_prodi']);
        });
    }
}
