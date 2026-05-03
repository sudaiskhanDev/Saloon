<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Feedback;
use Illuminate\Http\Request;

class UserAppointmentController extends Controller
{
    // 🔥 GET CURRENT USER APPOINTMENTS
    public function myAppointments()
    {
        $user = auth('user_api')->user();

        $appointments = Appointment::with(['service', 'adminStaff', 'feedback'])
            ->where('user_id', $user->user_id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $appointments
        ]);
    }

    // 🔥 SUBMIT FEEDBACK
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $user = auth('user_api')->user();

        // 🔥 CHECK APPOINTMENT BELONGS TO USER
        $appointment = Appointment::where('appointment_id', $request->appointment_id)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid appointment'
            ], 403);
        }

        // 🔥 SAVE FEEDBACK
        $feedback = Feedback::create([
            'appointment_id' => $request->appointment_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'date' => date('Y-m-d')
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Feedback submitted successfully',
            'data' => $feedback
        ]);
    }
}