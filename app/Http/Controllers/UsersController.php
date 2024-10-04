<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:15',
            'role_id' => 'required|integer'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => $request->role_id
        ]);


        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function findById(Request $request, int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid user id'], 400);
        }

        $user = User::find($id);

        if (!$user){
            return response()->json(['message' => 'User not found'], 400);
        }

        return response()->json($user);
    }

    public function destroy(int $id)
    {
        if (!is_numeric($id) || intval($id) <= 0) {
            return response()->json(['message' => 'Invalid user id'], 400);
        }

        $user = User::find($id);

        if(!$user) {
            return response()->json(['message' => 'User not found'], 400);
        }

        $user->delete();
    }
}
