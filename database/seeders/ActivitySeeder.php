<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        Activity::create([
            'name' => 'Intro to Laravel Assignment',
            'description' => 'Write a short essay about MVC architecture.',
            'opened_at' => Carbon::now(),
            'due_at' => Carbon::now()->addDays(5),
        ]);

        Activity::create([
            'name' => 'Database Design Quiz',
            'description' => 'Multiple-choice quiz on database normalization.',
            'opened_at' => Carbon::now(),
            'due_at' => Carbon::now()->addDays(7),
        ]);
    }
}
