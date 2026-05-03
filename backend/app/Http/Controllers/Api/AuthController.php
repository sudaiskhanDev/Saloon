<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminStaff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,staff',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // check duplicate email
        if (AdminStaff::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email already registered'
            ], 409);
        }

        $user = AdminStaff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // FIX: correct guard
        $token = auth('admin_api')->login($user);

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
        if (!$token = auth('admin_api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => auth('admin_api')->user()
        ]);
    }

    public function me()
    {
        return response()->json(auth('admin_api')->user());
    }

    public function logout()
    {
        auth('admin_api')->logout();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}