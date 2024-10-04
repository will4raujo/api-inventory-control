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
        $request->validate([
            'email' => 'required|string|email', 'max:255',
            'password' => 'required|string', 'min:8'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logged out successfully'], 200);
    }
}