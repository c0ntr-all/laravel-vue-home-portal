<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_albums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id');
            $table->integer('year');
            $table->integer('cd')->nullable()->default(NULL);
            $table->string('name');
            $table->string('attributes')->nullable();
            $table->string('edition')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable()->default(NULL);
            $table->string('path');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['artist_id', 'name', 'cd'], 'idx_name_cd_unique');
            $table->foreign('artist_id')
                  ->references('id')
                  ->on('music_artists')
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
        Schema::dropIfExists('music_albums');
    }
}
