<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json(Schedule::all());
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'admin_staff_id' => 'required',
            'work_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        $schedule = Schedule::create($request->all());

        return response()->json($schedule, 201);
    }

    // SHOW
    public function show($id)
    {
        return response()->json(Schedule::findOrFail($id));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->update($request->all());

        return response()->json($schedule);
    }

    // DELETE
    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Schedule deleted'
        ]);
    }
}