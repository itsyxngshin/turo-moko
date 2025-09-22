<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'id' => 1,
            'category_name' => 'Web Development',
            'category_description' => 'Courses related to HTML, CSS, JavaScript, and backend frameworks.',
        ]);

        Category::create([
            'id' => 2,
            'category_name' => 'Digital Literacy',
            'category_description' => 'Covers essential computer skills, internet usage, and digital safety.',
        ]);

        Category::create([
            'id' => 3,
            'category_name' => 'Science',
            'category_description' => 'Covers various scientific disciplines including physics, biology, and chemistry.',
        ]);

        Category::create([
            'id' => 4,
            'category_name' => 'History',
            'category_description' => 'Courses related to historical events, civilizations, and world history.',
        ]);
    }
}
