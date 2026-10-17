<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerBrowseController;
use App\Http\Controllers\GadgetController;
use App\Http\Controllers\LenderDashboardController;
use App\Http\Controllers\LenderSettingsController;
use App\Http\Controllers\LenderStoreController;
use App\Http\Controllers\LenderTransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('gadgets', GadgetController::class)->middleware('auth');

Route::get('/', function () {
    return view('Opening_Display.Homepage');
})->name('homepage');

Route::get('/login', function () {
    return view('Opening_Display.registration');
})->name('login.form');

Route::post('/user', [UserController::class, 'store'])->name('users');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/customer_browse', CustomerBrowseController::class)
    ->name('customer.browse');

Route::get('/rentals', function () {
    return view('Customer_Page.my_rentals');
})->name('myrentals');

Route::get('/details', function () {
    return view('Customer_Page.details');
})->name('details');

// lender-page
Route::get('/lender-dashboard', LenderDashboardController::class)
    ->middleware('auth')
    ->name('lender.dashboard');

Route::get('/lender-store', LenderStoreController::class)
    ->middleware('auth')
    ->name('lender.store');

Route::get('/lender-gadgets', [GadgetController::class, 'index'])
    ->middleware('auth')
    ->name('lender.gadgets');

Route::get('/lender-transaction', LenderTransactionController::class)
    ->middleware('auth')
    ->name('lender.transaction');

Route::get('/lender-renters', function () {
    return view('Seller-Page.renters');
})->name('lender.renters');

Route::get('/lender-settings', [LenderSettingsController::class, 'index'])
    ->middleware('auth')
    ->name('lender.settings');
Route::put('/lender-settings/profile', [LenderSettingsController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('lender.settings.profile');
Route::put('/lender-settings/password', [LenderSettingsController::class, 'updatePassword'])
    ->middleware('auth')
    ->name('lender.settings.password');
Route::post('/logout', [LenderSettingsController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
Route::get('lender-add-gadget', [GadgetController::class, 'create'])
    ->middleware('auth')
    ->name('lender.add-gadget');
Route::post('lender-add-gadget', [GadgetController::class, 'store'])
    ->middleware('auth')
    ->name('lender.add-gadget.store');

// admin-page
Route::get('/admin-dashboard', function () {
    return view('Admin_Page.admin_dashboard');
})->name('admin.dashboard');
Route::get('/admin-rentals', function () {
    return view('Admin_Page.rentals');
})->name('admin.rentals');
Route::get('/admin-stores', function () {
    return view('Admin_Page.seller-and-stores');
})->name('admin.stores');
