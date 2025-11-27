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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementer_id')->constrained('users');;
            $table->foreignId('organization_id')->nullable()->constrained('organizations');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('subcat_id')->nullable()->constrained('subcategories');
            $table->foreignId('cover_photo_id')->nullable();
            $table->string('name'); 
            $table->text('background'); 
            $table->enum('status', ['archived', 'active', 'deleted', 'closed'])->default('Active');
            $table->enum('visibility', ['visible', 'hidden'])->default('visible');
            $table->datetime('start_date'); 
            $table->datetime('end_date'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
