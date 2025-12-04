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
        Schema::create('announcement_attachments', function (Blueprint $table) {
            $table->id();
            
            // Relationship: belongs to an announcement
            $table->foreignId('announcement_id')
                  ->constrained('announcements')
                  ->onDelete('cascade');

            // File details
            $table->string('file_path');         // e.g. course_attachments/orientation.pdf
            $table->string('original_name')->nullable(); // e.g. orientation.pdf
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_attachments');
    }
};
