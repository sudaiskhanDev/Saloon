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

     // =========================
    // USER NOTIFICATIONS 🔥
    // =========================
    public function userNotifications()
    {
        $user = auth('user_api')->user();

        return response()->json(
            Notification::where('user_id', $user->user_id)
                ->latest()
                ->get()
        );
    }


    // =========================
    // USER DASHBOARD (APPOINTMENTS + FEEDBACK)
    // =========================
    public function dashboard()
    {
        $user = auth('user_api')->user();

        $appointments = Appointment::with(['service', 'feedback'])
            ->where('user_id', $user->user_id)
            ->latest()
            ->get();

        return response()->json($appointments);
    }

    // =========================
    // SUBMIT FEEDBACK
    // =========================
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable'
        ]);

        $user = auth('user_api')->user();

        // CHECK OWNERSHIP
        $appointment = Appointment::where('appointment_id', $request->appointment_id)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // PREVENT DUPLICATE
        if ($appointment->feedback) {
            return response()->json([
                'message' => 'Feedback already given'
            ], 400);
        }

        $feedback = Feedback::create([
            'appointment_id' => $appointment->appointment_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'date' => now()->toDateString()
        ]);

        return response()->json([
            'message' => 'Feedback submitted',
            'data' => $feedback
        ], 201);
    }












    // 🔥 GET STAFF APPOINTMENTS
    public function staffAppointments()
    {
        $staff = auth('admin_api')->user();

        $appointments = Appointment::with(['user', 'service'])
            ->where('admin_staff_id', $staff->admin_staff_id)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'appointments' => $appointments
        ]);
    }

    // 🔥 MARK AS COMPLETED
    public function markCompleted($id)
    {
        $staff = auth('admin_api')->user();

        $appointment = Appointment::where('appointment_id', $id)
            ->where('admin_staff_id', $staff->admin_staff_id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Appointment not found or not assigned to you'
            ], 404);
        }

        $appointment->status = 'completed';
        $appointment->save();

        return response()->json([
            'status' => true,
            'message' => 'Appointment marked as completed'
        ]);
    }

    // 🔥 OPTIONAL: CANCEL
    public function cancel($id)
    {
        $staff = auth('admin_api')->user();

        $appointment = Appointment::where('appointment_id', $id)
            ->where('admin_staff_id', $staff->admin_staff_id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Appointment not found'
            ], 404);
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        return response()->json([
            'status' => true,
            'message' => 'Appointment cancelled'
        ]);
    }


    public function userAppointmentsForFeedback()
{
    try {
        $user = auth('admin_api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $appointments = Appointment::with(['service', 'feedback'])
            ->where('admin_staff_id', $user->admin_staff_id)
            ->get();

        return response()->json([
            'message' => 'Appointments fetched successfully',
            'data' => $appointments
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error fetching appointments',
            'error' => $e->getMessage()
        ], 500);
    }
}
}