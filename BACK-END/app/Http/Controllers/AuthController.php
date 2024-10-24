<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => '🎉 Registration successful! Welcome ' . $user->name . '!',
            'user' => $user,
        ], 201);
    }

    // Login a user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Log the user's roles for debugging
        Log::info('User Roles:', $user->getRoleNames()->toArray());

        // Get role names
        $roles = $user->getRoleNames();
        $member_id = $user->member ? ($user->member ? $user->member->id : null) : null;

        return response()->json([
            'token' => $token,
            'roles' => $roles,
            'user' => [
                'member_id' => $member_id,
                'name' => $user->name,
            ],
            'message' => '🔐 Login successful! Welcome back, ' . $user->name . '!',
        ], 200);
    }

    // Logout the user (revoke token)
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => '👋 Logout successful! Come back soon!',
        ], 200);
    }
}
