<?php


 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // GET ALL (FIXED)
    public function index()
    {
        try {
            return response()->json(
                Notification::with('user')->get(),
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // CREATE (FIXED)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'message' => 'required|string',
            'status' => 'nullable|in:read,unread',
            'date' => 'nullable|date'
        ]);

        try {
            $notification = Notification::create([
                'user_id' => $request->user_id,
                'message' => $request->message,
                'status' => $request->status ?? 'unread',
                'date' => $request->date ?? now()->toDateString()
            ]);

            return response()->json([
                'message' => 'Notification created successfully',
                'data' => $notification
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // SHOW (FIXED)
    public function show($id)
    {
        try {
            $notification = Notification::with('user')->findOrFail($id);

            return response()->json($notification, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Notification not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    // UPDATE (FIXED - NO MASS UPDATE BUG)
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'sometimes|required|exists:users,user_id',
            'message' => 'sometimes|required|string',
            'status' => 'nullable|in:read,unread',
            'date' => 'nullable|date'
        ]);

        try {
            $notification = Notification::findOrFail($id);

            $notification->update([
                'user_id' => $request->user_id ?? $notification->user_id,
                'message' => $request->message ?? $notification->message,
                'status' => $request->status ?? $notification->status,
                'date' => $request->date ?? $notification->date,
            ]);

            return response()->json([
                'message' => 'Notification updated successfully',
                'data' => $notification
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE (FIXED)
    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();

            return response()->json([
                'message' => 'Notification deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Notification;

// class NotificationController extends Controller
// {
//     // GET ALL
//    public function index()
// {
//     return response()->json(Notification::with('user')->get());
// }

//     // CREATE
//     public function store(Request $request)
//     {
//         $request->validate([
//             'user_id' => 'required',
//             'message' => 'required',
//             'status' => 'nullable|in:read,unread',
//             'date' => 'required'
//         ]);

//         $notification = Notification::create([
//             'user_id' => $request->user_id,
//             'message' => $request->message,
//             'status' => $request->status ?? 'unread',
//             'date' => $request->date
//         ]);

//         return response()->json($notification, 201);
//     }

//     // SHOW
//     public function show($id)
//     {
//         return response()->json(Notification::findOrFail($id));
//     }

//     // UPDATE
//     public function update(Request $request, $id)
//     {
//         $notification = Notification::findOrFail($id);

//         $notification->update($request->all());

//         return response()->json($notification);
//     }

//     // DELETE
//     public function destroy($id)
//     {
//         Notification::findOrFail($id)->delete();

//         return response()->json([
//             'message' => 'Notification deleted'
//         ]);
//     }
// }