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
        Schema::table('questions', function (Blueprint $table) {
            // Update the enum to include new question types
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer', 'long_answer', 'essay'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('type', ['multiple_choice', 'true_false', 'essay'])->change();
        });
    }
};
