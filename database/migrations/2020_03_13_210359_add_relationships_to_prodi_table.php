<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToProdiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prodi', function (Blueprint $table) {
            // Menambahkan  kode_jurusan dengan format index
            $table->char('kode_jurusan',2)->index();
            // Menambahkan foreign key pada tabel prodi
            $table->foreign('kode_jurusan')
                  ->references('kode_jurusan')
                  ->on('jurusan')
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
        Schema::table('prodi', function (Blueprint $table) {
            //Menghapus foreign key
            $table->dropForeign(['kode_jurusan']);
        });
    }
}
