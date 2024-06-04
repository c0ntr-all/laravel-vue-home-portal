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
        Schema::create('music_album_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('album_id');
            $table->unsignedBigInteger('version_type_id')->default(1);
            $table->longText('description')->nullable()->default(NULL);
            $table->string('year')->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('album_id')
                  ->references('id')
                  ->on('music_albums')
                  ->cascadeOnDelete();

            $table->foreign('version_type_id')
                  ->references('id')
                  ->on('music_album_version_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_album_versions');
    }
};
