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
        Schema::create('course_enrollees', function (Blueprint $table) {
            $table->id();
             $table->foreignId('enrollee_id')->constrained('users');
            $table->foreignId('course_id')->constrained('courses');
            $table->dateTime('enrollment_date');
            $table->dateTime('completion_date')->nullable();
            $table->enum('status', ['Active', 'Completed', 'Dropped']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollees');
    }
};
