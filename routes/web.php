<?php

use App\Http\Controllers\Public\GaleriController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminMachineController;
use App\Http\Controllers\Admin\TemplateUploadController;


Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::put('/user/reset/{id}', [UserController::class, 'reset'])->name('user.reset');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');


});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/templates/create', [TemplateUploadController::class, 'create'])
        ->name('admin.templates.create');

    Route::post('/templates/upload', [TemplateUploadController::class, 'upload'])
        ->name('admin.templates.upload');

    Route::get('/templates', [
        TemplateUploadController::class,
        'index'
    ])->name('admin.templates.index');

    Route::get('/templates/{template}/edit', [
        TemplateUploadController::class,
        'edit'
    ])->name('admin.templates.edit');

    Route::put('/templates/{template}', [
        TemplateUploadController::class,
        'update'
    ])->name('admin.templates.update');

    Route::delete('/templates/{template}', [TemplateUploadController::class, 'destroy'])->name('admin.templates.destroy');
    Route::patch('/templates/{template}/toggle', [TemplateUploadController::class, 'toggle'])->name('admin.templates.toggle');

});


Route::middleware(['auth'])
    ->group(function () {

        // CRUD Machine
        Route::get('/machines', [AdminMachineController::class, 'index'])
            ->name('machines.index');

        Route::get('/machines/create', [AdminMachineController::class, 'create'])
            ->name('machines.create');

        Route::post('/machines', [AdminMachineController::class, 'store'])
            ->name('machines.store');

        Route::get('/machines/{machine}/edit', [AdminMachineController::class, 'edit'])
            ->name('machines.edit');

        Route::put('/machines/{machine}', [AdminMachineController::class, 'update'])
            ->name('machines.update');

        Route::delete('/machines/{machine}', [AdminMachineController::class, 'destroy'])
            ->name('machines.destroy');
    });


Route::get(
    'photo-sessions/{session_id}/download/',
    [GaleriController::class, 'show']
)->name('gallery.show');

Route::get(
    '/gallery/frame/{photo_id}/download',
    [GaleriController::class, 'downloadFrame']
)->name('gallery.frame.download');

Route::get(
    '/gallery/{session_id}/final/download',
    [GaleriController::class, 'downloadFinal']
)->name('gallery.final.download');
