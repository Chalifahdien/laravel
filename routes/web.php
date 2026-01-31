<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Public\GaleriController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaperSizeController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AdminMachineController;
use App\Http\Controllers\Admin\TemplateUploadController;
use App\Http\Controllers\Admin\ReportAnalyticController;
use App\Http\Controllers\Admin\BannerPromoController;
use App\Http\Controllers\Admin\InvoiceController;


Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware(['auth'])->group(function () {
    // DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::put('/user/reset/{id}', [UserController::class, 'reset'])->name('user.reset');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // REPORT ANALYTICS
    Route::get('/report-analytics', [ReportAnalyticController::class, 'index'])->name('admin.report-analytics.index');

    // TRANSACTIONS
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{photoSession}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{photoSession}/invoice', [InvoiceController::class, 'show'])->name('transactions.invoice');

    // INVOICES (list + download)
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{photoSession}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    // CRUD TEMPLATE
    Route::get('/templates/create', [TemplateUploadController::class, 'create'])->name('admin.templates.create');
    Route::post('/templates/upload', [TemplateUploadController::class, 'upload'])->name('admin.templates.upload');
    Route::get('/templates', [TemplateUploadController::class, 'index'])->name('admin.templates.index');
    Route::get('/templates/{template}/edit', [TemplateUploadController::class, 'edit'])->name('admin.templates.edit');
    Route::put('/templates/{template}', [TemplateUploadController::class, 'update'])->name('admin.templates.update');
    Route::delete('/templates/{template}', [TemplateUploadController::class, 'destroy'])->name('admin.templates.destroy');
    Route::patch('/templates/{template}/toggle', [TemplateUploadController::class, 'toggle'])->name('admin.templates.toggle');

    // CRUD Machine
    Route::get('/machines', [AdminMachineController::class, 'index'])->name('machines.index');
    Route::get('/machines/create', [AdminMachineController::class, 'create'])->name('machines.create');
    Route::post('/machines', [AdminMachineController::class, 'store'])->name('machines.store');
    Route::get('/machines/{machine}/edit', [AdminMachineController::class, 'edit'])->name('machines.edit');
    Route::put('/machines/{machine}', [AdminMachineController::class, 'update'])->name('machines.update');
    Route::delete('/machines/{machine}', [AdminMachineController::class, 'destroy'])->name('machines.destroy');

    // CRUD Paper Siza
    Route::get('/paper-sizes', [PaperSizeController::class, 'index'])->name('admin.paper-sizes.index');
    Route::post('/paper-sizes', [PaperSizeController::class, 'store'])->name('admin.paper-sizes.store');
    Route::put('/paper-sizes/{paperSize}', [PaperSizeController::class, 'update'])->name('admin.paper-sizes.update');
    Route::delete('/paper-sizes/{paperSize}', [PaperSizeController::class, 'destroy'])->name('admin.paper-sizes.destroy');

    // CRUD Banner Promo
    Route::get('/banner-promo', [BannerPromoController::class, 'index'])->name('admin.banner-promo.index');
    Route::get('/banner-promo/create', [BannerPromoController::class, 'create'])->name('admin.banner-promo.create');
    Route::post('/banner-promo', [BannerPromoController::class, 'store'])->name('admin.banner-promo.store');
    Route::get('/banner-promo/{bannerPromo}/edit', [BannerPromoController::class, 'edit'])->name('admin.banner-promo.edit');
    Route::put('/banner-promo/{bannerPromo}', [BannerPromoController::class, 'update'])->name('admin.banner-promo.update');
    Route::delete('/banner-promo/{bannerPromo}', [BannerPromoController::class, 'destroy'])->name('admin.banner-promo.destroy');
    Route::patch('/banner-promo/{bannerPromo}/toggle', [BannerPromoController::class, 'toggle'])->name('admin.banner-promo.toggle');

    // Gallery
    Route::get('/gallery', [GalleryController::class, 'index'])->name('admin.gallery.index');
    Route::get('/gallery/session/{sessionId}', [GalleryController::class, 'bySession'])->name('admin.gallery.session');
    Route::put('/gallery/{finalImage}/toggle-printed', [GalleryController::class, 'togglePrinted'])->name('admin.gallery.toggle-printed');
    Route::delete('/gallery/{finalImage}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');
});

// PUBLIC ROUETE
Route::get('photo-sessions/{session_id}/download/', [GaleriController::class, 'show'])->name('gallery.show');
Route::get('/gallery/frame/{photo_id}/download', [GaleriController::class, 'downloadFrame'])->name('gallery.frame.download');
Route::get('/gallery/{session_id}/final/download', [GaleriController::class, 'downloadFinal'])->name('gallery.final.download');
