<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'World Vision Philippines',
                'description' => 'A humanitarian organization committed to helping children live life at its fullness',
            ],

            [
                'name' => 'UNICEF Philippines',
                'description' => 'A humanitarian organization committed to helping children live life at its fullness',
            ],
        ];

        DB::table('organizations')->insert($organizations);
    }
}
