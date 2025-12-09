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
        Schema::table('submissions', function (Blueprint $table) {
            $table->decimal('grade', 5, 2)->nullable()->after('attachment_original_name');
            $table->text('feedback')->nullable()->after('grade');
            $table->foreignId('graded_by')->nullable()->constrained('users')->after('feedback');
            $table->timestamp('graded_at')->nullable()->after('graded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);
            $table->dropColumn(['grade', 'feedback', 'graded_by', 'graded_at']);
        });
    }
};
