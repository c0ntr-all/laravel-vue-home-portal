<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('album_id');
            $table->integer('number');
            $table->string('name');
            $table->integer('cd')->nullable();
            $table->string('path_windows');
            $table->time('duration');
            $table->integer('bitrate');

            $table->timestamps();

            $table->foreign('album_id')
                  ->references('id')
                  ->on('music_albums')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_tracks');
    }
}
