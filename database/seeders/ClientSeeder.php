<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('clients')->insert([
            [
                'username' => 'topalatwebui',
                'name' => 'Topalat Web Client',
                'email' => 'topalatonline@gmail.com',
                'is_active' => true,
                'password' => Hash::make('12345678'),
                'updated_at' => now()->timestamp,
                'created_at' => now()->timestamp,
            ],
//            [
//                'username' => 'topalatmobileui',
//                'name' => 'Topalat Mobile Client',
//                'email' => 'devshittu@gmail.com',
//                'is_active' => true,
//                'password' => Hash::make('12345678'),
//                'updated_at' => now()->timestamp,
//                'created_at' => now()->timestamp,
//            ],
        ]);
    }
}
