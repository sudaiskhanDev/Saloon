<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

use App\Models\AdminStaff;
use App\Models\Service;
use App\Models\Notification;

class AppointmentController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json(Appointment::all());
    }

    // CREATE
//     public function store(Request $request)
// {
//     $user = auth('user_api')->user();

//     if (!$user) {
//         return response()->json(['message' => 'Unauthenticated'], 401);
//     }

//     $request->validate([
//         'service_id' => 'required',
//         'date' => 'required',
//         'time' => 'required',
//     ]);

//     return Appointment::create([
//         'user_id' => $user->user_id,
//         'admin_staff_id' => null,
//         'service_id' => $request->service_id,
//         'date' => $request->date,
//         'time' => $request->time,
//         'status' => 'booked'
//     ]);
// }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'admin_staff_id' => 'required',
            'service_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'status' => 'nullable|in:booked,cancelled,completed'
        ]);

        $appointment = Appointment::create([
            'user_id' => $request->user_id,
            'admin_staff_id' => $request->admin_staff_id,
            'service_id' => $request->service_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => $request->status ?? 'booked'
        ]);

        return response()->json($appointment, 201);
    }

    // SHOW SINGLE
    public function show($id)
    {
        return response()->json(Appointment::findOrFail($id));
    }

    // UPDATE
    public function update(Request $request, $id)
{
    $appointment = Appointment::findOrFail($id);

    // Capture old values before update
    $oldStaffId = $appointment->admin_staff_id;
    $oldStatus = $appointment->status;

    // Perform the update
    $appointment->update($request->all());

    // Capture new values after update
    $newStaffId = $appointment->admin_staff_id;
    $newStatus = $appointment->status;

    // ============= NOTIFICATION LOGIC =============

    // 1️⃣ First-time staff assignment (null → staff): NO notification
    if ($oldStaffId === null && $newStaffId !== null) {
        // Silent – do nothing
    }

    // 2️⃣ Staff reassignment: If old status was "cancelled"
    elseif ($oldStaffId !== null && $newStaffId !== null && $oldStaffId != $newStaffId) {
        
        // 🔥 If old status was cancelled → automatically set to booked
        if ($oldStatus === 'cancelled') {
            
            // ✅ Force status to "booked"
            $appointment->status = 'booked';
            $appointment->save();
            
            // ✅ Send reassignment notification to client
            $newStaff = \App\Models\AdminStaff::find($newStaffId);
            $service = \App\Models\Service::find($appointment->service_id);

            if ($newStaff && $service) {
                \App\Models\Notification::create([
                    'user_id' => $appointment->user_id,
                    'message' => "Your appointment for {$service->name} on {$appointment->date} at {$appointment->time} has been reassigned to {$newStaff->name}.",
                    'status'  => 'unread',
                    'date'    => now()->toDateString(),
                ]);
            }
        }
        // For other staff changes (e.g., booked → booked with different staff),
        // no notification is sent (per your requirement)
    }

    return response()->json([
        'message' => 'Appointment updated successfully',
        'appointment' => $appointment->fresh() // 🔥 Refresh to get latest status
    ]);
}
    // public function update(Request $request, $id)
    // {
    //     $appointment = Appointment::findOrFail($id);

    //     $appointment->update($request->all());

    //     return response()->json($appointment);
    // }

    // DELETE
    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Appointment deleted'
        ]);
    }
}