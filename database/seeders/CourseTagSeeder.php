<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get all existing courses
        $courses = DB::table('courses')->get();
        $now = now();

        // 2. Define specific tags based on keywords found in titles
        $keywordMap = [
            'Disaster' => ['Risk Mgmt', 'Safety', 'Resilience'],
            'Empowerment' => ['Leadership', 'Community', 'Development'],
            'Advocacy' => ['Social Justice', 'Policy', 'Activism'],
            'Climate' => ['Environment', 'Sustainability', 'Green'],
            'Digital' => ['Technology', 'Basics', 'Internet'],
            'First Aid' => ['Health', 'Emergency', 'Life Skills'],
            'Youth' => ['Future Leaders', 'Soft Skills', 'Training'],
            'Gardening' => ['Agriculture', 'Urban', 'DIY'],
            'Financial' => ['Finance', 'Budgeting', 'Money'],
            'Mental' => ['Wellness', 'Psychology', 'Self-care'],
            'Coding' => ['Programming', 'Web Dev', 'IT'],
            'Project' => ['Management', 'Planning', 'Admin'],
            'Child' => ['Human Rights', 'Legal', 'Protection'],
            'Speaking' => ['Communication', 'Confidence', 'Public Speaking'],
            'Cybersecurity' => ['Security', 'Data', 'Privacy'],
            'Gender' => ['Inclusivity', 'HR', 'Workplace'],
            'Writing' => ['Creative', 'Storytelling', 'Arts'],
            'Photography' => ['Visual Arts', 'Camera', 'Media'],
            'Conflict' => ['Peace', 'Mediation', 'HR'],
            'Social Media' => ['Marketing', 'Branding', 'Digital'],
            'Grant' => ['Non-profit', 'Funding', 'Proposal'],
            'Excel' => ['Data Analysis', 'Office', 'Productivity'],
            'Design' => ['Creativity', 'Graphics', 'Tools'],
        ];

        // 3. Loop through every course and assign tags
        foreach ($courses as $course) {
            $assignedTags = [];
            $foundMatch = false;

            // Check if the course title contains any of our keywords
            foreach ($keywordMap as $keyword => $tags) {
                if (stripos($course->course_title, $keyword) !== false) {
                    $assignedTags = $tags;
                    $foundMatch = true;
                    break; // Stop after first match to keep it simple
                }
            }

            // Fallback: If no keyword matched, give generic tags
            if (!$foundMatch) {
                $assignedTags = ['General', 'Skill Building', 'Education'];
            }

            // Insert the tags into the database
            foreach ($assignedTags as $tag) {
                DB::table('course_tags')->insert([
                    'course_id' => $course->id,
                    'tag' => $tag,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
