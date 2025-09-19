<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'profile_id' => 1, // You need to ensure a profile with id=1 exists
                'role_id' => 3, // Admin
                'username' => 'admin',
                'email' => 'admin@example.com',
                'phonenum' => '09171234567',
                'password' => Hash::make('password'), // default password
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Instructor User',
                'profile_id' => 2, // You need to ensure profile exists
                'role_id' => 2, // Instructor
                'username' => 'instructor',
                'email' => 'instructor@example.com',
                'phonenum' => '09179876543',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Learner User',
                'profile_id' => 3,
                'role_id' => 1, // Learner
                'username' => 'learner',
                'email' => 'learner@example.com',
                'phonenum' => '09170000000',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
