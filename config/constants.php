<?php
return [
    'pagination' => [
        'per_page' => 1000,
    ],
    'baxi' => [
        'api_url' => env('BAXI_ENDPOINT', 'https://payments.baxipay.com.ng/api/baxipay'),
        'api_key' => env('BAXI_API_KEY', '5adea9-044a85-708016-7ae662-646d59'),
        'user_secret' => env('BAXI_USER_SECRET', 'YOUR_USER_SECRET'),
        'username' => env('BAXI_USERNAME', 'baxi_test'),
        'test_signature' => env('BAXI_TEST_SIGNATURE', 'ONXpnbbudYgopBvRwPFCn7eZTPY='),
        'agent_id' => env('BAXI_AGENT_ID', '0'),
    ],
    'service_status' => [
        'PENDING' => '0',
        'COMPLETED' => '1',
        'FAILED' => '-1',
    ],
    'service_categories' => [
        'airtime' => 'AT',
        'cabletv' => 'CT',
        'databundle' => 'DT',
        'electricity' => 'EL',
    ]
];
