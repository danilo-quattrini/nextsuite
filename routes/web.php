<?php

use App\Http\Controllers\CompanyController;
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
        $customers = Customer::with('skills')
            ->paginate(5);
        return view('dashboard', compact('customers'));
    })->name('dashboard');

    Route::get('/onboarding/customer/create', function () {
        return view('create-customer');
    })->name('customer.create');
});
