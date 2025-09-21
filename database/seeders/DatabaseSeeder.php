<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
        RolesTableSeeder::class,      // roles must exist first
        UsersTableSeeder::class,      // users depend on roles
        OrganizationSeeder::class,    // organizations before courses
        CategorySeeder::class,        // categories before subcategories
        SubCategorySeeder::class,     // subcategories before courses
        CourseSeeder::class,          // courses depend on all above
        CourseEnrolleesSeeder::class,
]);

    }
}

