<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                [
                    'username' => 'topalatui',
                    'full_name' => 'Topalat Web Client',
                    'email' => 'topalatonline@gmail.com',
                    'is_staff' => true,
                    'is_superuser' => true,
                    'is_active' => true,
                    'is_client' => true,
                    'password' => Hash::make('12345678'),
                    'updated_at' => now()->timestamp,
                    'created_at' => now()->timestamp,
                ],
                [
                    'username' => 'devshittu',
                    'full_name' => 'Muhammed Shittu',
                    'email' => 'devshittu@gmail.com',
                    'is_staff' => true,
                    'is_superuser' => true,
                    'is_active' => true,
                    'is_client' => false,
                    'password' => Hash::make('12345678'),
                    'updated_at' => now()->timestamp,
                    'created_at' => now()->timestamp,
                ],
                [
                    'username' => 'runcie',
                    'full_name' => 'Runcie Adejoh',
                    'email' => 'runcie4real@gmail.com',
                    'is_staff' => true,
                    'is_superuser' => true,
                    'is_active' => true,
                    'is_client' => false,
                    'password' => Hash::make('12345678'),
                    'updated_at' => now()->timestamp,
                    'created_at' => now()->timestamp,
                ],
            ]
        );
    }
}
