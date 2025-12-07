<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\Lesson;
use Faker\Factory as Faker;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $lessons = Lesson::all();

        foreach ($lessons as $lesson) {
            Assignment::create([
                'lesson_id' => $lesson->id,
                'title' => $faker->sentence,
                'instruction' => $faker->paragraph,
                'status' => $faker->randomElement(['Open','Closed']),
                'start_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'end_date' => $faker->dateTimeBetween('+1 month', '+3 months'),
                'filetype_allowed' => $faker->boolean,
                'order' => $faker->numberBetween(1,5),
                'visibility' => $faker->randomElement(['Hidden','Active']),
                'post_date' => now(),
            ]);
        }
    }
}

