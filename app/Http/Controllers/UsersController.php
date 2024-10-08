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

    public function profile(Request $request)
    {
        $user = auth()->user();

        return response()->json($user);
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->role_id !== 1) {
            return response()->json(['error' => 'You do not have permission to access this resource'], 403);
        }

        $users = User::all();

        return response()->json($users);
    }

    public function findById(Request $request, int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid user id'], 400);
        }

        try {
            $user = User::find($id);
    
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
    
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching user', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid user id'], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 400);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'sometimes|required|string|max:15',
            'role_id' => 'sometimes|required|integer'
        ]);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'phone' => $request->phone ?? $user->phone,
            'role_id' => $request->role_id ?? $user->role_id
        ]);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy(int $id)
    {
        if (!is_numeric($id) || intval($id) <= 0) {
            return response()->json(['message' => 'Invalid user id'], 400);
        }

        $myUser = auth()->user();
        $user = User::find($id);

        if(!$user) {
            return response()->json(['message' => 'User not found'], 400);
        }

        if ($myUser->id === $user->id) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        }
    
        if ($myUser->role_id === 1 && $user->role_id === 1) {
            return response()->json(['message' => 'Unauthorized to delete another administrator'], 403);
        }

        $user->delete();
    }
}
