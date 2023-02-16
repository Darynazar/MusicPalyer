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
        Schema::create('feat_music', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('feat_id');
            $table->unsignedBigInteger('music_id');

            // $table->foreign('feat_id')->references('id')->on('feats')->onUpdate('cascade');
            // $table->foreign('music_id')->references('id')->on('music')->onUpdate('cascade');

            $table->timestamps();

            $table->index('feat_id');
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
        Schema::dropIfExists('feat_music');
    }
};
