<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToNotifikasiUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifikasi_user', function (Blueprint $table) {
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
        Schema::table('notifikasi_user', function (Blueprint $table) {
            $table->dropForeign(['nip']);
        });
    }
}
