<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:4',
            'full_name' => 'required|min:4',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_staff' => false,
            'is_superuser' => false,
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json([
                'success' => true,
                'message' => Response::$statusTexts[Response::HTTP_OK]
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json(['message' => 'User successfully signed out']);
    }
}
