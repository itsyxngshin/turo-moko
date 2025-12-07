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
        Schema::table('courses', function (Blueprint $table) {
            // Add course_code if it doesn't exist
            if (!Schema::hasColumn('courses', 'course_code')) {
                $table->string('course_code', 10)->unique()->after('id');
            }
            
            // Add course_title if it doesn't exist
            if (!Schema::hasColumn('courses', 'course_title')) {
                $table->string('course_title')->after('name');
            }
            
            // Add cover_photo_id if it doesn't exist (nullable for now)
            if (!Schema::hasColumn('courses', 'cover_photo_id')) {
                $table->foreignId('cover_photo_id')->nullable()->after('subcat_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'course_code')) {
                $table->dropColumn('course_code');
            }
            if (Schema::hasColumn('courses', 'course_title')) {
                $table->dropColumn('course_title');
            }
            if (Schema::hasColumn('courses', 'cover_photo_id')) {
                $table->dropColumn('cover_photo_id');
            }
        });
    }
};
