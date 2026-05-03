<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminStaff;
use App\Models\Appointment;
use Illuminate\Http\Request;

class StaffStatusController extends Controller
{
    public function getStaffWithStatus(Request $request)
    {
        // 🔥 VALIDATION (optional but correct)
        $request->validate([
            'date' => 'nullable|date'
        ]);

        $date = $request->date ?? date('Y-m-d');

        // 🔥 ONLY STAFF (NO ADMINS)
        $staffs = AdminStaff::where('role', 'staff')->get();

        $result = [];

        foreach ($staffs as $staff) {

            // 🔥 CHECK APPOINTMENT ONLY FOR THAT DATE
            $hasAppointment = Appointment::where('admin_staff_id', $staff->admin_staff_id)
                ->whereDate('date', $date)
                ->where('status', 'booked')
                ->exists();

            $result[] = [
                'admin_staff_id' => $staff->admin_staff_id,
                'name' => $staff->name,
                'email' => $staff->email,
                'role' => $staff->role,

                // 🔥 STATUS LOGIC
                'status' => $hasAppointment ? 'booked' : 'available'
            ];
        }

        return response()->json([
            'status' => true,
            'date' => $date,
            'data' => $result
        ]);
    }
}