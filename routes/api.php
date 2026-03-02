<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MachineController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TemplateApiController;
use App\Http\Controllers\Api\PhotoSessionController;
use App\Http\Controllers\Api\StickerController;

// Route::get('/user', function (Request $request) {return $request->user();})->middleware('auth:sanctum');

// Midtrans payment routes - DISABLED (using manual payment)
Route::middleware('machine.token')->group(function () {
    Route::post('/payments', [PaymentController::class, 'create']);
    Route::get('/payments/{order_id}/check', [PaymentController::class, 'checkStatus']);
    Route::post('/photo-sessions/start', [SessionController::class, 'start']);
    Route::get('/photo-sessions/{id}', [SessionController::class, 'show']);
    Route::get('/templates', [TemplateApiController::class, 'index']);
    // Route::get('/templates/{id}', [TemplateApiController::class, 'show']);
    Route::post('/sessions/{session}/complete', [PhotoSessionController::class, 'completeSession']);
    Route::post('/sessions/{session}/set-print-quantity', [PhotoSessionController::class, 'setPrintQuantity']);
    Route::get('/machines/{id}/detail', [MachineController::class, 'detail']);
    Route::get('/machines/{id}/banners', [MachineController::class, 'banners']);
    Route::get('/stickers', [StickerController::class, 'index']);
});

Route::get('/templates/{id}', [TemplateApiController::class, 'show']);