<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artist_music', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id');
            $table->unsignedBigInteger('music_id');

            // $table->foreign('artist_id')->references('id')->on('artists')->onUpdate('cascade');
            // $table->foreign('music_id')->references('id')->on('music')->onUpdate('cascade');

            $table->timestamps();

            $table->index('artist_id');
            $table->index('music_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artist_music');
    }
};
