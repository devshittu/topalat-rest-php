<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;



if (!function_exists('make_baxi_url')) {
    function make_baxi_url($url)
    {
        $contains = Str::contains($url, ['/api/baxipay']);
        if ($contains) {
            $url = ltrim( $url, "/api/baxipay");
//            dd($url, Config::get('constants.baxi.api_url') . $url);
            return Config::get('constants.baxi.api_url') .'/'. $url;
        }
        return Config::get('constants.baxi.api_url') . $url;
    }
}


if (!function_exists('make_hmac_auth')) {
    function make_hmac_auth($url){
        return true;
    }
}

//$request_type = "GET";
//$endpoint = "/api/baxipay/superagent/account/balance";
//$request_date = "Thu, 19 Dec 2019 17:40:26 GMT";
//$request_date = date(DATE_RFC1123);
//$user_secret = "YOUR_USER_SECRET";
//$json_payload = '{ "name":"tayo" }';
//$encoded_payload_hash = "";

if (!function_exists('calculate_digest')) {

    function calculate_digest($request_type, $endpoint, $json_payload = "")
    {
        // 1. CONVERT DATE TO UNIX TIMESTAMP
        $request_date = date(DATE_RFC1123);
        $user_secret = Config::get('constants.baxi.user_secret');
        $timestamp = strtotime($request_date);

        if (!empty($json_payload)) {
            // 2. DO A SHA256 OF YOUR JSON PAYLOAD (IF AVAILABLE)
            $payload_hash = hash('sha256', $json_payload, $raw_output = TRUE);

            // 3. ENCODE THE PAYLOAD HASH WITH BASE-64
            $encoded_payload_hash = base64_encode($payload_hash);
        } else {
            $encoded_payload_hash = ""; // NO PAYLOAD
        }

        // 4. CREATE A SECURITY STRING FOR THIS REQUEST
        $signed_string = $request_type . $endpoint . $timestamp . $encoded_payload_hash;

        // 5. DO A UTF-8 ENCODE OF THE SECURITY STRING
        $encoded_signed_string = utf8_encode($signed_string);

        // 6. SIGN USING HMAC-SHA1: Key = USER_SECRET, Message = ENCODED SIGNED STRING
        $hash_signature = hash_hmac("sha1", $encoded_signed_string, $user_secret, $raw_output = TRUE);

        // 7. CONVERT HASH SIGNATURE TO BASE 64
        $final_signature = base64_encode($hash_signature);

        return $final_signature;
    }
}

if (!function_exists('process_hmac_auth')) {

    function process_hmac_auth($request)
    {

        $request_type = $request->method();
//        var_dump($request_type);
        $endpoint = str_replace("api/",'',$request->path());

        $json_payload = collect($request->all())->toJson();
        // 1. CONVERT DATE TO UNIX TIMESTAMP
//        $request_auth = $request->header('Authorization');
//        SPLIT IN TO TWO USINg :  and get the second item after username
        $request_date = $request->header('X-DATE-TIME');
        $user_secret = Config::get('constants.app.client_secret'); //'secret'; // Take out to .env variables
        $timestamp = strtotime($request_date);

        if (!empty($json_payload)) {
            // 2. DO A SHA256 OF YOUR JSON PAYLOAD (IF AVAILABLE)
            $payload_hash = hash('sha256', $json_payload, $raw_output = FALSE);

            // 3. ENCODE THE PAYLOAD HASH WITH BASE-64
            $encoded_payload_hash = base64_encode($payload_hash);
        } else {
            $encoded_payload_hash = ""; // NO PAYLOAD
        }
//        dd($encoded_payload_hash);
        $signed_string = $request_type . $endpoint . $timestamp . $encoded_payload_hash;

        // 5. DO A UTF-8 ENCODE OF THE SECURITY STRING
        $encoded_signed_string = utf8_encode($signed_string);

        // 6. SIGN USING HMAC-SHA1: Key = USER_SECRET, Message = ENCODED SIGNED STRING
        $hash_signature = hash_hmac("sha1", $encoded_signed_string, $user_secret, $raw_output = FALSE);

        // 7. CONVERT HASH SIGNATURE TO BASE 64
        $final_signature = base64_encode($hash_signature);

        return $final_signature;
    }
}


