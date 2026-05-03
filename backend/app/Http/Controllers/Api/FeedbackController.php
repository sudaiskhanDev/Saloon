<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json(Feedback::all());
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable',
            'date' => 'required'
        ]);

        $feedback = Feedback::create($request->all());

        return response()->json($feedback, 201);
    }

    // SHOW
    public function show($id)
    {
        return response()->json(Feedback::findOrFail($id));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        $feedback->update($request->all());

        return response()->json($feedback);
    }

    // DELETE
    public function destroy($id)
    {
        Feedback::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Feedback deleted'
        ]);
    }
}