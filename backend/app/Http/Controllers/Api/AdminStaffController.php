<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminStaff;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    // CREATE STAFF
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // 🔥 CUSTOM DUPLICATE CHECK
        $exists = AdminStaff::where('email', $request->email)->first();

        if ($exists) {
            return response()->json([
                'message' => 'Staff already exists with this email'
            ], 409);
        }

        $staff = AdminStaff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
        ]);

        return response()->json([
            'message' => 'Staff created successfully',
            'data' => $staff
        ], 201);
    }

    // GET ALL
    public function index()
    {
        return response()->json(
            AdminStaff::where('role', 'staff')->get()
        );
    }

    // GET ONE
    public function show($id)
    {
        $staff = AdminStaff::where('role', 'staff')->find($id);

        if (!$staff) {
            return response()->json([
                'message' => 'Staff not found'
            ], 404);
        }

        return response()->json($staff);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $staff = AdminStaff::where('role', 'staff')->find($id);

        if (!$staff) {
            return response()->json([
                'message' => 'Staff not found'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|min:2|max:100',
            'email' => 'sometimes|email',
        ]);

        // 🔥 email uniqueness check (ignore self)
        if ($request->email && AdminStaff::where('email', $request->email)
            ->where('admin_staff_id', '!=', $id)
            ->exists()) {

            return response()->json([
                'message' => 'Email already taken by another staff'
            ], 409);
        }

        $staff->update([
            'name' => $request->name ?? $staff->name,
            'email' => $request->email ?? $staff->email,
        ]);

        return response()->json([
            'message' => 'Staff updated successfully',
            'data' => $staff
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $staff = AdminStaff::where('role', 'staff')->find($id);

        if (!$staff) {
            return response()->json([
                'message' => 'Staff not found'
            ], 404);
        }

        $staff->delete();

        return response()->json([
            'message' => 'Staff deleted successfully'
        ]);
    }
}