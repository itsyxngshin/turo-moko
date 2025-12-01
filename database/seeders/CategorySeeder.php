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
             'category_name' => 'Advocacy',
            'category_description' => 'Promoting and supporting causes or policies for social change',
        ]);

        Category::create([
            'id' => 2,
             'category_name' => 'Social Justice',
            'category_description' => 'Advancing equality and fairness in society',
        ]);
    }
}
