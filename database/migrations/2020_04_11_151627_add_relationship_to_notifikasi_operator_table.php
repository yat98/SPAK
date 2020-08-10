<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToNotifikasiOperatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifikasi_operator', function (Blueprint $table) {
            $table->foreign('id_operator')
                  ->references('id')
                  ->on('notifikasi_operator')
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
        Schema::table('notifikasi_operator', function (Blueprint $table) {
            $table->dropForeign(['id_operator']);
        });
    }
}
