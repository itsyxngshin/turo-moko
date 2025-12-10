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
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['program_rating', 'program_comment', 'implementer_rating', 'implementer_comment']);
            $table->text('text');
            $table->integer('order')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['type', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};
