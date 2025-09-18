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
        Schema::create('rating_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollee_id')->constrained('course_enrollees');
            $table->foreignId('prog_rating_id')->constrained('program_ratings');
            $table->integer('rating_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_responses');
    }
};
