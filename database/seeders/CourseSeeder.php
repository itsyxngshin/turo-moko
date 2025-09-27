<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            [
            'implementer_id' => 1,
            'organization_id' => 1,
            'category_id' => 1,
            'subcat_id' => null,
            'cover_photo_id' => null,
            'course_title' => 'Disaster Risk Reduction Advocacy',
            'name' => 'DRR Advocacy',
            'background' => 'This course focuses on advocating for disaster risk reduction and preparedness.',
            'status' => 'Active',
            'visibility' => 'Visible',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'implementer_id' => 2,
            'organization_id' => 2,
            'category_id' => 2,
            'subcat_id' => null,
            'cover_photo_id' => null,
            'course_title' => 'Community Empowerment Strategies',
            'name' => 'Community Empowerment',
            'background' => 'This course provides strategies for empowering communities to achieve sustainable development.',
            'status' => 'Active',
            'visibility' => 'Visible',
            'start_date' => now(),
            'end_date' => now()->addMonths(4),
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'implementer_id' => 3,
            'organization_id' => null,
            'category_id' => 3,
            'subcat_id' => null,
            'cover_photo_id' => null,
            'course_title' => 'Advocacy for Social Change',
            'name' => 'Social Change Advocacy',
            'background' => 'Learn to advocate for social change and community development through effective strategies.',
            'status' => 'Active',
            'visibility' => 'Visible',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
