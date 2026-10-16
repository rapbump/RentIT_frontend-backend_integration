<?php

use Illuminate\Support\Facades\Route;

use function Dom\import_simplexml;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('Opening_Display.Homepage');
})->name('homepage');

Route::get('/login', function () {
    return view('Opening_Display.registration');
})->name('login.form');

Route::post('/user', [UserController::class, 'store'])->name('users');


Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/customer_browse', function () {
    return view('Customer_Page.customer_browse');
})->name('customer.browse');

Route::get('/rentals', function(){
    return view('Customer_Page.my_rentals');
})->name('myrentals');

Route::get('/details', function(){
    return view('Customer_Page.details');
})->name('details');

//lender-page
Route::get('/lender-dashboard', function(){
    return view('Seller-Page.Dashboard');
})->name('lender.dashboard');

Route::get('/lender-store', function(){
    return view('Seller-Page.myStore');
})->name('lender.store');

Route::get('/lender-gadgets', function(){
    return view('Seller-Page.gadgets');
})->name('lender.gadgets');

Route::get('/lender-transaction', function(){
    return view('Seller-Page.transaction');
})->name('lender.transaction');

Route::get('/lender-renters', function(){
    return view('Seller-Page.renters');
})->name('lender.renters');

Route::get('/lender-settings', function(){
    return view('Seller-Page.Settings');
})->name('lender.settings');


//admin-page
Route::get('/admin-dashboard', function(){
    return view('Admin_Page.admin_dashboard');
})->name('admin.dashboard');
Route::get('/admin-rentals', function(){
    return view('Admin_Page.rentals');
})->name('admin.rentals');
Route::get('/admin-stores', function(){
    return view('Admin_Page.seller-and-stores');
})->name('admin.stores');
