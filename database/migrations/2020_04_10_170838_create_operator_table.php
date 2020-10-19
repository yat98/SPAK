<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('username');
            $table->string('password',60);
            $table->enum('bagian',['subbagian kemahasiswaan','subbagian pendidikan dan pengajaran','subbagian umum & bmn','front office']);
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
        Schema::dropIfExists('operator');
    }
}
