<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicTagablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_tagables', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('tagable_id');
            $table->string('tagable_type');
            $table->tinyInteger('priority')
                  ->default(1)
                  ->comment('Добавлен тег как основной или нет');;

            $table->primary(['tag_id', 'tagable_id', 'tagable_type']);

            $table->foreign('tag_id')
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
        Schema::dropIfExists('music_tagables');
    }
}
