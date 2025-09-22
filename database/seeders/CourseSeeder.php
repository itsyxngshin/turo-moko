<?php

namespace Database\Seeders;

<<<<<<< Updated upstream
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
=======
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Active courses
        $webDev = Course::create([
            'implementer_id'   => 2,
            'organization_id'  => 1,
            'category_id'      => 1,
            'subcat_id'        => null,
            'cover_photo_id'   => null,
            'name'             => 'Introduction to Web Development',
            'background'       => 'images/web-dev.jpg',
            'status'           => 'active',
            'visibility'       => 'Visible',
            'start_date'       => Carbon::now(),
            'end_date'         => Carbon::now()->addMonths(3),
        ]);

        $digital = Course::create([
            'implementer_id'   => 2,
            'organization_id'  => 1,
            'category_id'      => 2,
            'subcat_id'        => null,
            'cover_photo_id'   => null,
            'name'             => 'Digital Skills 101',
            'background'       => 'images/digital-literacy.jpg',
            'status'           => 'active',
            'visibility'       => 'Visible',
            'start_date'       => Carbon::now(),
            'end_date'         => Carbon::now()->addMonths(6),
        ]);

        // Completed courses
        Course::create([
            'implementer_id'   => 2,
            'organization_id'  => 1,
            'category_id'      => 3,
            'subcat_id'        => null,
            'cover_photo_id'   => null,
            'course_title'     => 'Basic Computer Skills',
            'name'             => 'Computer Fundamentals',
            'background'       => 'images/computer-basics.jpg',
            'status'           => 'completed',
            'visibility'       => 'Visible',
            'start_date'       => Carbon::now()->subMonths(6),
            'end_date'         => Carbon::now()->subMonths(3),
        ]);

        Course::create([
            'implementer_id'   => 2,
            'organization_id'  => 1,
            'category_id'      => 4,
            'subcat_id'        => null,
            'cover_photo_id'   => null,
            'course_title'     => 'World History Overview',
            'name'             => 'History 101',
            'background'       => 'images/history.jpg',
            'status'           => 'completed',
            'visibility'       => 'Visible',
            'start_date'       => Carbon::now()->subMonths(8),
            'end_date'         => Carbon::now()->subMonths(5),
        ]);

        // Mark one course as recently accessed by user ID 1
        $user = User::find(1); // make sure this user exists
        if ($user) {
            $user->recentCourses()->attach($webDev->id, ['last_accessed' => Carbon::now()]);
        }
>>>>>>> Stashed changes
    }
}
