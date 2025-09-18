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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons');
            $table->string('title');
            $table->text('instruction');
            $table->enum('status', ['Open', 'Closed']);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('filetype_allowed')->default(true);
            $table->integer('order')->nullable();
            $table->enum('visibility', ['Hidden', 'Active'])->default(true);
            $table->timestamp('post_date')->nullable();
            $table->timestamps();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
