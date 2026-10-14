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



