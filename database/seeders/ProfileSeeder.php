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
                [
                'photo_id' => null,
                'first_name' => 'Jose',
                'middle_name' => null,
                'last_name' => 'Reyes', 
                'created_at' => now(),
                'updated_at' => now(),
                ],
                [
                'photo_id' => null,
                'first_name' => 'Ana',
                'middle_name' => null,
                'last_name' => 'Garcia',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                [
                'photo_id' => null,
                'first_name' => 'Carlos',
                'middle_name' => null,
                'last_name' => 'Lopez',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                [
                'photo_id' => null,
                'first_name' => 'Luz',
                'middle_name' => null,
                'last_name' => 'Cruz',
                'created_at' => now(),
                'updated_at' => now(),
                ],

                [
                'photo_id' => null,
                'first_name' => 'Pedro',
                'middle_name' => 'Abad',
                'last_name' => 'Ramos',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                [
                'photo_id' => null,
                'first_name' => 'Rosa',
                'middle_name' => 'Binamira',
                'last_name' => 'Torres',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                [
                'photo_id' => null,
                'first_name' => 'Elena',
                'middle_name' => null,
                'last_name' => 'Perez',
                'created_at' => now(),
                'updated_at' => now(),
                ],
                [
                'photo_id' => null,
                'first_name' => 'Miguel',
                'middle_name' => null,
                'last_name' => 'Aquino',
                'created_at' => now(),
                'updated_at' => now(),
                ],

                [
                'photo_id' => null,
                'first_name' => 'Mico',
                'middle_name' => null,
                'last_name' => 'Diaz',
                'created_at' => now(),
                'updated_at' => now(),
                ],
            ]);
    }
}
