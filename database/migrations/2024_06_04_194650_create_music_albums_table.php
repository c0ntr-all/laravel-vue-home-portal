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
        Schema::create('music_albums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')
                  ->nullable()
                  ->default(NULL)
                  ->comment('Если есть переиздания');
            $table->unsignedBigInteger('album_type_id')
                  ->default(1);
            $table->string('name');
            $table->longText('description')
                  ->nullable()
                  ->default(NULL);
            $table->string('edition')
                  ->nullable()
                  ->default(NULL);
            $table->date('date')
                  ->nullable()
                  ->default(NULL);
            $table->boolean('is_date_verified')
                  ->default(false);
            $table->string('image')
                  ->nullable()
                  ->default(NULL);
            $table->string('path');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('album_type_id')
                  ->references('id')
                  ->on('music_album_types');
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
};
