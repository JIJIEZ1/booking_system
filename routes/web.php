<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Staff;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\CustomerFeedbackController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\UnifiedLoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminFacilityController;
use App\Http\Controllers\AdminFeedbackController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\StaffBookingController;
use App\Http\Controllers\StaffScheduleController;
use App\Http\Controllers\AdminReportsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffReportsController;

// ----------------------------
// Root Redirect
// ----------------------------
Route::get('/', fn() => redirect()->route('customer.booking'));

// ----------------------------
// Password Reset
// ----------------------------
Route::get('/customer/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/customer/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('customer.password.email');
Route::get('/customer/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/customer/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


// ----------------------------
// Unified Login
// ----------------------------
Route::get('/login', [UnifiedLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UnifiedLoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [UnifiedLoginController::class, 'logout'])->name('logout');

// ----------------------------
// Public Booking Page
// ----------------------------
Route::get('/customer/booking', [BookingController::class, 'index'])->name('customer.booking');

// ----------------------------
// Customer Registration
// ----------------------------
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ----------------------------
// Admin Routes (all under one prefix, no duplicates)
// ----------------------------
Route::prefix('admin')->middleware('auth:admin')->name('admin.')->group(function () {
    
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{role}/{email}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{role}/{email}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{role}/{email}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Bookings
    // Get available booking slots for a facility & date


    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [AdminBookingController::class, 'store'])->name('bookings.store');      // ADD
    Route::put('/bookings/{id}', [AdminBookingController::class, 'update'])->name('bookings.update'); // EDIT
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy'); // DELETE

    // Payments
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{payment_id}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::post('payments/{payment_id}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

    // Schedule
    Route::prefix('schedule')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('schedule.index');
        Route::post('/store', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::put('/update/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
        Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('schedule.delete');
    });

    // Facilities
    Route::get('/facilities', [AdminFacilityController::class, 'index'])->name('facilities.index');
    Route::post('/facilities/store', [AdminFacilityController::class, 'store'])->name('facilities.store');
    Route::get('/facilities/{id}/edit', [AdminFacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('/facilities/update/{id}', [AdminFacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/facilities/delete/{id}', [AdminFacilityController::class, 'destroy'])->name('facilities.delete');

    // Facility Pricing Schedules
    Route::get('/facilities/{facilityId}/pricing', [App\Http\Controllers\AdminFacilityPricingController::class, 'index'])->name('facility.pricing');
    Route::post('/facilities/{facilityId}/pricing', [App\Http\Controllers\AdminFacilityPricingController::class, 'store'])->name('facility.pricing.store');
    Route::put('/facilities/{facilityId}/pricing/{id}', [App\Http\Controllers\AdminFacilityPricingController::class, 'update'])->name('facility.pricing.update');
    Route::delete('/facilities/{facilityId}/pricing/{id}', [App\Http\Controllers\AdminFacilityPricingController::class, 'destroy'])->name('facility.pricing.delete');

    // Feedback
    Route::get('feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('feedback/{feedback_id}/show', [AdminFeedbackController::class, 'show'])->name('feedback.show');
    Route::get('feedback/{feedback_id}/reply', [AdminFeedbackController::class, 'replyForm'])->name('feedback.reply.form');
    Route::post('feedback/{feedback_id}/reply', [AdminFeedbackController::class, 'replySubmit'])->name('feedback.reply.submit');

    // Reports
    Route::get('/reports', [AdminReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [AdminReportsController::class, 'exportPDF'])->name('reports.export_pdf');


    // Profile
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
});

// ----------------------------
// Staff Routes
// ----------------------------
Route::prefix('staff')->middleware('auth:staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');


     // Staff Bookings CRUD (resource route)
    Route::resource('bookings', StaffBookingController::class)->names([
        'index' => 'bookings.index',
        'store' => 'bookings.store',
        'update'=> 'bookings.update',
        'destroy'=> 'bookings.destroy', // This one fixes your error
        'create'=> 'bookings.create',
        'edit'=> 'bookings.edit',
        'show'=> 'bookings.show',
    ]);


    // Schedule
Route::get('schedule', [StaffScheduleController::class, 'index'])->name('schedule.index');
Route::get('schedule/create', [StaffScheduleController::class, 'create'])->name('schedule.create');
Route::post('schedule/store', [StaffScheduleController::class, 'store'])->name('schedule.store');
Route::get('schedule/edit/{id}', [StaffScheduleController::class, 'edit'])->name('schedule.edit');
Route::put('schedule/update/{id}', [StaffScheduleController::class, 'update'])->name('schedule.update');
Route::delete('schedule/delete/{id}', [StaffScheduleController::class, 'destroy'])->name('schedule.delete');

 // Reports ✅
    Route::get('reports', [StaffReportsController::class, 'index'])->name('reports.index'); // staff.reports.index
    Route::get('reports/export_pdf', [StaffReportsController::class, 'exportPDF'])->name('reports.export_pdf'); // staff.reports

});

// ----------------------------
// Customer Routes
// ----------------------------
// ----------------------------
// Customer Routes
// ----------------------------
Route::middleware('auth:customers')->prefix('customer')->name('customer.')->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboardCustomer', [
        'customer' => Auth::guard('customers')->user()
    ]))->name('dashboard');

    // Booking
    Route::get('/booking/create/{facilityName}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/submit', [BookingController::class, 'store'])->name('booking.submit');
    Route::get('/mybookings', [BookingController::class, 'myBookings'])->name('mybookings');
    Route::put('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    
    // Payment
     Route::get('/payment/{bookingId}', [CustomerPaymentController::class, 'paymentPage'])
->name('payment.page');

    Route::get('/payment/online/{bookingId}', [CustomerPaymentController::class, 'showOnlinePayment'])->name('payment.online');
    Route::get('/payment/cash/{bookingId}', [CustomerPaymentController::class, 'showCashPayment'])->name('payment.cash');
    Route::post('/payment/redirect', [CustomerPaymentController::class, 'redirectToPaymentPage'])->name('payment.redirect');
    Route::post('/payment/store', [CustomerPaymentController::class, 'store'])->name('payment.store');

    
    // Feedback
    Route::get('/feedback', [CustomerFeedbackController::class, 'index'])->name('feedback.list');
    Route::get('/feedback/create/{bookingId}', [CustomerFeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [CustomerFeedbackController::class, 'store'])->name('feedback.store');

    // Profile
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [CustomerController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [CustomerController::class, 'updateProfile'])
     ->name('profile.update');

});


// ----------------------------
// Static Pages
// ----------------------------
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

Route::get('/slots', [AdminBookingController::class, 'getAvailableSlots']);

Route::put('/customer/bookings/{booking}/autoCancel', [BookingController::class, 'autoCancel'])->name('customer.booking.autoCancel');
