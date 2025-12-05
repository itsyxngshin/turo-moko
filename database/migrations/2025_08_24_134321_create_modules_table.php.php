<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
    $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
    $table->integer('module_number');
    $table->string('module_title');
    $table->text('description')->nullable();
    
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
