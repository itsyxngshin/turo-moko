<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            #UsersTableSeeder::class,
            CategorySeeder::class, 
            SubcategorySeeder::class,
            ProfileSeeder::class,
            UserSeeder::class, // ✅ add this
            OrganizationSeeder::class,
            CourseSeeder::class,
            CourseTagSeeder::class,
            LessonSeeder::class,          // lessons depend on courses
            AssignmentSeeder::class,  
            CourseEnrolleesSeeder::class,
            ConversationSeeder::class, 
            MessageSeeder::class,
            EvaluationQuestionsSeeder::class,
        ]);
    }
}

