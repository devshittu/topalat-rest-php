<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('transaction_logs')->insert([
            'email' => Str::random(10) . '@gmail.com',
            'reference' => Str::random(10),
            'description' => Str::random(100),
            'service_category_raw' => Str::random(10),
            'service_provider_raw' => Str::random(10),
            'payment_status' => 2,
            'service_render_status' => 1,
            'service_request_payload_data' => '{ "name": "John Doe"}',
        ]);
    }
}
