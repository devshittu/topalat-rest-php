<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\RefreshTokenRepository;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public $successStatus = 200;
    /**
     * login
 * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request){
        $attributes = ['email' => $request->email, 'password' => $request->password];
        if(Auth::guard('client')->attempt($attributes)){

//            $refreshToken = Passport::refreshToken()->create($attributes);
//            dd($refreshToken);
            $client = Auth::guard('client')->user();
            $token              =  $client->createToken('tangCLIToken')->accessToken;
            $result['client_id']  = $client->id;
            $result['success']  =  true;
            $result['message']  =  "Success! you are logged in successfully";
            $result['token']    =  $token;
//            $refreshToken = '' +  RefreshToken::create();

            return response()->json(['data' => $result], $this->successStatus);

        }
        else{
            return response()->json(['error'=>'Client email or password incorrect'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
