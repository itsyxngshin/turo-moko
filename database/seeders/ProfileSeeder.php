<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create([
            'id' => 1,
            'first_name' => 'Admin',
            'last_name' => 'User',
        ]);

        Profile::create([
            'id' => 2,
            'first_name' => 'Instructor',
            'last_name' => 'User',
        ]);

        Profile::create([
            'id' => 3,
            'first_name' => 'Learner',
            'last_name' => 'User',
        ]);
    }
}
