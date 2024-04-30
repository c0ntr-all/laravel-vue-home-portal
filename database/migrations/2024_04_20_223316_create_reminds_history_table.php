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
        Schema::create('reminds_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remind_id');
            $table->text('comment')->nullable()->default(NULL);
            $table->datetime('completed_at');
            $table->timestamps();

            $table->foreign('remind_id')
                  ->references('id')
                  ->on('reminds')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminds_history');
    }
};
