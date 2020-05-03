<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKodeSuratTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kode_surat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_surat',50);
            $table->enum('status_aktif',['aktif','non aktif']);
            $table->enum('jenis_surat',['surat keterangan','surat dispensasi','surat pengantar cuti','surat rekomendasi','surat tugas']);
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
        Schema::dropIfExists('kode_surat');
    }
}
