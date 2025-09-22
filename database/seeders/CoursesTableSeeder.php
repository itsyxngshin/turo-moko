<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;


class CoursesTableSeeder extends Seeder
{
    public function run(): void
    {
        Course::truncate(); // clear old data

        Course::create([
            'name' => 'Web Development 101',
            'subject' => 'Computer Science',
            'description' => 'Intro to HTML, CSS, JS',
            'completed' => false,
        ]);

        Course::create([
            'name' => 'Database Systems',
            'subject' => 'Information Technology',
            'description' => 'Relational DB and SQL',
            'completed' => true, // ✅ completed course
        ]);

        Course::create([
            'name' => 'Software Engineering',
            'subject' => 'Computer Science',
            'description' => 'Agile, SDLC, UML',
            'completed' => true, // ✅ completed course
        ]);
    }
}

