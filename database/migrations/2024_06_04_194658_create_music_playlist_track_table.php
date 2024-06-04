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
        Schema::create('music_playlists_tracks', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('track_id');
            $table->unsignedBigInteger('playlist_id');
            $table->timestamps();

            $table->primary(['artist_id', 'album_id']);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');

            $table->foreign('track_id')
                  ->references('id')
                  ->on('music_tracks');

            $table->foreign('playlist_id')
                  ->references('id')
                  ->on('music_playlists')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_playlists_items');
    }
};
