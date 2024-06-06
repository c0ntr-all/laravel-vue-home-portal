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
        Schema::create('music_playlist_track', function (Blueprint $table) {
            $table->unsignedBigInteger('idx')->comment('Номер по порядку');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('track_id');
            $table->unsignedBigInteger('playlist_id');
            $table->timestamps();

            $table->unique(['playlist_id', 'idx'], 'unique_playlist_idx');

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
        Schema::dropIfExists('music_playlist_track');
    }
};
