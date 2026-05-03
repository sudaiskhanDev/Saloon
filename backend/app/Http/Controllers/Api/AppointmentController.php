<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json(Appointment::all());
    }

    // CREATE
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

        $appointment->update($request->all());

        return response()->json($appointment);
    }

    // DELETE
    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Appointment deleted'
        ]);
    }
}