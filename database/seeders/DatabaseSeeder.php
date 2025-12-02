<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
    RolesTableSeeder::class,
    ProfileSeeder::class,         // profiles must exist before users
    UsersTableSeeder::class,
    OrganizationSeeder::class,
    CategorySeeder::class,
    SubCategorySeeder::class,
    CourseSeeder::class,          // courses must exist first
    LessonSeeder::class,          // lessons depend on courses
    AssignmentSeeder::class,      // assignments depend on lessons
]);



    }
}

