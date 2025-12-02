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
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class, 
            SubcategorySeeder::class,
            ProfileSeeder::class,
            UserSeeder::class, // ✅ add this
            OrganizationSeeder::class,
            CourseSeeder::class,
            ConversationSeeder::class, 

        ]);
    }
}
