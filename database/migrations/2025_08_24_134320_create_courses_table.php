<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('set null');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('subcat_id')->nullable()->constrained('subcategories')->onDelete('set null');
            $table->foreignId('cover_photo_id')->nullable()->constrained('photos')->onDelete('set null');
            $table->string('course_title'); 
            $table->string('name'); 
            $table->text('background'); 
            $table->enum('status', ['archived', 'active', 'deleted', 'closed'])->default('active');
            $table->enum('visibility', ['visible', 'hidden'])->default('visible');
            $table->datetime('start_date'); 
            $table->datetime('end_date'); 
            $table->integer('student_limit')->default(20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
