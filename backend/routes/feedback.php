<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FeedbackController;

Route::prefix('feedback')->group(function () {
    Route::get('/', [FeedbackController::class, 'index']);
    Route::post('/', [FeedbackController::class, 'store']);
    Route::get('/{id}', [FeedbackController::class, 'show']);
    Route::put('/{id}', [FeedbackController::class, 'update']);
    Route::delete('/{id}', [FeedbackController::class, 'destroy']);
});