<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talent', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_alias');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->longText('tipe')->comment('json berisi ciri-ciri talent');
            $table->text('alamat');
            $table->longText('social_media')->comment('json berisi social media talent');
            $table->text('message');
            $table->longText('service')->comment('json service');
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
        Schema::dropIfExists('talent');
    }
}
