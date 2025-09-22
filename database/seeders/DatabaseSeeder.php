<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< Updated upstream
        // Create one demo user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
=======
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
>>>>>>> Stashed changes

        // Create multiple random users
        User::factory(5)->create();

        // Call other seeders
        $this->call([
            CourseSeeder::class,
            ActivitySeeder::class, // ✅ add this
        ]);
    }
}
