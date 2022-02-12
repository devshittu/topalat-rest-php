<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfClient
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    //Replace handle function:
    public function handle($request, Closure $next)
    {
//        return response( array( "process_hmac_auth" => process_hmac_auth($request), "method" =>  $request->method(), "headers" => $request->header(), "data" => $request->all(), "path" => $request->path() ), Response::HTTP_FORBIDDEN);
//        remaining the process_hmac_client
        list($final_signature, $signed_string, $json_payload) = process_hmac_auth($request);
//        $isAuthorisedClient = (process_hmac_auth($request) === $request->header('X-CLIENT-VERIFY'));
        $isAuthorisedClient = $final_signature === $request->header('X-CLIENT-VERIFY');

        //This will be excecuted if the new authentication fails.
        if (!$isAuthorisedClient){
//            return response( array( "status" => "failed", "message" => "Client verification error.", "json_payload" => $json_payload, "signed_string" => $signed_string ), Response::HTTP_FORBIDDEN);
            return response( array( "status" => "failed", "message" => "Client verification error."), Response::HTTP_FORBIDDEN);
        }
        else return $next($request);

    }
}
