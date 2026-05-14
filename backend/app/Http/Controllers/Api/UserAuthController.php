<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Password must be at least 6 characters long',
                'errors' => $validator->errors()
            ], 422);
        }

        // duplicate email check
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email already registered'
            ], 409);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // FIX: correct guard
        $token = auth('user_api')->login($user);

        return response()->json([
            'message' => 'Account created successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // FIX: correct guard
        if (!$token = auth('user_api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => auth('user_api')->user()
        ]);
    }

    public function me()
    {
        return response()->json(auth('user_api')->user());
    }

    public function logout()
    {
        auth('user_api')->logout();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}