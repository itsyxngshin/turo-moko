<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run()
    {
        $category = Category::first(); // make sure at least 1 category exists

        Subcategory::create([
            'category_id' => $category->id,
            'subcategory_name' => 'Frontend Development',
            'description' => 'Covers HTML, CSS, and JavaScript',
        ]);
    }
}

