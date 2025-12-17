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
        // Add order column to modules
        Schema::table('modules', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('module_number');
        });

        // Add order column to assignments
        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('course_id');
        });

        // Add order column to quizzes
        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('course_id');
        });

        // Add order column to program_evaluations
        Schema::table('program_evaluations', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('course_id');
        });

        // Add order column to announcements
        Schema::table('announcements', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('program_evaluations', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
