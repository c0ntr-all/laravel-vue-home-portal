<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->default(NULL);
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('content')->nullable();
            $table->boolean('common')->default(1);
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('music_tags')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_tags');
    }
}
