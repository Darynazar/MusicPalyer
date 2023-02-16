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
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->string('src')->nullable();
            $table->string('demo')->nullable();
            $table->string('title')->nullable();
            $table->string('cover')->nullable();

            $table->double('play')->nullable();
            $table->double('view')->nullable();
            $table->double('heart')->nullable();
            $table->boolean('top')->nullable();
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
        Schema::dropIfExists('music');
    }
};
