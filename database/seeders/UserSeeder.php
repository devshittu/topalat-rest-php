<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
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
        $arrayString = Config::get('constants.app.init_users');
        $myArray = [];
        eval("\$myArray = $arrayString;");
        DB::table('users')->insert($myArray);
        /*
        DB::table('users')->insert([
                [
                    'username' => 'devshittu',
                    'full_name' => 'Muhammed Shittu',
                    'email' => 'devshittu@gmail.com',
                    'is_staff' => true,
                    'is_superuser' => true,
                    'is_active' => true,
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
                    'password' => Hash::make('12345678'),
                    'updated_at' => now()->timestamp,
                    'created_at' => now()->timestamp,
                ],
            ]
        );*/
    }
}
