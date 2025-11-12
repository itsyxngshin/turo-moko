<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('implementor_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('learner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('implementer_id')->constrained('users')->cascadeOnDelete();

            $table->tinyInteger('teaching_effectiveness_rating');
            $table->tinyInteger('responsiveness_rating');
            $table->tinyInteger('explanation_clarity_rating');
            $table->tinyInteger('recommendation_rating');

            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('implementor_feedbacks');
    }
};