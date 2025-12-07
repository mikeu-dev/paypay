<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Login
     * 
     * Authenticate a user and return an access token.
     * 
     * @group Authentication
     * @unauthenticated
     * @response {
     *  "access_token": "1|...",
     *  "token_type": "Bearer",
     *  "user": {...}
     * }
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated)) {
            $user = Auth::user();
            /** @var \App\Models\User $user */
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->load('employee'),
            ]);
        }

        return response()->json(['message' => 'Invalid login details'], 401);
    }

    /**
     * Logout
     * 
     * Revoke the current access token.
     * 
     * @group Authentication
     * @authenticated
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Get User Profile
     * 
     * Get the currently authenticated user.
     * 
     * @group Authentication
     * @authenticated
     */
    public function me(Request $request)
    {
        return response()->json($request->user()->load('employee'));
    }
}
