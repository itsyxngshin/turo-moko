<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'World Vision Philippines',
                'description' => 'A humanitarian organization committed to helping children live life at its fullness',
                'color' => 'orange',  
            ],

            [
                'name' => 'UNICEF Philippines',
                'description' => 'A humanitarian organization committed to helping children live life at its fullness',
                'color' => 'blue',
            ],

            [
                'name' => 'Greenpeace Philippines',
                'description' => 'A humanitarian organization committed to helping children live life at its fullness',
                'color' => 'green',
            ],

            [
                'name' => 'Kabataang Resilient Network',
                'description' => 'A humanitarian organization committed to helping children live life at its fullness',
                'color' => 'yellow', 
            ],
        ];

        DB::table('organizations')->insert($organizations);
    }
}
