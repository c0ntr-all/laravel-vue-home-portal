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
        Schema::create('music_tagables', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('tagable_id');
            $table->string('tagable_type');
            $table->unsignedBigInteger('tag_group_id')->nullable()->default(NULL);
            $table->timestamps();

            $table->primary(['tag_id', 'tagable_id', 'tagable_type']);

            $table->foreign('tag_id')
                ->references('id')
                ->on('music_tags')
                ->cascadeOnDelete();

            $table->foreign('tag_group_id')
                  ->references('id')
                  ->on('music_tag_groups');
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
};
