<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    private $guard = 'api';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    //all auth manually input with 'api'

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth($this->guard)->attempt($credentials)) {
            return response()->json(['error' => 'Incorrect email or password!'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth($this->guard)->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


/*    public function me()
    {
        return response()->json(auth()->user());
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    } 

*/


    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => auth($this->guard)->user()->id,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }
}