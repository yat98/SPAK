<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasiOperatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifikasi_operator', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_operator')->index()->unsigned();
            $table->string('judul_notifikasi'); 
            $table->string('isi_notifikasi');
            $table->text('link_notifikasi');
            $table->enum('status',['dilihat','belum dilihat'])->default('belum dilihat');
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
        Schema::dropIfExists('notifikasi_operator');
    }
}
