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