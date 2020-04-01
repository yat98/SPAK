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
            $table->char('nip',18)->primary();
            $table->string('nama');
            $table->enum('jabatan',['dekan','wakil dekan 1','wakil dekan 2','wakil dekan 3','kasubag kemahasiswaan','kasubag akademik']);
            $table->enum('status_aktif',['aktif','non-aktif']);
            $table->string('tanda_tangan');
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
