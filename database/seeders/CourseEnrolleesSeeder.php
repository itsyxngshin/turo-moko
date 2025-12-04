<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Course;

class CourseEnrolleesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get learners (role_id = 1)
        $learners = User::where('role_id', 1)->get();

        // Get all courses
        $courses = Course::all();

        if ($learners->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('No learners or courses found, skipping CourseEnrolleesSeeder.');
            return;
        }

        foreach ($learners as $learner) {
            // Enroll each learner in 1–3 random courses
            $enrolledCourses = $courses->random(min(3, $courses->count()));

            foreach ($enrolledCourses as $course) {
                $status = collect(['Active', 'Completed', 'Dropped'])->random();

                $enrollmentDate = Carbon::now()->subDays(rand(1, 60));
                $completionDate = $status === 'Completed'
                    ? $enrollmentDate->copy()->addDays(rand(5, 30))
                    : null;

                DB::table('course_enrollees')->insert([
                    'enrollee_id'     => $learner->id,
                    'course_id'       => $course->id,
                    'enrollment_date' => $enrollmentDate,
                    'completion_date' => $completionDate,
                    'status'          => $status,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }

        $this->command->info('Course enrollees seeded successfully!');
    }
}
