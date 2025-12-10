<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data from program_ratings to evaluation_questions
        DB::table('program_ratings')->orderBy('order')->get()->each(function ($rating) {
            DB::table('evaluation_questions')->insert([
                'type' => 'program_rating',
                'text' => $rating->statement,
                'order' => $rating->order,
                'status' => $rating->status,
                'created_at' => $rating->created_at,
                'updated_at' => $rating->updated_at,
            ]);
        });

        // Migrate data from program_comments to evaluation_questions
        DB::table('program_comments')->orderBy('order')->get()->each(function ($comment) {
            DB::table('evaluation_questions')->insert([
                'type' => 'program_comment',
                'text' => $comment->description,
                'order' => $comment->order,
                'status' => $comment->status,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
            ]);
        });

        // Migrate data from implementer_ratings to evaluation_questions
        DB::table('implementer_ratings')->orderBy('order')->get()->each(function ($rating) {
            DB::table('evaluation_questions')->insert([
                'type' => 'implementer_rating',
                'text' => $rating->statement,
                'order' => $rating->order,
                'status' => $rating->status,
                'created_at' => $rating->created_at,
                'updated_at' => $rating->updated_at,
            ]);
        });

        // Migrate data from implementer_comments to evaluation_questions
        DB::table('implementer_comments')->orderBy('order')->get()->each(function ($comment) {
            DB::table('evaluation_questions')->insert([
                'type' => 'implementer_comment',
                'text' => $comment->description,
                'order' => $comment->order,
                'status' => $comment->status,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
            ]);
        });

        // Drop old tables (commented out for safety - uncomment when ready)
        // Schema::dropIfExists('program_ratings');
        // Schema::dropIfExists('program_comments');
        // Schema::dropIfExists('implementer_ratings');
        // Schema::dropIfExists('implementer_comments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate old tables if needed
        // This is complex - better to restore from backup if rollback is needed
        DB::table('evaluation_questions')->delete();
    }
};
