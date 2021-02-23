<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('app_preferences')->insert([
                [
                    'profile_key' => 'default',
                    'settings' => json_encode([
                        'app_name' => 'Topalat Nigeria',
                        'services_rate' => 80,
                        'services' => [
                            'airtime' => true,
                            'databundle' => true,
                            'cabletv' => true,
                            'power' => true,
                        ],
                        'announcement' => [
                            'show' => true,
                            'type' => 'is-info',
                            'size' => 'is-medium',
                            'message' => 'Please note that this site is still in <b>test mode</b>, kindly pay attention to this space for information on when we are going live',
                        ],
                    ]),
                    'updated_at' => now()->timestamp,
                    'created_at' => now()->timestamp,
                ],
                [
                    'profile_key' => 'development',
                    'settings' => json_encode([
                        'app_name' => 'Topalat Nigeria',
                        'services_rate' => 80,
                        'services' => [
                            'airtime' => true,
                            'databundle' => true,
                            'cabletv' => true,
                            'power' => true,
                        ],
                        'announcement' => [
                            'show' => true,
                            'type' => 'is-danger',
                            'size' => 'is-medium',
                            'message' => 'Please note that this site is still in <b>test mode</b>, kindly pay attention to this space for information on when we are going live',
                        ],
                    ]),
                    'updated_at' => now()->timestamp,
                    'created_at' => now()->timestamp,
                ],
            ]
        );
    }
}
