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
        Schema::table('assignments', function (Blueprint $table) {
            // Add course_id column
            $table->foreignId('course_id')->after('id')->constrained('courses')->onDelete('cascade');
            
            // Make lesson_id nullable
            $table->foreignId('lesson_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Drop course_id foreign key and column
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
            
            // Revert lesson_id to not nullable (if needed)
            $table->foreignId('lesson_id')->nullable(false)->change();
        });
    }
};
