<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'juan_delacruz',
                'email' => 'juandelacruz@turo-moko.com',
                'phonenum' => '+639123456789',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'maria_santos',
                'email' => 'mariasantos@turo-moko.com',
                'phonenum' => '+639987654321',
                'password' => Hash::make('password123'),
                'role_id' => 2,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'jose_reyes',
                'email' => 'josereyes@turo-moko.com',
                'phonenum' => '+639112233445',
                'password' => Hash::make('securepass'),
                'role_id' => 3,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'ana_garcia',
                'email' => 'anagarcia@turo-moko.com',
                'phonenum' => '+639556677889',
                'password' => Hash::make('mypassword'),
                'role_id' => 1,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'carlos_lopez',
                'email' => 'carloslopez@turo-moko.com',
                'phonenum' => '+639998877665',
                'password' => Hash::make('adminpass'),
                'role_id' => 2,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'luz_cruz',
                'email' => 'luzcruz@turo-moko.com',
                'phonenum' => '+639334455667',
                'password' => Hash::make('userpass'),
                'role_id' => 3,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'pedro_ramos',
                'email' => 'pedroramos@turo-moko.com',
                'phonenum' => '+639776655443',
                'password' => Hash::make('password456'),
                'role_id' => 1,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'rosa_torres',
                'email' => 'rosatorres@turo-moko.com',
                'phonenum' => '+639223344556',
                'password' => Hash::make('mypassword123'),
                'role_id' => 2,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'elena_flores',
                'email' => 'elenaflores@turo-moko.com',
                'phonenum' => '+639665544332',
                'password' => Hash::make('secure123'),
                'role_id' => 3,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'miguel_aquino',
                'email' => 'miguelaquino@turo-moko.com',
                'phonenum' => '+639887766554',
                'password' => Hash::make('adminsecure'),
                'role_id' => 1,
                'profile_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
