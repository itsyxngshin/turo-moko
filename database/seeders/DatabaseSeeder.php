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
        // Create one demo user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create multiple random users
        User::factory(5)->create();

        // Call other seeders
        $this->call([
            CourseSeeder::class,
            ActivitySeeder::class, // ✅ add this
        ]);
    }
}
