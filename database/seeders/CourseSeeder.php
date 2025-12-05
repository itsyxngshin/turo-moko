<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
     public function run(): void
    {
        $now = now();

        DB::table('courses')->insert([
            [
                'implementer_id' => 1, 'organization_id' => 1, 'category_id' => 1, // Advocacy
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Disaster Risk Reduction Advocacy',
                
                'background' => 'This course focuses on advocating for disaster risk reduction and preparedness.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-01-15 08:00:00', 'end_date' => '2025-04-15 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            [
                'implementer_id' => 2, 'organization_id' => 2, 'category_id' => 5, // Community Dev
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Community Empowerment Strategies',
                'background' => 'This course provides strategies for empowering communities to achieve sustainable development.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-02-01 09:00:00', 'end_date' => '2025-06-30 16:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            [
                'implementer_id' => 3, 'organization_id' => null, 'category_id' => 2, // Social Justice
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Advocacy for Social Change',
                'background' => 'Learn to advocate for social change and community development through effective strategies.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-03-10 10:00:00', 'end_date' => '2025-07-20 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            [
                'implementer_id' => 1, 'organization_id' => 1, 'category_id' => 3, // Environmental
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Climate Change Adaptation 101',
                'background' => 'Understanding the basics of climate change and how communities can adapt to shifting weather patterns.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-04-05 08:00:00', 'end_date' => '2025-08-05 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            [
                'implementer_id' => 2, 'organization_id' => 2, 'category_id' => 8, // Technology
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Digital Literacy for Seniors',
                'background' => 'Helping the elderly navigate modern technology, smartphones, and basic internet safety.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-05-01 09:30:00', 'end_date' => '2025-05-31 15:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            [
                'implementer_id' => 1, 'organization_id' => 1, 'category_id' => 7, // Health
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Basic First Aid & CPR',
                'background' => 'Essential life-saving skills for emergencies, including CPR and wound care.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-06-12 08:00:00', 'end_date' => '2025-06-14 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 7. Governance
            [
                'implementer_id' => 3, 'organization_id' => null, 'category_id' => 11, // Governance
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Youth Leadership Bootcamp',
                'background' => 'Developing the next generation of leaders through teamwork, public speaking, and ethics training.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-07-20 08:00:00', 'end_date' => '2025-07-25 20:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 8. Environmental
            [
                'implementer_id' => 2, 'organization_id' => 2, 'category_id' => 3, // Environmental
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Sustainable Urban Gardening',
                'background' => 'How to grow your own food in small urban spaces using sustainable methods.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-08-01 07:00:00', 'end_date' => '2025-10-01 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 9. Finance
            [
                'implementer_id' => 1, 'organization_id' => 1, 'category_id' => 10, // Business
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Financial Literacy 101',
                'background' => 'Managing personal finances, budgeting, and understanding debt for young professionals.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-08-15 18:00:00', 'end_date' => '2025-09-15 20:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 10. Health
            [
                'implementer_id' => 3, 'organization_id' => null, 'category_id' => 7, // Health
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Mental Health Awareness',
                'background' => 'Recognizing signs of burnout and anxiety, and how to support colleagues and friends.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-09-10 09:00:00', 'end_date' => '2025-09-10 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 11. Tech
            [
                'implementer_id' => 2, 'organization_id' => 2, 'category_id' => 8, // Tech
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Introduction to Coding',
                'background' => 'A beginner friendly course on HTML, CSS, and the basics of web development.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-10-01 13:00:00', 'end_date' => '2025-12-20 16:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 12. Governance
            [
                'implementer_id' => 1, 'organization_id' => 1, 'category_id' => 11, // Governance
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Project Management for NGOs',
                'background' => 'Learn the lifecycle of a project, from proposal writing to monitoring and evaluation.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-10-15 09:00:00', 'end_date' => '2025-11-30 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 13. Human Rights
            [
                'implementer_id' => 2, 'organization_id' => 2, 'category_id' => 4, // Human Rights
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Child Rights Protection',
                'background' => 'Understanding legal frameworks and practical approaches to protecting children in the community.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-11-05 08:30:00', 'end_date' => '2025-11-07 16:30:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 14. Governance / Arts (Public Speaking)
            [
                'implementer_id' => 3, 'organization_id' => null, 'category_id' => 11, // Governance/Leadership
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Effective Public Speaking',
                'background' => 'Overcome stage fright and learn to deliver compelling presentations.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2025-12-01 10:00:00', 'end_date' => '2025-12-15 12:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 15. Tech
            [
                'implementer_id' => 1, 'organization_id' => 1, 'category_id' => 8, // Tech
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Cybersecurity Essentials',
                'background' => 'Protecting yourself and your organization from phishing, malware, and data breaches.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-01-10 09:00:00', 'end_date' => '2026-02-10 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),

            ],
            // 16. Social Justice
            [
                'implementer_id' => 2, 'organization_id' => 2, 'category_id' => 2, // Social Justice
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Gender Equality in the Workplace',
                'background' => 'Promoting inclusivity and understanding gender dynamics in professional settings.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-02-15 08:00:00', 'end_date' => '2026-02-16 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 17. Arts
            [
                'implementer_id' => 3, 'organization_id' => null, 'category_id' => 9, // Arts
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Creative Writing Workshop',
                'background' => 'Unleash your creativity through guided writing exercises and storytelling techniques.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-03-01 18:00:00', 'end_date' => '2026-04-01 20:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 18. Arts
            [
                'implementer_id' => 1, 'organization_id' => 1, 'category_id' => 9, // Arts
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Basics of Photography',
                'background' => 'Learn composition, lighting, and camera settings to take stunning photos.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-03-15 09:00:00', 'end_date' => '2026-03-20 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 19. Community Dev
            [
                'implementer_id' => 2, 'organization_id' => 2, 'category_id' => 5, // Community Dev
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Conflict Resolution Skills',
                'background' => 'Techniques for de-escalating arguments and finding win-win solutions in conflicts.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-04-10 08:00:00', 'end_date' => '2026-04-12 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 20. Business / Marketing
            [
                'implementer_id' => 3, 'organization_id' => null, 'category_id' => 10, // Business
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Social Media Marketing',
                'background' => 'Building a brand presence online using Facebook, Instagram, and TikTok.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-05-01 10:00:00', 'end_date' => '2026-06-01 16:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 21. Business
            [
                'implementer_id' => 1, 'organization_id' => 1, 'category_id' => 10, // Business
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Grant Writing for Non-Profits',
                'background' => 'How to write successful grant proposals to secure funding for your organization.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-06-15 09:00:00', 'end_date' => '2026-06-30 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 22. Tech
            [
                'implementer_id' => 2, 'organization_id' => 2, 'category_id' => 8, // Tech
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Data Analysis with Excel',
                'background' => 'Mastering pivot tables, VLOOKUP, and charts to analyze data effectively.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-07-01 13:00:00', 'end_date' => '2026-07-15 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
            // 23. Arts / Tech
            [
                'implementer_id' => 3, 'organization_id' => null, 'category_id' => 9, // Arts
                'subcat_id' => null, 'cover_photo_id' => null,
                'course_title' => 'Basic Graphic Design',
                'background' => 'Introduction to design principles and using tools like Canva for marketing.',
                'status' => 'Active', 'visibility' => 'Visible',
                'start_date' => '2026-08-05 09:00:00', 'end_date' => '2026-08-25 17:00:00',
                'created_at' => $now, 'updated_at' => $now,
                'course_code' => Course::generateUniqueCode(),
            ],
        ]); 
    }
}
