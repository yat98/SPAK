<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToStatusMahasiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_mahasiswa', function (Blueprint $table) {
           // Menambahkan foreign key pada tabel mahasiswa
            $table->foreign('nim')
                  ->references('nim')
                  ->on('mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            // Menambahkan foreign key pada tabel tahun_akademik
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
        Schema::table('status_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign(['id_tahun_akademik']);
        });
    }
}
