<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'Learner',
                'role_description' => 'Default role for learners who can access courses and assessments.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Instructor',
                'role_description' => 'Instructors can create courses, upload materials, and manage learners.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Admin',
                'role_description' => 'Administrators manage the entire system including users, courses, and roles.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
