<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Notification;

class AppointmentActionController extends Controller
{
    // =========================
    // STORE APPOINTMENT + NOTIFICATION
    // =========================
    public function store(Request $request)
    {
        // 🔐 GET LOGGED IN USER
        $user = auth('user_api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // ✅ VALIDATION
        $request->validate([
            'service_id' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);

        // =========================
        // 1️⃣ CREATE APPOINTMENT
        // =========================
        $appointment = Appointment::create([
            'user_id' => $user->user_id,
            'admin_staff_id' => null,
            'service_id' => $request->service_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'booked'
        ]);

        // =========================
        // 2️⃣ CREATE NOTIFICATION (DIRECT HERE)
        // =========================
        Notification::create([
            'user_id' => $user->user_id,
            'message' => "Your appointment has been booked successfully for {$request->date} at {$request->time}",
            'status' => 'unread',
            'date' => now()->toDateString()
        ]);

        // =========================
        // 3️⃣ RESPONSE
        // =========================
        return response()->json([
            'message' => 'Appointment booked successfully',
            'appointment' => $appointment
        ], 201);
    }
}