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
            $table->string('nama',100);
            $table->enum('jabatan',['dekan','wd1','wd2','wd3','kasubag kemahasiswaan','kasubag pendidikan dan pengajaran','kasubag umum & bmn','kabag tata usaha','kepala perpustakaan']);
            $table->enum('pangkat',['penata muda','penata muda tkt. I','penata','penata tkt. I','pembina','pembina tkt. I','pembina utama muda','pembina utama madya','pembina utama']);
            $table->enum('golongan',['III/a','III/b','III/c','III/d','IV/a','IV/b','IV/c','IV/d','IV/e']);
            $table->enum('status_aktif',['aktif','non aktif']);
            $table->text('tanda_tangan')->nullable();
            $table->string('password',60);
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
