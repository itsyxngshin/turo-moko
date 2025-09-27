<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
            'category_name' => 'Advocacy',
            'category_description' => 'Promoting and supporting causes or policies for social change',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'category_name' => 'Social Justice',
            'category_description' => 'Advancing equality and fairness in society',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'category_name' => 'Environmental Advocacy',
            'category_description' => 'Protecting and preserving the environment',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'category_name' => 'Human Rights',
            'category_description' => 'Defending and promoting fundamental rights and freedoms',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'category_name' => 'Community Development',
            'category_description' => 'Empowering communities to achieve sustainable growth',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'category_name' => 'Education Advocacy',
            'category_description' => 'Promoting access to quality education for all',
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
