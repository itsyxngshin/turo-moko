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
        // Drop foreign key constraints so we can use evaluation_questions IDs instead
        Schema::table('rating_responses', function (Blueprint $table) {
            $table->dropForeign(['prog_rating_id']);
        });
        
        Schema::table('comment_responses', function (Blueprint $table) {
            $table->dropForeign(['prog_comment_id']);
        });
        
        Schema::table('implementer_rating_responses', function (Blueprint $table) {
            $table->dropForeign(['imp_rating_id']);
        });
        
        Schema::table('implementer_comment_responses', function (Blueprint $table) {
            $table->dropForeign(['imp_comment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rating_responses', function (Blueprint $table) {
            $table->foreign('prog_rating_id')->references('id')->on('program_ratings');
        });
        
        Schema::table('comment_responses', function (Blueprint $table) {
            $table->foreign('prog_comment_id')->references('id')->on('program_comments');
        });
        
        Schema::table('implementer_rating_responses', function (Blueprint $table) {
            $table->foreign('imp_rating_id')->references('id')->on('implementer_ratings');
        });
        
        Schema::table('implementer_comment_responses', function (Blueprint $table) {
            $table->foreign('imp_comment_id')->references('id')->on('implementer_comments');
        });
    }
};
