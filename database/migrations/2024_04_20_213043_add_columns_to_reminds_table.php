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
        Schema::table('reminds', function (Blueprint $table) {
            $table->string('to_remind_before')->nullable()->default(NULL)->after('datetime');
            $table->boolean('is_regular')->default(false)->after('to_remind_before');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminds', function (Blueprint $table) {
            $table->dropColumn('boolean', 'is_regular');
            $table->dropColumn('string', 'to_remind_before');
        });
    }
};
