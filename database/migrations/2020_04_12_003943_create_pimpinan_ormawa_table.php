<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePimpinanOrmawaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pimpinan_ormawa', function (Blueprint $table) {
            $table->char('nim',25)->index()->primary();
            $table->integer('id_ormawa')->unsigned();
            $table->enum('jabatan',['ketua','sekretaris','bendahara']);
            $table->enum('status_aktif',['aktif','non aktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pimpinan_ormawa');
    }
}
