<?php

require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/service.php';
require __DIR__.'/appointment.php';
require __DIR__.'/schedule.php';
require __DIR__.'/feedback.php';
require __DIR__.'/notification.php';





use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\AdminStaff;
use App\Models\Service;

// 👤 USERS
Route::get('/users', function () {
    return User::all();
});

// 💇 STAFF ONLY
Route::get('/staff', function () {
    return AdminStaff::where('role', 'staff')->get();
});

// 💅 SERVICES
Route::get('/services', function () {
    return Service::all();
});




use App\Http\Controllers\Api\AppointmentActionController;

Route::prefix('user')->middleware('auth:user_api')->group(function () {

    Route::post('/appointments', [AppointmentActionController::class, 'store']);

     Route::get('/dashboard', [AppointmentActionController::class, 'dashboard']);

    Route::post('/feedback', [AppointmentActionController::class, 'submitFeedback']);

    Route::get('/appointments', [AppointmentActionController::class, 'userAppointments']);

    // 🔥 NEW
    Route::get('/notifications', [AppointmentActionController::class, 'userNotifications']);
});



 
Route::middleware('auth:admin_api')->group(function () {

    // 🔥 staff appointments list
    Route::get('/staff/appointments', [
        AppointmentActionController::class,
        'staffAppointments'
    ]);

    // 🔥 mark completed
    Route::post('/staff/appointment/{id}/complete', [
        AppointmentActionController::class,
        'markCompleted'
    ]);

    // 🔥 cancel appointment
    Route::post('/staff/appointment/{id}/cancel', [
        AppointmentActionController::class,
        'cancel'
    ]);

});




use App\Http\Controllers\Api\StaffStatusController;

Route::get('/staff/status', [
    StaffStatusController::class,
    'getStaffWithStatus'
]);







use App\Http\Controllers\Api\UserAppointmentController;

Route::middleware('auth:user_api')->group(function () {

    // 🔥 USER APPOINTMENTS
    Route::get('/my-appointments', [
        UserAppointmentController::class,
        'myAppointments'
    ]);

    // 🔥 FEEDBACK SUBMIT
    Route::post('/feedback', [
        UserAppointmentController::class,
        'submitFeedback'
    ]);
});





use App\Http\Controllers\Api\AdminStaffController;

Route::post('/staff', [AdminStaffController::class, 'store']);
Route::get('/staff', [AdminStaffController::class, 'index']);
Route::get('/staff/{id}', [AdminStaffController::class, 'show']);
Route::put('/staff/{id}', [AdminStaffController::class, 'update']);
Route::delete('/staff/{id}', [AdminStaffController::class, 'destroy']);

