<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToPimpinanOrmawaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pimpinan_ormawa', function (Blueprint $table) {
            $table->foreign('nim')
                  ->references('nim')
                  ->on('mahasiswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
                  
            $table->foreign('id_ormawa')
                  ->references('id')
                  ->on('ormawa')
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
        Schema::table('pimpinan_ormawa', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign(['id_ormawa']);
        });
    }
}
