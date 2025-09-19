<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
     public function run(): void
    {
        Course::create([
            'implementer_id'   => 2, // must exist in users table
            'organization_id'  => 1, // must exist in organizations table
            'category_id'      => 1, // must exist in categories table
            'subcat_id'        => null,
            'cover_photo_id'   => null,
            'course_title'     => 'Web Development Basics',
            'name'             => 'Introduction to Web Development',
            'background'       => 'This course introduces learners to HTML, CSS, and JavaScript basics.',
            'status'           => 'Active',
            'visibility'       => 'Visible',
            'start_date'       => Carbon::now(),
            'end_date'         => Carbon::now()->addMonths(3),
        ]);

        Course::create([
            'implementer_id'   => 2,
            'organization_id'  => 1,
            'category_id'      => 2,
            'subcat_id'        => null,
            'cover_photo_id'   => null,
            'course_title'     => 'Digital Literacy Training',
            'name'             => 'Digital Skills 101',
            'background'       => 'Covers computer basics, internet navigation, and online safety.',
            'status'           => 'Active',
            'visibility'       => 'Visible',
            'start_date'       => Carbon::now(),
            'end_date'         => Carbon::now()->addMonths(6),
        ]);
    }
}
