<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    // REGISTER LOGIC
    public function register(Request $request) {
        // DEBUG: log incoming payload to help diagnose missing fields from frontend
        \Log::debug('Register payload', $request->all());
        // validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // must come with password confirmation
            'phone' => 'nullable|string|max:15',
        ]);

        // create new user now
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,

        ]);

        // create api token
        $token = $user->createToken('kilimo-cha-kisasa')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'You are registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 201);
    }

    public function login(Request $request) {
        \Log::debug('Login payload', $request->all());

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect email or password.'],
            ]);
        }

        if (isset($user->is_active) && $user->is_active === false) {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Contact the administrator.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('kilimo-cha-kisasa')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'login successful.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'role' => strtolower($user->role ?? 'user'),
            ]
        ]);
    }

    // LOGOUT LOGIC
    public function logout(Request $request) {
        // delete the now token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'You have logout to your account',
        ]);
    }

    // the user profile
    public function me(Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }
}

