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
                            'message' => 'This site is still in <b>test mode</b>. Found a bug? Great!  <a href="/contact-us">report</a> or <a href="mailto:contact@topalat.ng">mail us</a> 🙏🏾',
                        ],
                        'social_media_handles' => [
                            'twitter' => '@topalatng',
                            'instagram' => 'topalatng',
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
                            'message' => '<b>Test Mode</b>: Found a bug? Great!  <a href="/contact-us">report</a> or <a href="mailto:contact@topalat.ng">mail us</a> 🙏🏾',
                        ],
                        'social_media_handles' => [
                            'twitter' => '@topalatng',
                            'instagram' => 'topalatng',
                        ],
                    ]),
                    'updated_at' => now()->timestamp,
                    'created_at' => now()->timestamp,
                ],
            ]
        );
    }
}
