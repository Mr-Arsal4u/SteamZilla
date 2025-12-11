<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/pricing', [PackageController::class, 'index'])->name('pricing');
Route::get('/gift-cards', [GiftCardController::class, 'index'])->name('gift-cards');
Route::post('/gift-cards/buy', [GiftCardController::class, 'buy'])->name('gift-cards.buy');
Route::post('/gift-cards/check-balance', [GiftCardController::class, 'checkBalance'])->name('gift-cards.check-balance');
Route::post('/gift-cards/check-reload', [GiftCardController::class, 'checkReloadCard'])->name('gift-cards.check-reload');
Route::post('/gift-cards/reload', [GiftCardController::class, 'reload'])->name('gift-cards.reload');
Route::get('/gift-cards/success/{id}', [GiftCardController::class, 'success'])->name('gift-cards.success');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');

// User Authentication Routes
Route::get('/login', [AuthController::class, 'showUserLogin'])->name('user.login');
Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.post');
Route::get('/register', [AuthController::class, 'showUserRegister'])->name('user.register');
Route::post('/register', [AuthController::class, 'userRegister'])->name('user.register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Booking Routes - Multi-step flow
Route::get('/order-now', [BookingController::class, 'step1'])->name('order-now');
Route::get('/booking/step1', [BookingController::class, 'step1'])->name('booking.step1');
Route::post('/booking/step1', [BookingController::class, 'step1Store'])->name('booking.step1.store');
Route::get('/booking/step2', [BookingController::class, 'step2'])->name('booking.step2');
Route::post('/booking/step2', [BookingController::class, 'step2Store'])->name('booking.step2.store');
Route::get('/booking/step3', [BookingController::class, 'step3'])->name('booking.step3');
Route::post('/booking/step3', [BookingController::class, 'step3Store'])->name('booking.step3.store');
Route::get('/booking/step4', [BookingController::class, 'step4'])->name('booking.step4');
Route::post('/booking/step4', [BookingController::class, 'step4Store'])->name('booking.step4.store');
Route::get('/booking/success/{id}', [BookingController::class, 'success'])->name('booking.success');

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::get('/admin/register', [AuthController::class, 'showRegister'])->name('admin.register');
Route::post('/admin/register', [AuthController::class, 'register'])->name('admin.register.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Protected Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Bookings Management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.update-status');
    Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('bookings.delete');
    
    // Payments Management
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    
    // Packages Management
    Route::get('/packages', [AdminController::class, 'packages'])->name('packages');
    Route::get('/packages/create', [AdminController::class, 'createPackage'])->name('packages.create');
    Route::post('/packages', [AdminController::class, 'storePackage'])->name('packages.store');
    Route::get('/packages/{id}/edit', [AdminController::class, 'editPackage'])->name('packages.edit');
    Route::post('/packages/{id}', [AdminController::class, 'updatePackage'])->name('packages.update');
    Route::delete('/packages/{id}', [AdminController::class, 'deletePackage'])->name('packages.delete');
    
    // Addons Management
    Route::get('/addons', [AdminController::class, 'addons'])->name('addons');
    Route::get('/addons/create', [AdminController::class, 'createAddon'])->name('addons.create');
    Route::post('/addons', [AdminController::class, 'storeAddon'])->name('addons.store');
    Route::get('/addons/{id}/edit', [AdminController::class, 'editAddon'])->name('addons.edit');
    Route::post('/addons/{id}', [AdminController::class, 'updateAddon'])->name('addons.update');
    Route::delete('/addons/{id}', [AdminController::class, 'deleteAddon'])->name('addons.delete');
    
    // Settings Management
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Gallery Management
    Route::get('/gallery', [AdminController::class, 'gallery'])->name('gallery');
    Route::post('/gallery', [AdminController::class, 'storeGalleryImage'])->name('gallery.store');
    Route::post('/gallery/{id}', [AdminController::class, 'updateGalleryImage'])->name('gallery.update');
    Route::delete('/gallery/{id}', [AdminController::class, 'deleteGalleryImage'])->name('gallery.delete');
    
        // Page Content Management
        Route::get('/pages/{page}', [AdminController::class, 'pageContent'])->name('pages.content');
        Route::post('/pages/{page}', [AdminController::class, 'updatePageContent'])->name('pages.update');

        // Contact Submissions Management
        Route::get('/contact-submissions', [AdminController::class, 'contactSubmissions'])->name('contact-submissions');
        Route::get('/contact-submissions/{id}', [AdminController::class, 'showContactSubmission'])->name('contact-submissions.show');
        Route::post('/contact-submissions/{id}/mark-read', [AdminController::class, 'markContactSubmissionRead'])->name('contact-submissions.mark-read');
        Route::delete('/contact-submissions/{id}', [AdminController::class, 'deleteContactSubmission'])->name('contact-submissions.delete');
    });

// User Protected Routes
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/bookings', [UserController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [UserController::class, 'showBooking'])->name('bookings.show');
    Route::get('/contact', [UserController::class, 'contact'])->name('contact');
    Route::post('/contact', [UserController::class, 'submitContact'])->name('contact.submit');
    Route::get('/activity', [UserController::class, 'activity'])->name('activity');
});
