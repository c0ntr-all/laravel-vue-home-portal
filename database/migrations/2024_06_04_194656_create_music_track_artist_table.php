<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('music_track_artist', function (Blueprint $table) {
            $table->unsignedBigInteger('track_id');
            $table->unsignedBigInteger('artist_id');
            $table->boolean('is_author')->default(1);

            $table->primary(['artist_id', 'track_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_track_artist');
    }
};
