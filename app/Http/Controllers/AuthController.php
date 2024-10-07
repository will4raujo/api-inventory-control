<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{  
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "access_token" => $token,
                "token_type" => 'Bearer'
            ]);
        }

        return response()->json(['message' => 'Invalid login credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logged out successfully'], 200);
    }
}