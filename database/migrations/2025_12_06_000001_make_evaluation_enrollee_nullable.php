<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make enrollee_id optional for evaluation templates so they are course-scoped,
     * not tied to a specific learner record.
     */
    public function up(): void
    {
        Schema::table('program_evaluations', function (Blueprint $table) {
            $table->dropForeign(['enrollee_id']);
            $table->unsignedBigInteger('enrollee_id')->nullable()->change();
        });

        Schema::table('implementer_evaluations', function (Blueprint $table) {
            $table->dropForeign(['enrollee_id']);
            $table->unsignedBigInteger('enrollee_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('program_evaluations', function (Blueprint $table) {
            $table->unsignedBigInteger('enrollee_id')->nullable(false)->change();
            $table->foreign('enrollee_id')->references('id')->on('course_enrollees');
        });

        Schema::table('implementer_evaluations', function (Blueprint $table) {
            $table->unsignedBigInteger('enrollee_id')->nullable(false)->change();
            $table->foreign('enrollee_id')->references('id')->on('course_enrollees');
        });
    }
};

