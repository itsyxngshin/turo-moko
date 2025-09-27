<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('profiles')->insert([
                [
                'photo_id' => null,
                'first_name' => 'Juan',
                'middle_name' => 'A.',
                'last_name' => 'Dela Cruz',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                [
                'photo_id' => null,
                'first_name' => 'Maria',
                'middle_name' => null,
                'last_name' => 'Santos',
                'created_at' => now(),
                'updated_at' => now(),
                ],
            ]);
    }
}
