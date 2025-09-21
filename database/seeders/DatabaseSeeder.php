<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        RolesTableSeeder::class,      // roles must exist first
        UsersTableSeeder::class,      // users depend on roles
        OrganizationSeeder::class,    // organizations before courses
        CategorySeeder::class,        // categories before subcategories
        SubCategorySeeder::class,     // subcategories before courses
        CourseSeeder::class,          // courses depend on all above
        UserSeeder::class,
        CoursesTableSeeder::class,
]);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
