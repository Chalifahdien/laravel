<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TemplateApiController;
use App\Http\Controllers\Api\PhotoSessionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payments', [PaymentController::class, 'create']);
Route::get('/payments/{order_id}/check', [PaymentController::class, 'checkStatus']);
Route::post('/photo-sessions/start', [PhotoSessionController::class, 'start']);
Route::get('/photo-sessions/{id}', [PhotoSessionController::class, 'show']);


Route::prefix('templates')->group(function () {
    Route::get('/', [TemplateApiController::class, 'index']);        // list template aktif
    Route::get('/{id}', [TemplateApiController::class, 'show']);     // detail template + frames
});

