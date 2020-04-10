<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            // $table->char('nip',18)->primary();
            $table->string('nama');
            $table->enum('jabatan',['dekan','wd1','wd2','wd3','kasubag kemahasiswaan','kasubag pendidikan dan pengajaran']);
            $table->enum('status_aktif',['aktif','non aktif']);
            $table->text('tanda_tangan')->nullable();
            $table->string('password');
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
        Schema::dropIfExists('user');
    }
}
