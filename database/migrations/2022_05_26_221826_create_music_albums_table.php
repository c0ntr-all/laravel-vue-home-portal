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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('artist_id');
            $table->integer('year');
            $table->string('name');
            $table->string('type')->default(NULL);
            $table->enum('version', ['instrumental', 'remaster', 'remix', 'live'])->nullable()->default(NULL);
            $table->string('edition')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('path_windows');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
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
