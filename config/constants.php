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
    'app' => [
        'client_secret' => env('APP_WEB_CLIENT_SECRET', 'APP_WEB_CLIENT_SECRET'),
        'init_users' => env('APP_USERS_ARRAY', '[]'),
    ],
    'clients' => [
        'web_password' => env('APP_WEB_CLIENT_PASSWORD', 'APP_WEB_CLIENT_PASSWORD'),
        'web_username' => env('APP_WEB_CLIENT_USERNAME', 'APP_WEB_CLIENT_USERNAME'),
        'web_email' => env('APP_WEB_CLIENT_EMAIL', 'APP_WEB_CLIENT_EMAIL'),
        'web_name' => env('APP_WEB_CLIENT_NAME', 'APP_WEB_CLIENT_NAME'),
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
