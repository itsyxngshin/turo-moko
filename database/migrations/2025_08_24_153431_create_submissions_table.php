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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollee_id')->constrained('users');
            $table->foreignId('assignment_id')->constrained('assignments');
            $table->text('instruction')->nullable();
            $table->binary('attachment')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->boolean('visibility')->default(true);
            $table->timestamp('post_date')->nullable(); 
            $table->timestamp('edit_date')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
