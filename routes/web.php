<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PDFController;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(PDFController::class)->group(function(){
    Route::get('/generate/{customer}', 'show')
       ->name('document.show');
});

/* COMPANY ROUTES */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(CompanyController::class)->group(function () {

   Route::get('/onboarding/company/create', 'index')
       ->name('company.create');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(CustomerController::class)->group(function () {

    Route::get('customer/create', 'index')
        ->name('customer.create');

    Route::get('customer/list', 'show')
        ->name('customer.list');
});