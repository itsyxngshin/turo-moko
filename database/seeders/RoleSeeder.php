<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['role_name' => 'learner', 'role_description' => 'Regular user with limited access', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'implementer', 'role_description' => 'User with permissions to create course', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'admin', 'role_description' => 'Administrator with full access', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
