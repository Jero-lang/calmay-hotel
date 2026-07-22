<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\VerificationController;

// ============ AUTH ROUTES ============
require __DIR__.'/auth.php';

// ============ VERIFICATION ROUTES ============
Route::get('/verify/{email}', [VerificationController::class, 'showVerifyForm'])->name('verify.form');
Route::post('/verify/code', [VerificationController::class, 'verify'])->name('verify.code');
Route::post('/verify/resend', [VerificationController::class, 'resendCode'])->name('verify.resend');

// ============ HOME ============
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============ BOOKING ROUTES ============
Route::get('/booking/start', [BookingController::class, 'start'])->name('booking.start');
Route::post('/booking/details', [BookingController::class, 'processDetails'])->name('booking.details');
Route::get('/booking/confirmation', [BookingController::class, 'showConfirmation'])->name('booking.confirmation');
Route::post('/booking/confirmation', [BookingController::class, 'processConfirmation'])->name('booking.confirmation.post');
Route::get('/booking/summary', [BookingController::class, 'summary'])->name('booking.summary');
Route::get('/booking/reset', [BookingController::class, 'reset'])->name('booking.reset');
Route::get('/booking/receipt/{bookingId}', [BookingController::class, 'downloadReceipt'])->name('booking.receipt');

// ── Booked dates API — admin sees all, users see only confirmed/checked_in ──
Route::get('/api/booked-dates', [AdminController::class, 'bookedDates'])->name('api.booked-dates');
Route::get('/api/available-dates', [BookingController::class, 'availableDates'])->name('api.available-dates');

// ── Send booking summary email ──────────────────────────────────────────────
Route::middleware(['auth'])->post('/booking/send-summary', [BookingController::class, 'sendSummaryEmail'])->name('booking.send-summary');

// ============ FAQ ============
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// ============ USER BOOKINGS ============
Route::middleware(['auth'])->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my.bookings');
});

// ============ ADMIN ============
// Public admin login (no middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',     [AdminController::class, 'users'])->name('users');
        Route::post('/bookings/{bookingId}/status', [AdminController::class, 'updateBookingStatus'])
             ->name('bookings.status');
    });
});