<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminRentalController;
use App\Http\Controllers\AdminSellerController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerBrowseController;
use App\Http\Controllers\CustomerCartController;
use App\Http\Controllers\CustomerPageController;
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

Route::get('/rentals', [CustomerPageController::class, 'myRentals'])
    ->middleware('auth')
    ->name('myrentals');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CustomerCartController::class, 'index'])->name('customer.cart');
    Route::post('/cart/items/{gadget}', [CustomerCartController::class, 'store'])->name('customer.cart.store');
    Route::delete('/cart/items/{cartItem}', [CustomerCartController::class, 'destroy'])->name('customer.cart.destroy');
    Route::post('/cart/request', [CustomerCartController::class, 'requestRentals'])
        ->name('customer.cart.request');
});

Route::get('/details/{gadget}', [CustomerPageController::class, 'showGadget'])
    ->name('details');

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
Route::patch('/lender-transaction/{rental}/approve', [LenderTransactionController::class, 'approveRequest'])
    ->middleware('auth')
    ->name('lender.transaction.approve');
Route::patch('/lender-transaction/{rental}/reject', [LenderTransactionController::class, 'rejectRequest'])
    ->middleware('auth')
    ->name('lender.transaction.reject');

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
Route::middleware('auth')->group(function () {
    Route::get('/admin-dashboard', AdminDashboardController::class)->name('admin.dashboard');
    Route::get('/admin-rentals', AdminRentalController::class)->name('admin.rentals');
    Route::get('/admin-stores', [AdminSellerController::class, 'index'])->name('admin.stores');
    Route::patch('/admin-stores/{seller}/verification', [AdminSellerController::class, 'updateVerificationStatus'])
        ->name('admin.stores.verification');
    Route::get('/admin-settings', [AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::put('/admin-settings/profile', [AdminSettingsController::class, 'updateProfile'])
        ->name('admin.settings.profile');
    Route::put('/admin-settings/password', [AdminSettingsController::class, 'updatePassword'])
        ->name('admin.settings.password');
    Route::put('/admin-settings/platform', [AdminSettingsController::class, 'updatePlatform'])
        ->name('admin.settings.platform');
});
