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
            $table->integer('user_id');
            $table->integer('artist_id');
            $table->string('name');
            $table->integer('year');
            $table->text('content')->nullable();
            $table->string('image')->nullable()->default(NULL);
            $table->timestamps();
            $table->softDeletes();
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
