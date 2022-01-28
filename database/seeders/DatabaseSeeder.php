<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\User::factory(2)->create();
//         \App\Models\TransactionLog::factory(5)->create();
//         \App\Models\Contact::factory(50)->create();

        $this->call([
            ClientSeeder::class,
            UserSeeder::class,
            AppPreferenceSeeder::class,
//            TransactionLogSeeder::class,
//            ContactSeeder::class,
        ]);
    }
}
