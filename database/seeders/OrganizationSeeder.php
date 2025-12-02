<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        Organization::create([
            'id'          => 1, // So CourseSeeder can reference it
            'name'        => 'Default Organization',
            'description' => 'This is the default seeded organization for testing.',
        ]);
    }
}
