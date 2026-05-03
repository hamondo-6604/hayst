<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Landing\HomeController;
use App\Http\Controllers\Landing\TicketBookingController;
use App\Http\Controllers\Landing\BookingRoutesController;
use App\Http\Controllers\Landing\ManageBookingController;
use App\Http\Controllers\Landing\AccountController;

/*
|--------------------------------------------------------------------------
| PUBLIC — Guest + Auth
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('landing.home');

// Search Trips
Route::get('/ticket-booking',  [TicketBookingController::class, 'index'])->name('landing.ticket_booking');
Route::post('/ticket-booking', [TicketBookingController::class, 'search'])->name('landing.ticket_booking.search');

// Routes & Terminals
Route::get('/routes', [BookingRoutesController::class, 'index'])->name('landing.booking_routes');

// Promos (public)
Route::get('/promos', [HomeController::class, 'promos'])->name('landing.promos');

// Track Trip
Route::get('/track-trip', [HomeController::class, 'trackTrip'])->name('landing.track_trip');

/*
|--------------------------------------------------------------------------
| AUTH-GATED — Requires login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // My Bookings
    Route::get('/my-bookings', [ManageBookingController::class, 'index'])
        ->name('manage.bookings');

    Route::post('/my-bookings/{booking}/cancel', [ManageBookingController::class, 'cancel'])
        ->name('manage.bookings.cancel');

    Route::post('/my-bookings/notifications/read', [ManageBookingController::class, 'markNotificationsRead'])
        ->name('manage.bookings.notifications.read');

    // Account & Support
    Route::get('/account', [AccountController::class, 'index'])->name('landing.account');

    // Profile update (from AccountController)
    Route::post('/account/profile',  [AccountController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('settings.updatePassword');

    // Seat Selection
    Route::get('/select-seats/{trip_id}', [TicketBookingController::class, 'selectSeats'])->name('user.select.seats');
    Route::post('/select-seats/{trip_id}', [TicketBookingController::class, 'bookSeats'])->name('user.book.seats');

    // Passenger Details
    Route::get('/booking/{booking_id}/details', [TicketBookingController::class, 'passengerDetails'])->name('user.booking.details');
    Route::post('/booking/{booking_id}/details', [TicketBookingController::class, 'storePassengerDetails'])->name('user.booking.store_details');

    // Checkout & Payment
    Route::get('/booking/{booking_id}/checkout', [TicketBookingController::class, 'checkout'])->name('user.booking.checkout');
    Route::post('/booking/{booking_id}/pay', [TicketBookingController::class, 'processPayment'])->name('user.booking.pay');
    Route::get('/booking/{booking_id}/success', [TicketBookingController::class, 'bookingSuccess'])->name('user.booking.success');
});

/*
|--------------------------------------------------------------------------
| AUTH AJAX — Login / Register / Logout / Forgot
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return redirect()->route('landing.home', ['open_login' => 1]);
})->name('login');
Route::post('/login',           [AuthController::class, 'login_post'])->name('login_post');
Route::post('/register',        [AuthController::class, 'register_post'])->name('register_post');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');