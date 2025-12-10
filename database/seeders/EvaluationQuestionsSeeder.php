<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EvaluationQuestionsSeeder extends Seeder
{
    /**
     * Seed the global evaluation questions.
     * These questions are managed by admins and used across all evaluations.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Program (Course) Rating Questions
        DB::table('program_ratings')->insert([
            [
                'statement' => 'How would you rate the overall course quality?',
                'status' => 'active',
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'statement' => 'How useful were the course materials and resources?',
                'status' => 'active',
                'order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'statement' => 'How well-organized was the course structure?',
                'status' => 'active',
                'order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'statement' => 'How engaging was the course content?',
                'status' => 'active',
                'order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Program (Course) Comment Questions
        DB::table('program_comments')->insert([
            [
                'description' => 'Additional comments about the course',
                'status' => 'active',
                'order' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Implementer Rating Questions
        DB::table('implementer_ratings')->insert([
            [
                'statement' => 'How would you rate the implementor\'s teaching effectiveness?',
                'status' => 'active',
                'order' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'statement' => 'How responsive was the implementor to questions and concerns?',
                'status' => 'active',
                'order' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'statement' => 'How clear were the implementor\'s explanations and instructions?',
                'status' => 'active',
                'order' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'statement' => 'How likely are you to take another course from this implementor?',
                'status' => 'active',
                'order' => 9,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Implementer Comment Questions
        DB::table('implementer_comments')->insert([
            [
                'description' => 'Additional feedback for the implementor',
                'status' => 'active',
                'order' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
