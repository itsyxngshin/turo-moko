<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // Delete old assignments safely
        Assignment::query()->delete();

        $now = Carbon::now();

        Assignment::create([
            'lesson_id' => 1,
            'title' => 'Sample Assignment 1',
            'instruction' => 'Complete the exercises in Lesson 1.',
            'status' => 'open',
            'start_date' => $now->subDays(1),
            'end_date' => $now->addDays(3),
            'filetype_allowed' => 'pdf,docx',
            'order' => 1,
            'visibility' => 1,
            'post_date' => $now->subDay(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Assignment::create([
            'lesson_id' => 2,
            'title' => 'Research Paper',
            'instruction' => 'Write a 3-page research paper on cybersecurity.',
            'status' => 'open',
            'start_date' => $now,
            'end_date' => $now->addWeek(),
            'filetype_allowed' => 'pdf,docx',
            'order' => 2,
            'visibility' => 1,
            'post_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Assignment::create([
            'lesson_id' => 1,
            'title' => 'Quiz #1',
            'instruction' => 'Complete the quiz before the deadline.',
            'status' => 'open',
            'start_date' => $now->subDays(2),
            'end_date' => $now->addDays(1),
            'filetype_allowed' => '',
            'order' => 3,
            'visibility' => 1,
            'post_date' => $now->subDays(2),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
