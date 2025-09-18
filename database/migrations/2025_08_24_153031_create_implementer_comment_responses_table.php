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
        Schema::create('implementer_comment_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollee_id')->constrained('course_enrollees');
            $table->foreignId('prog_rating_id')->constrained('implementer_evaluations');
            $table->foreignId('imp_comment_id')->constrained('implementer_comments');
            $table->string('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementer_comment_responses');
    }
};
