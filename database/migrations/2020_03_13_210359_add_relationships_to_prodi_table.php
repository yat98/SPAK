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
            $table->integer('id_jurusan')->unsigned();
            // Menambahkan foreign key pada tabel prodi
            $table->foreign('id_jurusan')
                  ->references('id')
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
            $table->dropForeign(['id_jurusan']);
        });
    }
}
